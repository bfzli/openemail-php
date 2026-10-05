<?php

declare(strict_types=1);

namespace OpenEmail\Constants;

final class SubscriptionStatuses
{
    public const ACTIVE = 'active';
    public const UNSUBSCRIBED = 'unsubscribed';

    public static function values(): array
    {
        return [self::ACTIVE, self::UNSUBSCRIBED];
    }
}
