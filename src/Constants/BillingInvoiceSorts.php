<?php

declare(strict_types=1);

namespace OpenEmail\Constants;

final class BillingInvoiceSorts
{
    public const NEWEST = 'newest';
    public const OLDEST = 'oldest';

    public static function values(): array
    {
        return [self::NEWEST, self::OLDEST];
    }
}
