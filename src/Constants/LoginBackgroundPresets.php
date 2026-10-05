<?php

declare(strict_types=1);

namespace OpenEmail\Constants;

final class LoginBackgroundPresets
{
    public const DUSK = 'dusk';
    public const MIST = 'mist';
    public const SAND = 'sand';
    public const NIGHT = 'night';

    public static function values(): array
    {
        return [self::DUSK, self::MIST, self::SAND, self::NIGHT];
    }
}
