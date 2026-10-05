<?php

declare(strict_types=1);

namespace OpenEmail\Constants;

final class PaidPlanIds
{
    public const STARTER = 'starter';
    public const BUSINESS = 'business';
    public const ENTERPRISE = 'enterprise';

    public static function values(): array
    {
        return [self::STARTER, self::BUSINESS, self::ENTERPRISE];
    }
}
