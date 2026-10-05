<?php

declare(strict_types=1);

namespace OpenEmail\Constants;

final class SubscriptionSorts
{
    public const RECENT = 'recent';
    public const MOST = 'most';
    public const UNREAD = 'unread';
    public const NAME = 'name';

    public static function values(): array
    {
        return [self::RECENT, self::MOST, self::UNREAD, self::NAME];
    }
}
