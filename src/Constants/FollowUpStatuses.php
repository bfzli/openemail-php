<?php

declare(strict_types=1);

namespace OpenEmail\Constants;

final class FollowUpStatuses
{
    public const WAITING = 'waiting';
    public const ANSWERED = 'answered';
    public const DUE = 'due';
    public const CANCELLED = 'cancelled';

    public static function values(): array
    {
        return [self::WAITING, self::ANSWERED, self::DUE, self::CANCELLED];
    }
}
