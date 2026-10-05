<?php

declare(strict_types=1);

namespace OpenEmail\Internal;

use OpenEmail\Exception\InvalidArgumentException;

final class Wire
{
    private const RECIPIENT_FIELDS = ['to', 'cc', 'bcc'];

    private const SCHEDULED_AT = 'scheduledAt';

    private const ATTACHMENTS = 'attachments';

    private const FIXED_EXPONENT_MAX = 21;

    private const FIXED_EXPONENT_MIN = -6;

    public static function body(?array $body): array
    {
        return $body ?? [];
    }

    public static function email(array $body): array
    {
        $wire = self::recipients($body);

        if (!\array_key_exists(self::ATTACHMENTS, $wire)) {
            return $wire;
        }

        if ($wire[self::ATTACHMENTS] === null) {
            unset($wire[self::ATTACHMENTS]);

            return $wire;
        }

        $wire[self::ATTACHMENTS] = array_map(self::attachment(...), self::many($wire[self::ATTACHMENTS]));

        return $wire;
    }

    public static function recipients(array $body): array
    {
        foreach (self::RECIPIENT_FIELDS as $field) {
            if (isset($body[$field])) {
                $body[$field] = self::many($body[$field]);
            }
        }

        if (\array_key_exists(self::SCHEDULED_AT, $body)) {
            $body[self::SCHEDULED_AT] = self::instant($body[self::SCHEDULED_AT]);
        }

        return $body;
    }

    public static function many(mixed $value): array
    {
        if ($value instanceof \Traversable && self::isIterated($value)) {
            return iterator_to_array($value, false);
        }

        return \is_array($value) && self::isListLike($value) ? array_values($value) : [$value];
    }

    public static function attachment(mixed $value): array
    {
        if (!\is_array($value)) {
            throw new InvalidArgumentException(Messages::ATTACHMENT_SHAPE);
        }

        if (\array_key_exists('fileId', $value)) {
            return ['fileId' => $value['fileId']];
        }

        $wire = ['filename' => $value['filename'] ?? null, 'content' => self::content($value['content'] ?? null)];

        if (\array_key_exists('contentType', $value)) {
            $wire['contentType'] = $value['contentType'];
        }

        return $wire;
    }

    public static function content(mixed $value): string
    {
        if (\is_string($value)) {
            if (preg_match(Defaults::BASE64_TEXT, str_replace(Defaults::BASE64_WHITESPACE, '', $value)) === 1) {
                return $value;
            }

            throw new InvalidArgumentException(Messages::ATTACHMENT_CONTENT_SHAPE);
        }

        if (!RawBody::isReadable($value)) {
            throw new InvalidArgumentException(Messages::ATTACHMENT_CONTENT_SHAPE);
        }

        return base64_encode(RawBody::read($value));
    }

    public static function instant(mixed $value): mixed
    {
        if ($value instanceof \DateTimeInterface) {
            return \DateTimeImmutable::createFromInterface($value)
                ->setTimezone(new \DateTimeZone(Defaults::UTC))
                ->format(Defaults::ISO_INSTANT);
        }

        return $value;
    }

    public static function number(int|float $value): int|float
    {
        if (\is_int($value) || !is_finite($value)) {
            return $value;
        }

        if ($value === floor($value) && abs($value) <= 9007199254740991) {
            return (int) $value;
        }

        return $value;
    }

    public static function numberText(float $value): string
    {
        $number = self::number($value);

        if (\is_int($number)) {
            return (string) $number;
        }

        if (is_nan($number)) {
            return 'NaN';
        }

        if (is_infinite($number)) {
            return $number > 0 ? 'Infinity' : '-Infinity';
        }

        return self::jsNumber($number);
    }

    public static function scalar(mixed $value): mixed
    {
        $value = self::instant($value);

        if (\is_int($value) || \is_float($value)) {
            return self::number($value);
        }

        if ($value instanceof \BackedEnum) {
            return $value->value;
        }

        if ($value instanceof \UnitEnum) {
            return $value->name;
        }

        if ($value instanceof \Stringable) {
            return (string) $value;
        }

        return $value;
    }

