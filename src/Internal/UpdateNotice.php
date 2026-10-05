<?php

declare(strict_types=1);

namespace OpenEmail\Internal;

use OpenEmail\OpenEmail;

final class UpdateNotice
{
    private const SELECT_SLICE = 0.1;

    private static bool $claimed = false;

    private static ?\CurlMultiHandle $multi = null;

    private static ?\CurlHandle $handle = null;

    private static float $startedAt = 0.0;

    public static function check(): void
    {
        if (self::$claimed) {
            return;
        }

        self::$claimed = true;

        try {
            if (!self::isDisabled()) {
                self::start();
            }
        } catch (\Throwable) {
            self::release();
        }
    }

    public static function poll(): void
    {
        self::settle(0.0);
    }

    public static function finish(): void
    {
        self::settle(max(0.0, Defaults::UPDATE_NOTICE['TIMEOUT'] - (microtime(true) - self::$startedAt)));
    }

    public static function latestVersion(string $body): ?string
    {
        $data = Json::tryDecode($body);
        $packages = \is_array($data) ? ($data['packages'] ?? null) : null;
        $versions = \is_array($packages) ? ($packages[Defaults::PACKAGE] ?? null) : null;

        if (!\is_array($versions)) {
            return null;
        }

        $best = null;

        foreach ($versions as $entry) {
            $version = \is_array($entry) ? ($entry['version'] ?? null) : null;

            if (!\is_string($version) || self::parts($version) === null) {
                continue;
            }

            if ($best === null || self::isNewer($version, $best)) {
                $best = $version;
            }
        }

        return $best === null ? null : ltrim(trim($best), 'v');
    }

    public static function isNewer(string $candidate, string $current): bool
    {
        $left = self::parts($candidate);
        $right = self::parts($current);

        if ($left === null || $right === null) {
            return false;
        }

        return ($left <=> $right) === 1;
    }

    public static function parts(string $value): ?array
    {
        $pieces = explode('.', ltrim(trim($value), 'v'));

        if (\count($pieces) !== 3) {
            return null;
        }

        $numbers = [];

        foreach ($pieces as $piece) {
            if (preg_match('/^\d+$/', $piece) !== 1) {
                return null;
            }

            $numbers[] = (int) $piece;
        }

        return $numbers;
    }

    public static function notice(string $latest): string
    {
        return \sprintf(
            Messages::UPDATE_AVAILABLE,
            Defaults::UPDATE_NOTICE['ICON'],
            Defaults::PACKAGE,
            $latest,
            OpenEmail::VERSION,
            Defaults::UPDATE_NOTICE['PAGE_URL'],
        );
    }

    private static function isDisabled(): bool
    {
        if (\PHP_SAPI !== 'cli' && \PHP_SAPI !== 'phpdbg') {
            return true;
        }

        if (Credentials::fromEnvironment(Defaults::UPDATE_NOTICE['ENV_DISABLE']) !== null || !\extension_loaded('curl')) {
            return true;
        }

        return !\defined('STDOUT') || !\defined('STDERR') || !\is_resource(\STDOUT) || !\is_resource(\STDERR) || !stream_isatty(\STDOUT);
    }

    private static function settle(float $budget): void
    {
        try {
            self::advance($budget);
        } catch (\Throwable) {
            self::release();
        }
    }

    private static function start(): void
    {
        $handle = curl_init(Defaults::UPDATE_NOTICE['REGISTRY_URL']);

        if ($handle === false) {
            return;
        }

        $milliseconds = Defaults::UPDATE_NOTICE['TIMEOUT'] * Defaults::MILLISECONDS_PER_SECOND;

        curl_setopt_array($handle, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => false,
            CURLOPT_NOSIGNAL => true,
            CURLOPT_TIMEOUT_MS => $milliseconds,
            CURLOPT_CONNECTTIMEOUT_MS => $milliseconds,
            CURLOPT_ENCODING => '',
            CURLOPT_HTTPHEADER => [
                Protocol::HEADER_KEYS['ACCEPT'] . ': ' . Protocol::CONTENT_TYPES['JSON'],
                Protocol::HEADER_KEYS['USER_AGENT'] . ': ' . Defaults::USER_AGENT,
            ],
        ]);

        $multi = curl_multi_init();
        curl_multi_add_handle($multi, $handle);

        self::$multi = $multi;
        self::$handle = $handle;
        self::$startedAt = microtime(true);

        register_shutdown_function([self::class, 'finish']);
        self::advance(0.0);
    }

    private static function advance(float $budget): void
    {
        $multi = self::$multi;

        if ($multi === null) {
            return;
        }

        $deadline = microtime(true) + $budget;

        while (true) {
            $running = 0;

            if (curl_multi_exec($multi, $running) !== CURLM_OK) {
                self::release();

                return;
            }

            if ($running === 0) {
                self::complete();

                return;
            }

            $left = $deadline - microtime(true);

            if ($left <= 0) {
                return;
            }

            curl_multi_select($multi, min($left, self::SELECT_SLICE));
        }
    }

    private static function complete(): void
    {
        $handle = self::$handle;
        $body = $handle === null ? null : curl_multi_getcontent($handle);
        $status = $handle === null ? 0 : curl_getinfo($handle, CURLINFO_RESPONSE_CODE);

        self::release();

        if ($status < 200 || $status > 299 || !\is_string($body)) {
            return;
        }

        $latest = self::latestVersion($body);

        if ($latest !== null && self::isNewer($latest, OpenEmail::VERSION) && \defined('STDERR') && \is_resource(\STDERR)) {
            @fwrite(\STDERR, self::notice($latest) . \PHP_EOL);
        }
    }

    private static function release(): void
    {
        if (self::$multi !== null && self::$handle !== null) {
            curl_multi_remove_handle(self::$multi, self::$handle);
        }

        self::$multi = null;
        self::$handle = null;
    }
}
