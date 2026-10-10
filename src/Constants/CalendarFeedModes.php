<?php

declare(strict_types=1);

namespace OpenEmail\Constants;

final class CalendarFeedModes
{
    public const FULL = 'full';
    public const BUSY = 'busy';

    public static function values(): array
    {
        return [self::FULL, self::BUSY];
    }
}
