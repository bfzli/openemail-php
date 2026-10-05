<?php

declare(strict_types=1);

namespace OpenEmail\Constants;

final class AudienceMemberStatuses
{
    public const SUBSCRIBED = 'subscribed';
    public const UNSUBSCRIBED = 'unsubscribed';

    public static function values(): array
    {
        return [self::SUBSCRIBED, self::UNSUBSCRIBED];
    }
}
