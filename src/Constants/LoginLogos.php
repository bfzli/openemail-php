<?php

declare(strict_types=1);

namespace OpenEmail\Constants;

final class LoginLogos
{
    public const DARK = 'dark';
    public const LIGHT = 'light';

    public static function values(): array
    {
        return [self::DARK, self::LIGHT];
    }
}
