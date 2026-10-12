<?php

declare(strict_types=1);

namespace OpenEmail\Constants;

final class DedicatedIpStatuses
{
    public const AVAILABLE = 'available';
    public const WARMING = 'warming';
    public const READY = 'ready';
    public const RELEASED = 'released';

    public static function values(): array
    {
        return [self::AVAILABLE, self::WARMING, self::READY, self::RELEASED];
    }
}
