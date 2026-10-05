<?php

declare(strict_types=1);

namespace OpenEmail\Constants;

final class FormRadii
{
    public const NONE = 'none';
    public const SM = 'sm';
    public const MD = 'md';
    public const LG = 'lg';
    public const FULL = 'full';

    public static function values(): array
    {
        return [self::NONE, self::SM, self::MD, self::LG, self::FULL];
    }
}
