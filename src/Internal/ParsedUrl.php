<?php

declare(strict_types=1);

namespace OpenEmail\Internal;

final class ParsedUrl
{
    private const DEFAULT_PORTS = ['http' => 80, 'https' => 443];

    private function __construct(
        public readonly string $scheme,
        public readonly string $host,
        public readonly ?int $port,
        public readonly string $path,
        public readonly bool $hasUserInfo,
    ) {}

    public static function http(string $text): ?self
    {
        $parts = parse_url($text);

        if (!\is_array($parts)) {
            return null;
        }

        $scheme = \is_string($parts['scheme'] ?? null) ? strtolower($parts['scheme']) : '';
        $host = \is_string($parts['host'] ?? null) ? $parts['host'] : '';

        if (!isset(self::DEFAULT_PORTS[$scheme]) || $host === '' || preg_match(Defaults::HOST_CHARACTERS, $host) !== 1) {
            return null;
        }

        return new self(
            $scheme,
            $host,
            \is_int($parts['port'] ?? null) ? $parts['port'] : null,
            \is_string($parts['path'] ?? null) ? $parts['path'] : '',
            isset($parts['user']) || isset($parts['pass']),
        );
    }

    public function effectivePort(): int
    {
        return $this->port ?? self::DEFAULT_PORTS[$this->scheme] ?? 0;
    }
}
