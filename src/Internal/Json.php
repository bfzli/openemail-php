<?php

declare(strict_types=1);

namespace OpenEmail\Internal;

use OpenEmail\Exception\InvalidArgumentException;

final class Json
{
    private const ENCODE_FLAGS = JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_LINE_TERMINATORS | JSON_THROW_ON_ERROR;

    private const DECODE_FLAGS = JSON_INVALID_UTF8_SUBSTITUTE | JSON_THROW_ON_ERROR;

    private const NUMBER_MARK = "\u{E000}";

    public static function encode(#[\SensitiveParameter] mixed $value): string
    {
        $numbers = [];
        $marked = self::markNumbers($value, $numbers, self::NUMBER_MARK . bin2hex(random_bytes(8)) . ':', 0);

        try {
            $json = json_encode($marked, self::ENCODE_FLAGS, Defaults::BODY_DEPTH);
        } catch (\JsonException $error) {
            throw new InvalidArgumentException(\sprintf(Messages::JSON_ENCODE, $error->getMessage()), 0, $error);
        }

        return $numbers === [] ? $json : strtr($json, $numbers);
    }

    public static function decode(string $text): mixed
    {
        try {
            return json_decode($text, true, Defaults::JSON_DEPTH, self::DECODE_FLAGS);
        } catch (\JsonException $error) {
            if ($error->getCode() !== JSON_ERROR_UTF16) {
                throw $error;
            }

            $repaired = self::withoutLoneSurrogates($text);

            if ($repaired === $text) {
                throw $error;
            }

            return json_decode($repaired, true, Defaults::JSON_DEPTH, self::DECODE_FLAGS);
        }
    }

    public static function tryDecode(string $text): mixed
    {
        if ($text === '') {
            return null;
        }

        try {
            return self::decode($text);
        } catch (\JsonException) {
            return null;
        }
    }

    public static function text(string $bytes): string
    {
        $text = self::validUtf8($bytes);

        return str_starts_with($text, Defaults::BYTE_ORDER_MARK) ? substr($text, \strlen(Defaults::BYTE_ORDER_MARK)) : $text;
    }

    public static function validUtf8(string $bytes): string
    {
        if (preg_match('//u', $bytes) === 1) {
            return $bytes;
        }

        $encoded = json_encode($bytes, JSON_INVALID_UTF8_SUBSTITUTE | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_LINE_TERMINATORS);
        $decoded = \is_string($encoded) ? json_decode($encoded, true) : null;

        return \is_string($decoded) ? $decoded : '';
    }

    private static function markNumbers(#[\SensitiveParameter] mixed $value, array &$numbers, string $prefix, int $depth): mixed
    {
        if (\is_float($value) && is_finite($value)) {
            $mark = $prefix . \count($numbers) . self::NUMBER_MARK;
            $numbers['"' . $mark . '"'] = Wire::numberText($value);

            return $mark;
        }

        if ((!\is_array($value) && !$value instanceof \stdClass) || $depth > Defaults::BODY_DEPTH) {
            return $value;
        }

        $marked = [];

        foreach (\is_array($value) ? $value : get_object_vars($value) as $name => $item) {
            $marked[$name] = self::markNumbers($item, $numbers, $prefix, $depth + 1);
        }

        return \is_array($value) ? $marked : (object) $marked;
    }

    public static function withoutLoneSurrogates(string $text): string
    {
        return (string) preg_replace_callback(Defaults::LONE_SURROGATE_ESCAPE, static function (array $match): string {
            $high = $match[1] ?? '';
            $pair = $match[2] ?? '';
            $loneLow = $match[4] ?? '';

            if ($high === '' && $loneLow === '') {
                return $match[0];
            }

            if ($high !== '' && $pair !== '') {
                return $match[0];
            }

            return Defaults::REPLACEMENT_ESCAPE;
        }, $text);
    }
}
