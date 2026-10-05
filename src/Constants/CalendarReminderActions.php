<?php

declare(strict_types=1);

namespace OpenEmail\Constants;

final class CalendarReminderActions
{
    public const DISPLAY = 'DISPLAY';
    public const EMAIL = 'EMAIL';
    public const AUDIO = 'AUDIO';

    public static function values(): array
    {
        return [self::DISPLAY, self::EMAIL, self::AUDIO];
    }
}
