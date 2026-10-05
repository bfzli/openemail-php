<?php

declare(strict_types=1);

namespace OpenEmail;

use OpenEmail\Constants\WebhookSignatureHeaders;
use OpenEmail\Exception\InvalidArgumentException;
use OpenEmail\Exception\WebhookSignatureException;
use OpenEmail\Internal\Defaults;
use OpenEmail\Internal\Json;
use OpenEmail\Internal\Messages;
use OpenEmail\Internal\Protocol;
use OpenEmail\Internal\Wire;
use Psr\Http\Message\MessageInterface;

final class Webhook
{
    public static function verify(
        string $payload,
        mixed $headers,
        #[\SensitiveParameter]
        string $secret,
        ?int $toleranceSeconds = null,
    ): array {
        if (trim($secret) === '') {
            throw new InvalidArgumentException(Messages::WEBHOOK_SECRET_REQUIRED);
        }

        $header = self::header($headers, WebhookSignatureHeaders::SIGNATURE);

        if ($header === null || $header === '') {
            throw new WebhookSignatureException(Messages::WEBHOOK_MISSING_HEADER);
        }

        $timestamp = self::timestampOf($header);
        $signatures = self::signaturesOf($header);

        if ($timestamp === null || $signatures === []) {
            throw new WebhookSignatureException(Messages::WEBHOOK_MALFORMED_HEADER);
        }

        self::assertFresh($timestamp, $toleranceSeconds ?? Protocol::WEBHOOK_SIGNATURE['TOLERANCE_SECONDS']);

        $stamp = \is_int($timestamp) ? (string) $timestamp : Wire::numberText($timestamp);
        $expected = hash_hmac('sha256', $stamp . '.' . $payload, $secret);
        $matched = false;

        foreach ($signatures as $candidate) {
            $matched = (\is_string($candidate) && hash_equals($expected, $candidate)) || $matched;
        }

        if (!$matched) {
            throw new WebhookSignatureException(Messages::WEBHOOK_MISMATCH);
        }

        try {
            $event = Json::decode(Json::text($payload));
        } catch (\JsonException $error) {
            throw new WebhookSignatureException(Messages::WEBHOOK_PAYLOAD_SHAPE, 0, $error);
        }

        if (!\is_array($event) || !str_starts_with(ltrim(Json::text($payload)), '{')) {
            throw new WebhookSignatureException(Messages::WEBHOOK_PAYLOAD_SHAPE);
        }

        return $event;
    }

    public static function header(mixed $headers, string $name): ?string
    {
        if ($headers instanceof MessageInterface) {
            return $headers->getHeader($name)[0] ?? null;
        }

        if (!is_iterable($headers)) {
            return null;
        }

        $wanted = strtolower($name);
        $server = 'HTTP_' . strtoupper(str_replace('-', '_', $name));

        foreach ($headers as $key => $value) {
            if (\is_string($key) && (strtolower($key) === $wanted || $key === $server)) {
                return self::first($value);
            }
        }

        return null;
    }

    private static function first(mixed $value): ?string
    {
        if (\is_array($value)) {
            $value = $value === [] ? null : reset($value);
        }

        return \is_string($value) ? $value : (\is_scalar($value) ? (string) $value : null);
    }

    private static function timestampOf(string $header): int|float|null
    {
        $values = self::valuesOf($header, Protocol::WEBHOOK_SIGNATURE['TIMESTAMP']);
        $last = end($values);

        return \is_string($last) ? self::number($last) : null;
    }

    private static function signaturesOf(string $header): array
    {
        return array_values(array_filter(
            self::valuesOf($header, Protocol::WEBHOOK_SIGNATURE['VERSION']),
            static fn(mixed $value): bool => $value !== '',
        ));
    }

    private static function valuesOf(string $header, string $wanted): array
    {
        $values = [];

        foreach (explode(Protocol::WEBHOOK_SIGNATURE['PAIR_SEPARATOR'], $header) as $pair) {
            $at = strpos($pair, Protocol::WEBHOOK_SIGNATURE['VALUE_SEPARATOR']);

            if ($at !== false && $at >= 1 && trim(substr($pair, 0, $at)) === $wanted) {
                $values[] = trim(substr($pair, $at + 1));
            }
        }

        return $values;
    }

    private static function number(string $value): int|float|null
    {
        if (preg_match(Defaults::INTEGER_TEXT, $value) === 1 && \strlen(ltrim($value, '+-')) <= 18) {
            return (int) $value;
        }

        if (!is_numeric($value)) {
            return null;
        }

        $number = (float) $value;

        return is_finite($number) ? Wire::number($number) : null;
    }

    private static function assertFresh(int|float $timestamp, int $tolerance): void
    {
        $drift = abs(time() - $timestamp);

        if ($tolerance > 0 && $drift > $tolerance) {
            throw new WebhookSignatureException(\sprintf(
                Messages::WEBHOOK_OUT_OF_TOLERANCE,
                \is_int($drift) ? (string) $drift : Wire::numberText($drift),
                (string) $tolerance,
            ));
        }
    }
}
