<?php

declare(strict_types=1);

namespace OpenEmail\Constants;

final class AutomationWaitModes
{
    public const DURATION = 'duration';
    public const UNTIL = 'until';
    public const EVENT = 'event';

    public static function values(): array
    {
        return [self::DURATION, self::UNTIL, self::EVENT];
    }
}
