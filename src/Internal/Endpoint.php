<?php

declare(strict_types=1);

namespace OpenEmail\Internal;

use OpenEmail\Exception\InvalidArgumentException;

final class Endpoint
{
    public static function validBaseUrl(#[\SensitiveParameter] string $value): string
    {
        $text = self::trimmed($value);
        $url = preg_match(Defaults::URL_FORBIDDEN, $text) === 1 ? null : ParsedUrl::http($text);

        if ($url === null) {
            throw new InvalidArgumentException(self::quoted((string) preg_replace(Defaults::URL_USERINFO, '$1', trim($value))) . ' ' . Messages::BASE_URL_SHAPE);
        }

        if ($url->hasUserInfo) {
            throw new InvalidArgumentException(Messages::BASE_URL_USERINFO);
        }

        if (self::isUnspecifiedHost($url->host)) {
            throw new InvalidArgumentException(self::unspecifiedHostMessage($value, $url));
        }

        return $text;
    }

    public static function normalise(#[\SensitiveParameter] ?string $value): ?string
    {
        if ($value === null) {
            return null;
        }

        $text = trim($value);
        $stripped = self::withoutTrailingSlashes($text);

        if (preg_match(Defaults::ENDPOINT_SCHEME_PATTERN, $text) === 1) {
            return $stripped;
        }

        if ($stripped === '') {
            return null;
        }

        return (self::isLocalHost(self::hostnameOf($stripped)) ? 'http' : 'https') . '://' . $stripped;
    }

    public static function isSecureOrigin(string $url): bool
    {
        $parsed = ParsedUrl::http($url);

        if ($parsed === null) {
            return false;
        }

        return $parsed->scheme === 'https' || self::isLoopbackHost($parsed->host);
    }

    public static function buildUrl(string $baseUrl, string $path, ?array $query): string
    {
        $url = self::apiUrl($baseUrl, $path);
        $pairs = [];

        foreach ($query ?? [] as $key => $value) {
            if ($value === null || $value === '') {
                continue;
            }

            $pairs[] = self::formEncode((string) $key) . '=' . self::formEncode(self::queryText($value));
        }

        return $pairs === [] ? $url : $url . '?' . implode('&', $pairs);
    }

    public static function queryText(mixed $value): string
    {
        if (\is_array($value)) {
            return implode(Protocol::LIST_SEPARATOR, array_map(self::queryText(...), array_values($value)));
        }

        $scalar = Wire::scalar($value);

        return match (true) {
            \is_bool($scalar) => $scalar ? 'true' : 'false',
            \is_int($scalar) => (string) $scalar,
            \is_float($scalar) => Wire::numberText($scalar),
            \is_string($scalar) => $scalar,
            $scalar === null => '',
            default => throw new InvalidArgumentException(\sprintf(Messages::BODY_VALUE_SHAPE, get_debug_type($value))),
        };
    }

    public static function formEncode(string $value): string
    {
        return strtr(rawurlencode($value), ['%20' => '+', '%2A' => '*', '~' => '%7E']);
    }

    public static function apiUrl(string $baseUrl, string $path): string
    {
        if (!str_starts_with($path, '/') || str_starts_with($path, '//') || str_starts_with($path, '/\\') || preg_match(Defaults::PATH_FORBIDDEN, $path) === 1) {
            throw new InvalidArgumentException(self::quoted($path) . ' ' . Messages::PATH_SHAPE);
        }

        $url = self::withoutTrailingSlashes($baseUrl) . $path;
        $origin = self::originOf($url);

        if ($origin === null || $origin !== self::originOf($baseUrl)) {
            throw new InvalidArgumentException(self::quoted($path) . ' ' . Messages::OFF_ORIGIN);
        }

        return $url;
    }

    public static function isLoopbackHost(string $host): bool
    {
        $canonical = self::canonicalHost($host);

        return \in_array($canonical, Protocol::LOOPBACK_HOSTNAMES, true)
            || preg_match(Defaults::LOOPBACK_IPV4_PATTERN, $canonical) === 1;
    }

    public static function isLocalHost(string $host): bool
    {
        $canonical = self::canonicalHost($host);

        return \in_array($canonical, Protocol::LOCAL_HOSTS, true)
            || preg_match(Defaults::LOOPBACK_IPV4_PATTERN, $canonical) === 1;
    }

    public static function isUnspecifiedHost(string $host): bool
    {
        return \in_array(self::canonicalHost($host), Protocol::UNSPECIFIED_HOSTNAMES, true);
    }

    public static function canonicalHost(string $host): string
    {
        $text = strtolower($host);

        if ($text === '') {
            return $text;
        }

        if (str_starts_with($text, '[')) {
            return self::ipv6Host($text);
        }

        $parts = explode('.', $text);

        if (\count($parts) > 1 && end($parts) === '') {
            array_pop($parts);
        }

        if (\count($parts) > 4) {
            return $text;
        }

        $numbers = [];

        foreach ($parts as $part) {
            $number = self::ipv4Number($part);

            if ($number === null) {
                return $text;
            }

            $numbers[] = $number;
        }

        return self::ipv4Address($numbers) ?? $text;
    }

    public static function quoted(string $value): string
    {
        $encoded = json_encode($value, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

        return \is_string($encoded) ? $encoded : var_export($value, true);
    }

    private static function ipv6Host(string $text): string
    {
        $packed = @inet_pton(trim($text, '[]'));

        if ($packed === false || \strlen($packed) !== 16) {
            return $text;
        }

        $address = inet_ntop($packed);

        return $address === false ? $text : '[' . strtolower($address) . ']';
    }

    private static function ipv4Number(string $part): int|float|null
    {
        if (preg_match(Defaults::IPV4_HEX_PART, $part) === 1) {
            return \strlen($part) === 2 ? 0 : hexdec(substr($part, 2));
        }

        if (preg_match(Defaults::IPV4_OCTAL_PART, $part) === 1) {
            return octdec($part);
        }

        if (preg_match(Defaults::IPV4_DECIMAL_PART, $part) === 1) {
            return \strlen($part) > 15 ? INF : (int) $part;
        }

        return null;
    }

    private static function ipv4Address(array $numbers): ?string
    {
        $count = \count($numbers);
        $last = array_pop($numbers);

        if (!\is_int($last) && !\is_float($last)) {
            return null;
        }

        foreach ($numbers as $number) {
            if (!\is_int($number) || $number > 255) {
                return null;
            }
        }

        if ($last >= 256 ** (5 - $count)) {
            return null;
        }

        $value = (int) $last;

        foreach (array_values($numbers) as $index => $number) {
            $value += $number * (256 ** (3 - $index));
        }

        return implode('.', array_map(static fn(int $shift): string => (string) (($value >> $shift) & 255), [24, 16, 8, 0]));
    }

    private static function originOf(string $url): ?string
    {
        $parsed = ParsedUrl::http($url);

        if ($parsed === null) {
            return null;
        }

        return $parsed->scheme . '://' . self::canonicalHost($parsed->host) . ':' . $parsed->effectivePort();
    }

    private static function hostnameOf(string $authority): string
    {
        $parts = parse_url('http://' . $authority);

        return \is_array($parts) && isset($parts['host']) ? $parts['host'] : '';
    }

    private static function unspecifiedHostMessage(string $value, ParsedUrl $url): string
    {
        $substitute = str_starts_with($url->host, '[') ? Protocol::LOOPBACK_IPV6_ADDRESS : Protocol::LOOPBACK_IPV4_ADDRESS;
        $port = $url->port === null ? '' : ':' . $url->port;
        $suggestion = self::withoutTrailingSlashes($url->scheme . '://' . $substitute . $port . $url->path);

        return \sprintf(Messages::UNSPECIFIED_HOST, self::quoted($value), self::canonicalHost($url->host), $suggestion);
    }

    private static function trimmed(string $value): string
    {
        return self::withoutTrailingSlashes(trim($value));
    }

    private static function withoutTrailingSlashes(string $value): string
    {
        return (string) preg_replace(Defaults::TRAILING_SLASHES, '', $value);
    }
}