    public static function jsonReady(#[\SensitiveParameter] mixed $value, string|int|null $key = null, string $parent = ''): mixed
    {
        return self::ready($value, $key, $parent, 0);
    }

    public static function encodeBody(#[\SensitiveParameter] array $body): string
    {
        $ready = self::jsonReady($body);

        return Json::encode($ready === [] ? new \stdClass() : $ready);
    }

    public static function withObjects(array $body, array $keys): array
    {
        foreach ($keys as $key) {
            if (\is_string($key) && \is_array($body[$key] ?? null)) {
                $body[$key] = (object) $body[$key];
            }
        }

        return $body;
    }

    private static function ready(#[\SensitiveParameter] mixed $value, string|int|null $key, string $parent, int $depth): mixed
    {
        if ($depth > Defaults::BODY_DEPTH) {
            throw new InvalidArgumentException(\sprintf(Messages::BODY_DEPTH, Defaults::BODY_DEPTH));
        }

        $scope = \is_string($key) ? $key : $parent;

        if ($value instanceof \JsonSerializable) {
            $serialized = $value->jsonSerialize();

            return $serialized === $value
                ? self::objectOf(get_object_vars($value), $scope, $depth)
                : self::ready($serialized, $key, $parent, $depth + 1);
        }

        if ($value instanceof \ArrayObject) {
            return self::objectOf($value->getArrayCopy(), $scope, $depth);
        }

        if ($value instanceof \stdClass) {
            return self::objectOf(get_object_vars($value), $scope, $depth);
        }

        if (\is_array($value)) {
            return self::arrayOf($value, self::isObjectKey($key, $parent), $scope, $depth);
        }

        if ($value instanceof \Traversable && self::isIterated($value)) {
            return self::arrayOf(self::entries($value), self::isObjectKey($key, $parent), $scope, $depth);
        }

        $scalar = self::scalar($value);

        if ($scalar === null || \is_bool($scalar) || \is_int($scalar) || \is_float($scalar) || \is_string($scalar)) {
            return $scalar;
        }

        if (\is_object($scalar) && !$scalar instanceof \Closure) {
            return self::objectOf(get_object_vars($scalar), $scope, $depth);
        }

        throw new InvalidArgumentException(\sprintf(Messages::BODY_VALUE_SHAPE, get_debug_type($value)));
    }

    private static function isObjectKey(string|int|null $key, string $parent): bool
    {
        return \is_string($key) && (isset(WireShapes::OBJECT_KEYS[$key]) || isset(WireShapes::OBJECT_PATHS[$parent . '.' . $key]));
    }

    private static function arrayOf(#[\SensitiveParameter] array $value, bool $isObject, string $scope, int $depth): array|\stdClass
    {
        if ($value === []) {
            return $isObject ? new \stdClass() : [];
        }

        if ($isObject) {
            return self::objectOf($value, $scope, $depth);
        }

        $listed = self::isListLike($value);
        $ready = [];

        foreach ($value as $name => $item) {
            $prepared = self::ready($item, $name, $scope, $depth + 1);

            if ($listed) {
                $ready[] = $prepared;
            } else {
                $ready[$name] = $prepared;
            }
        }

        return $ready;
    }

    private static function objectOf(#[\SensitiveParameter] array $properties, string $scope, int $depth): \stdClass
    {
        $ready = [];

        foreach ($properties as $name => $item) {
            $ready[$name] = self::ready($item, $name, $scope, $depth + 1);
        }

        return (object) $ready;
    }

    private static function isIterated(\Traversable $value): bool
    {
        return !$value instanceof \ArrayObject && !$value instanceof \Stringable;
    }

    private static function entries(#[\SensitiveParameter] \Traversable $value): array
    {
        $keyed = [];
        $listed = [];

        foreach ($value as $name => $item) {
            $listed[] = $item;

            if (\is_int($name) || \is_string($name)) {
                $keyed[$name] = $item;
            }
        }

        return \count($keyed) === \count($listed) && !self::isListLike($keyed) ? $keyed : $listed;
    }

    private static function isListLike(array $value): bool
    {
        if (array_is_list($value)) {
            return true;
        }

        foreach (array_keys($value) as $name) {
            if (!\is_int($name)) {
                return false;
            }
        }

        return true;
    }

    private static function jsNumber(float $value): string
    {
        if ($value === 0.0) {
            return '0';
        }

        $text = \sprintf('%.*H', -1, abs($value));
        $at = strpos($text, 'E');
        $mantissa = $at === false ? $text : substr($text, 0, $at);
        $exponent = $at === false ? 0 : (int) substr($text, $at + 1);
        $point = strpos($mantissa, '.');
        $whole = $point === false ? $mantissa : substr($mantissa, 0, $point);
        $raw = $whole . ($point === false ? '' : substr($mantissa, $point + 1));
        $significant = ltrim($raw, '0');
        $position = \strlen($whole) + $exponent - (\strlen($raw) - \strlen($significant));
        $digits = rtrim($significant, '0');
        $count = \strlen($digits);
        $sign = $value < 0 ? '-' : '';

        if ($count <= $position && $position <= self::FIXED_EXPONENT_MAX) {
            return $sign . $digits . str_repeat('0', $position - $count);
        }

        if ($position > 0 && $position <= self::FIXED_EXPONENT_MAX) {
            return $sign . substr($digits, 0, $position) . '.' . substr($digits, $position);
        }

        if ($position > self::FIXED_EXPONENT_MIN && $position <= 0) {
            return $sign . '0.' . str_repeat('0', -$position) . $digits;
        }

        $mantissaText = $count === 1 ? $digits : $digits[0] . '.' . substr($digits, 1);

        return $sign . $mantissaText . 'e' . ($position > 0 ? '+' : '-') . abs($position - 1);
    }
}
