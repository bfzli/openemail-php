<?php

declare(strict_types=1);

namespace OpenEmail\Constants;

final class BillingInvoiceStarts
{
    public const READY = 'ready';
    public const STARTED = 'started';
    public const REFUSED = 'refused';

    public static function values(): array
    {
        return [self::READY, self::STARTED, self::REFUSED];
    }
}
