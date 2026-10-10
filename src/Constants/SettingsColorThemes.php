<?php

declare(strict_types=1);

namespace OpenEmail\Constants;

final class SettingsColorThemes
{
    public const LIGHT = 'light';
    public const DARK = 'dark';
    public const SYSTEM = 'system';

    public static function values(): array
    {
        return [self::LIGHT, self::DARK, self::SYSTEM];
    }
}
