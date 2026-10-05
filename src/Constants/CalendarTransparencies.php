<?php

declare(strict_types=1);

namespace OpenEmail\Constants;

final class CalendarTransparencies
{
    public const OPAQUE = 'OPAQUE';
    public const TRANSPARENT = 'TRANSPARENT';

    public static function values(): array
    {
        return [self::OPAQUE, self::TRANSPARENT];
    }
}
