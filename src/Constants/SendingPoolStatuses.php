<?php

declare(strict_types=1);

namespace OpenEmail\Constants;

final class SendingPoolStatuses
{
    public const PENDING = 'pending';
    public const WARMING = 'warming';
    public const ACTIVE = 'active';
    public const RELEASING = 'releasing';
    public const RELEASED = 'released';
    public const FAILED = 'failed';

    public static function values(): array
    {
        return [self::PENDING, self::WARMING, self::ACTIVE, self::RELEASING, self::RELEASED, self::FAILED];
    }
}
