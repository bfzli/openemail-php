<?php

declare(strict_types=1);

namespace OpenEmail\Constants;

final class SettingsWeekStarts
{
    public const MONDAY = 'monday';
    public const SUNDAY = 'sunday';

    public static function values(): array
    {
        return [self::MONDAY, self::SUNDAY];
    }
}
