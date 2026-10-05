<?php

declare(strict_types=1);

namespace OpenEmail\Constants;

final class BillingIntervals
{
    public const MONTH = 'month';
    public const YEAR = 'year';

    public static function values(): array
    {
        return [self::MONTH, self::YEAR];
    }
}
