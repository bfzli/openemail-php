<?php

declare(strict_types=1);

namespace OpenEmail\Constants;

final class ConnectedAppStatuses
{
    public const ACTIVE = 'active';
    public const EXPIRED = 'expired';
    public const NEEDS_APPROVAL = 'needs-approval';
    public const ACCESS_LOST = 'access-lost';

    public static function values(): array
    {
        return [self::ACTIVE, self::EXPIRED, self::NEEDS_APPROVAL, self::ACCESS_LOST];
    }
}
