<?php

declare(strict_types=1);

namespace OpenEmail\Constants;

final class DeliverabilityCheckStatuses
{
    public const PASS = 'pass';
    public const WARN = 'warn';
    public const FAIL = 'fail';
    public const ABSENT = 'absent';

    public static function values(): array
    {
        return [self::PASS, self::WARN, self::FAIL, self::ABSENT];
    }
}
