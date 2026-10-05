<?php

declare(strict_types=1);

namespace OpenEmail\Constants;

final class BillingThemes
{
    public const LIGHT = 'light';
    public const DARK = 'dark';

    public static function values(): array
    {
        return [self::LIGHT, self::DARK];
    }
}
