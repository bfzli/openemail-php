<?php

declare(strict_types=1);

namespace OpenEmail\Constants;

final class CalendarSubscriptionErrors
{
    public const UNREACHABLE = 'unreachable';
    public const TOO_LARGE = 'too-large';
    public const NOT_A_CALENDAR = 'not-a-calendar';
    public const TOO_MANY_EVENTS = 'too-many-events';

    public static function values(): array
    {
        return [self::UNREACHABLE, self::TOO_LARGE, self::NOT_A_CALENDAR, self::TOO_MANY_EVENTS];
    }
}
