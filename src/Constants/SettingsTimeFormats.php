<?php

declare(strict_types=1);

namespace OpenEmail\Constants;

final class SettingsTimeFormats
{
    public const H12 = '12h';
    public const H24 = '24h';

    public static function values(): array
    {
        return [self::H12, self::H24];
    }
}
