<?php

declare(strict_types=1);

namespace OpenEmail\Constants;

final class FormWidths
{
    public const NARROW = 'narrow';
    public const NORMAL = 'normal';
    public const WIDE = 'wide';

    public static function values(): array
    {
        return [self::NARROW, self::NORMAL, self::WIDE];
    }
}
