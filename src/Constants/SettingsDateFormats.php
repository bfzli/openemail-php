<?php

declare(strict_types=1);

namespace OpenEmail\Constants;

final class SettingsDateFormats
{
    public const DMY = 'dmy';
    public const MDY = 'mdy';
    public const YMD = 'ymd';

    public static function values(): array
    {
        return [self::DMY, self::MDY, self::YMD];
    }
}
