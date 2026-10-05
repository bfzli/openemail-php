<?php

declare(strict_types=1);

namespace OpenEmail\Constants;

final class EmailNotificationCategories
{
    public const ACCOUNT = 'account';
    public const BILLING = 'billing';
    public const ACTIVITY = 'activity';
    public const PRODUCT = 'product';

    public static function values(): array
    {
        return [self::ACCOUNT, self::BILLING, self::ACTIVITY, self::PRODUCT];
    }
}
