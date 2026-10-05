<?php

declare(strict_types=1);

namespace OpenEmail\Http;

use OpenEmail\Exception\InvalidArgumentException;
use OpenEmail\Internal\Defaults;
use OpenEmail\Internal\Messages;

final class CurlHttpClient implements HttpClient
{
    private static array $inherited = [];

    private readonly ?\SensitiveParameterValue $proxy;

    private readonly \SensitiveParameterValue $curlOptions;

    private ?\CurlHandle $handle = null;

    private int $owner = 0;

    public function __construct(
        private readonly ?string $caBundle = null,
        #[\SensitiveParameter]
        ?string $proxy = null,
        #[\SensitiveParameter]
        array $curlOptions = [],
    ) {
        if (!\extension_loaded('curl')) {
            throw new InvalidArgumentException(Messages::CURL_REQUIRED);
        }

        foreach (array_keys($curlOptions) as $option) {
            if (!\is_int($option)) {
                throw new InvalidArgumentException(Messages::CURL_OPTION_KEY);
            }

            if (\defined('CURLOPT_REQUEST_TARGET') && $option === \constant('CURLOPT_REQUEST_TARGET')) {
                throw new InvalidArgumentException(Messages::CURL_OPTION_TARGET);
            }
        }

        $this->proxy = $proxy === null ? null : new \SensitiveParameterValue($proxy);
        $this->curlOptions = new \SensitiveParameterValue($curlOptions);
    }

    public function send(#[\SensitiveParameter] HttpRequest $request): HttpResponse
    {
        $handle = $this->handle();
        $collected = [];
        $options = $this->optionsFor($request);
        $options[CURLOPT_HEADERFUNCTION] = static function (\CurlHandle $handle, string $line) use (&$collected): int {
            self::collect($collected, $line);

            return \strlen($line);
        };

        foreach ($options as $option => $value) {
            self::apply($handle, $option, $value);
        }

        $body = curl_exec($handle);

        if ($body === false) {
            $number = curl_errno($handle);
            $reason = curl_error($handle);

            throw new HttpClientException(
                $reason !== '' ? $reason : (curl_strerror($number) ?? Messages::UNKNOWN_FAILURE),
                $number === CURLE_OPERATION_TIMEDOUT,
            );
        }

        return new HttpResponse(curl_getinfo($handle, CURLINFO_RESPONSE_CODE), $collected, \is_string($body) ? $body : '');
    }

    public function close(): void
    {
        $this->forgetInherited();
        $this->handle = null;
    }

    public function __debugInfo(): array
    {
        return [
            'caBundle' => $this->caBundle,
            'proxy' => $this->proxy === null ? null : Defaults::REDACTED,
            'curlOptions' => array_keys($this->userOptions()),
            'inheritedHandles' => \count(self::$inherited),
        ];
    }

    private function handle(): \CurlHandle
    {
        $this->forgetInherited();

        if ($this->handle !== null) {
            curl_reset($this->handle);

            return $this->handle;
        }

        $handle = curl_init();

        if ($handle === false) {
            throw new HttpClientException(Messages::CURL_HANDLE);
        }

        $this->handle = $handle;
        $this->owner = (int) getmypid();

        return $handle;
    }

    private function forgetInherited(): void
    {
        if ($this->handle === null || $this->owner === (int) getmypid()) {
            return;
        }

        self::$inherited[] = $this->handle;
        $this->handle = null;
    }

    private function userOptions(): array
    {
        return $this->curlOptions->getValue();
    }

    private function optionsFor(#[\SensitiveParameter] HttpRequest $request): array
    {
        $defaults = [
            CURLOPT_NOSIGNAL => true,
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_SSL_VERIFYHOST => 2,
            CURLOPT_ENCODING => '',
        ];

        if (\defined('CURL_SSLVERSION_TLSv1_2')) {
            $defaults[CURLOPT_SSLVERSION] = \constant('CURL_SSLVERSION_TLSv1_2');
        }

        if (\defined('CURLOPT_PROTOCOLS_STR')) {
            $defaults[\constant('CURLOPT_PROTOCOLS_STR')] = 'http,https';
        } else {
            $defaults[CURLOPT_PROTOCOLS] = CURLPROTO_HTTP | CURLPROTO_HTTPS;
        }

        if ($request->timeout !== null && $request->timeout > 0) {
            $milliseconds = max(1, (int) ceil($request->timeout * 1000));
            $defaults[CURLOPT_TIMEOUT_MS] = $milliseconds;
            $defaults[CURLOPT_CONNECTTIMEOUT_MS] = $milliseconds;
        }

        if ($this->caBundle !== null) {
            $defaults[CURLOPT_CAINFO] = $this->caBundle;
        }

        $proxy = $this->proxy?->getValue();

        if (\is_string($proxy)) {
            $defaults[CURLOPT_PROXY] = $proxy;
        }

        $required = [
            CURLOPT_URL => $request->url,
            CURLOPT_PORT => 0,
            CURLOPT_FOLLOWLOCATION => false,
            CURLOPT_UPLOAD => false,
            CURLOPT_HTTPGET => true,
            CURLOPT_CUSTOMREQUEST => $request->method,
            CURLOPT_HTTPHEADER => $this->headerLines($request),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER => false,
            CURLOPT_NOBODY => $request->method === 'HEAD',
        ];

        if ($request->body !== null) {
            $required[CURLOPT_POSTFIELDS] = $request->body;
        } elseif (\in_array($request->method, Defaults::BODYLESS_WRITES, true)) {
            $required[CURLOPT_POSTFIELDS] = '';
        }

        return array_diff_key(array_replace($defaults, $this->userOptions()), $required) + $required;
    }

    private function headerLines(#[\SensitiveParameter] HttpRequest $request): array
    {
        $lines = [];
        $named = [];

        foreach ($request->headers as $name => $value) {
            if (!\is_string($value)) {
                continue;
            }

            $lines[] = $name . ': ' . $value;
            $named[strtolower((string) $name)] = true;
        }

        foreach (Defaults::SUPPRESSED_CURL_HEADERS as $name) {
            if (!isset($named[strtolower($name)])) {
                $lines[] = $name . ':';
            }
        }

        return $lines;
    }

    private static function apply(\CurlHandle $handle, int $option, #[\SensitiveParameter] mixed $value): void
    {
        try {
            $accepted = curl_setopt($handle, $option, $value);
        } catch (\ValueError|\TypeError $error) {
            throw new InvalidArgumentException(\sprintf(Messages::CURL_OPTION, self::optionName($option), $error->getMessage()), 0, $error);
        }

        if (!$accepted) {
            throw new InvalidArgumentException(\sprintf(Messages::CURL_OPTION, self::optionName($option), curl_strerror(curl_errno($handle)) ?? Messages::UNKNOWN_FAILURE));
        }
    }

    private static function optionName(int $option): string
    {
        foreach (get_defined_constants(true)['curl'] ?? [] as $name => $value) {
            if ($value === $option && str_starts_with($name, 'CURLOPT_')) {
                return $name;
            }
        }

        return (string) $option;
    }

    private static function collect(array &$headers, string $line): void
    {
        $text = rtrim($line, "\r\n");

        if (str_starts_with($text, 'HTTP/')) {
            $headers = [];

            return;
        }

        $at = strpos($text, ':');

        if ($at === false || $at === 0) {
            return;
        }

        $name = strtolower(trim(substr($text, 0, $at)));
        $value = trim(substr($text, $at + 1));
        $existing = $headers[$name] ?? null;
        $headers[$name] = \is_string($existing) ? $existing . ', ' . $value : $value;
    }
}
