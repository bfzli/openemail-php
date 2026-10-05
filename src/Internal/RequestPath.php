<?php

declare(strict_types=1);

namespace OpenEmail\Internal;

use OpenEmail\Exception\InvalidArgumentException;

final class RequestPath
{
    public static function fill(string $pattern, array $params): string
    {
        preg_match_all(Defaults::PATH_PARAM, $pattern, $matches);

        foreach ($matches[1] as $name) {
            self::assertSegment($params[$name] ?? null);
        }

        $optional = (string) preg_replace_callback(
            Defaults::OPTIONAL_PATH_PARAM,
            static fn(array $match): string => self::optionalSegment($params[$match[1]] ?? null),
            $pattern,
        );

        return (string) preg_replace_callback(
            Defaults::PATH_PARAM,
            static fn(array $match): string => self::encode($params[$match[1]] ?? null),
            $optional,
        );
    }

    public static function encode(mixed $value): string
    {
        $text = self::segmentText($value) ?? '';

        return strtr(rawurlencode($text), ['%21' => '!', '%2A' => '*', '%27' => '\'', '%28' => '(', '%29' => ')']);
    }

    private static function assertSegment(mixed $value): void
    {
        $text = self::segmentText($value);

        if ($text === null || $text === '') {
            throw new InvalidArgumentException(Messages::EMPTY_SEGMENT);
        }

        if (preg_match(Defaults::DOT_SEGMENT, $text) === 1) {
            throw new InvalidArgumentException(Endpoint::quoted($text) . ' ' . Messages::DOT_SEGMENT);
        }
    }

    private static function segmentText(mixed $value): ?string
    {
        $scalar = Wire::scalar($value);

        if (\is_int($scalar)) {
            return (string) $scalar;
        }

        if (\is_float($scalar)) {
            return Wire::numberText($scalar);
        }

        if (!\is_string($scalar)) {
            return null;
        }

        if (preg_match('//u', $scalar) !== 1) {
            throw new InvalidArgumentException(Messages::SEGMENT_ENCODING);
        }

        return $scalar;
    }

    private static function optionalSegment(mixed $value): string
    {
        return $value === null || $value === '' ? '' : '/' . self::encode($value);
    }
}
