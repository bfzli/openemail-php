<?php

declare(strict_types=1);

namespace OpenEmail\Constants;

final class BroadcastRecipientFilters
{
    public const PENDING = 'pending';
    public const SENT = 'sent';
    public const DELIVERED = 'delivered';
    public const OPENED = 'opened';
    public const NOT_OPENED = 'not_opened';
    public const CLICKED = 'clicked';
    public const BOUNCED = 'bounced';
    public const COMPLAINED = 'complained';
    public const FAILED = 'failed';
    public const UNSUBSCRIBED = 'unsubscribed';

    public static function values(): array
    {
        return [
            self::PENDING,
            self::SENT,
            self::DELIVERED,
            self::OPENED,
            self::NOT_OPENED,
            self::CLICKED,
            self::BOUNCED,
            self::COMPLAINED,
            self::FAILED,
            self::UNSUBSCRIBED,
        ];
    }
}
