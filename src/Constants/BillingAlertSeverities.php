<?php

declare(strict_types=1);

namespace OpenEmail\Constants;

final class BillingAlertSeverities
{
    public const BLOCKED = 'blocked';
    public const WARNING = 'warning';
    public const INFO = 'info';

    public static function values(): array
    {
        return [self::BLOCKED, self::WARNING, self::INFO];
    }
}
