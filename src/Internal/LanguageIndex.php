<?php

declare(strict_types=1);

namespace OpenEmail\Internal;

use OpenEmail\Constants\Languages;

final class LanguageIndex
{
    private static ?array $byCode = null;

    private static array $byLabel = [];

    private static array $byNative = [];

    private static array $aliases = [];

    public static function byCode(?string $code): ?array
    {
        if ($code === null || $code === '') {
            return null;
        }

        return self::entry(self::codes()[self::key($code)] ?? null);
    }

    public static function resolve(?string $input): ?array
    {
        if ($input === null || $input === '') {
            return null;
        }

        $wanted = self::key($input);

        if ($wanted === '') {
            return null;
        }

        $codes = self::codes();

        $aliased = self::$aliases[$wanted] ?? null;

        if (\is_string($aliased)) {
            return self::entry($codes[$aliased] ?? null);
        }

        $direct = self::entry($codes[$wanted] ?? self::$byLabel[$wanted] ?? self::$byNative[$wanted] ?? null);

        if ($direct !== null) {
            return $direct;
        }

        $base = preg_split(Defaults::LANGUAGE_SUBTAG_PATTERN, $wanted)[0] ?? '';

        if ($base === '' || $base === $wanted) {
            return null;
        }

        $alias = self::$aliases[$base] ?? null;

        return self::entry($codes[\is_string($alias) ? $alias : $base] ?? null);
    }

    private static function entry(mixed $value): ?array
    {
        return \is_array($value) ? $value : null;
    }

    public static function isRtl(?string $code): bool
    {
        return (self::resolve($code)['rtl'] ?? false) === true;
    }

    public static function key(string $value): string
    {
        $trimmed = trim($value);

        return \function_exists('mb_strtolower') ? mb_strtolower($trimmed, 'UTF-8') : strtolower($trimmed);
    }

    private static function codes(): array
    {
        if (self::$byCode !== null) {
            return self::$byCode;
        }

        $byCode = [];

        foreach (Languages::ALL as $language) {
            $byCode[self::key($language['code'])] = $language;
            self::$byLabel[self::key($language['label'])] = $language;
            self::$byNative[self::key($language['native'])] = $language;
        }

        foreach (Protocol::LANGUAGE_ALIASES as [$alias, $code]) {
            self::$aliases[self::key($alias)] = self::key($code);
        }

        return self::$byCode = $byCode;
    }
}
