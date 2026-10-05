<?php

declare(strict_types=1);

namespace OpenEmail\Constants;

final class FormFonts
{
    public const SYSTEM = 'system';
    public const SANS = 'sans';
    public const SERIF = 'serif';
    public const MONO = 'mono';

    public static function values(): array
    {
        return [self::SYSTEM, self::SANS, self::SERIF, self::MONO];
    }
}
