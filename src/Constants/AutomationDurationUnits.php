<?php

declare(strict_types=1);

namespace OpenEmail\Constants;

final class AutomationDurationUnits
{
    public const MINUTES = 'minutes';
    public const HOURS = 'hours';
    public const DAYS = 'days';
    public const WEEKS = 'weeks';

    public static function values(): array
    {
        return [self::MINUTES, self::HOURS, self::DAYS, self::WEEKS];
    }
}
