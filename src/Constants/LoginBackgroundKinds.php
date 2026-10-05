<?php

declare(strict_types=1);

namespace OpenEmail\Constants;

final class LoginBackgroundKinds
{
    public const PRESET = 'preset';
    public const COLOR = 'color';
    public const IMAGE = 'image';

    public static function values(): array
    {
        return [self::PRESET, self::COLOR, self::IMAGE];
    }
}
