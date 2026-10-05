<?php

declare(strict_types=1);

namespace OpenEmail\Constants;

final class BroadcastStatuses
{
    public const SCHEDULED = 'scheduled';
    public const QUEUED = 'queued';
    public const SENDING = 'sending';
    public const SENT = 'sent';
    public const CANCELLED = 'cancelled';
    public const FAILED = 'failed';

    public static function values(): array
    {
        return [self::SCHEDULED, self::QUEUED, self::SENDING, self::SENT, self::CANCELLED, self::FAILED];
    }
}
