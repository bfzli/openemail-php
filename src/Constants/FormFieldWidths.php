<?php

declare(strict_types=1);

namespace OpenEmail\Constants;

final class FormFieldWidths
{
    public const FULL = 'full';
    public const HALF = 'half';

    public static function values(): array
    {
        return [self::FULL, self::HALF];
    }
}
