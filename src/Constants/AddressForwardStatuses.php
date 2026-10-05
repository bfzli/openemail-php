<?php

declare(strict_types=1);

namespace OpenEmail\Constants;

final class AddressForwardStatuses
{
    public const LIVE = 'live';
    public const PENDING = 'pending';
    public const REFUSED = 'refused';
    public const PAUSED = 'paused';

    public static function values(): array
    {
        return [self::LIVE, self::PENDING, self::REFUSED, self::PAUSED];
    }
}
