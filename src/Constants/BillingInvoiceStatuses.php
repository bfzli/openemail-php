<?php

declare(strict_types=1);

namespace OpenEmail\Constants;

final class BillingInvoiceStatuses
{
    public const DRAFT = 'draft';
    public const PENDING = 'pending';
    public const PAID = 'paid';
    public const REFUNDED = 'refunded';
    public const PARTIALLY_REFUNDED = 'partially_refunded';
    public const VOID = 'void';

    public static function values(): array
    {
        return [self::DRAFT, self::PENDING, self::PAID, self::REFUNDED, self::PARTIALLY_REFUNDED, self::VOID];
    }
}
