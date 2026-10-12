<?php

declare(strict_types=1);

namespace OpenEmail\Constants;

final class AutomationHoldReasons
{
    public const WINDOW = 'window';
    public const QUOTA = 'quota';
    public const LANE = 'lane';
    public const RETRY = 'retry';

    public static function values(): array
    {
        return [self::WINDOW, self::QUOTA, self::LANE, self::RETRY];
    }
}
