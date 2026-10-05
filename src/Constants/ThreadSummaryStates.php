<?php

declare(strict_types=1);

namespace OpenEmail\Constants;

final class ThreadSummaryStates
{
    public const READY = 'ready';
    public const PENDING = 'pending';
    public const NONE = 'none';

    public static function values(): array
    {
        return [self::READY, self::PENDING, self::NONE];
    }
}
