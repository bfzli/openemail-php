<?php

declare(strict_types=1);

namespace OpenEmail\Constants;

final class BillingAlertTargets
{
    public const BILLING = 'billing';
    public const USAGE = 'usage';

    public static function values(): array
    {
        return [self::BILLING, self::USAGE];
    }
}
