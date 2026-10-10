<?php

declare(strict_types=1);

namespace OpenEmail\Constants;

final class CalendarSubscriptionStatuses
{
    public const ACTIVE = 'active';
    public const FAILING = 'failing';
    public const STOPPED = 'stopped';

    public static function values(): array
    {
        return [self::ACTIVE, self::FAILING, self::STOPPED];
    }
}
