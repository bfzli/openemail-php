<?php

declare(strict_types=1);

namespace OpenEmail\Constants;

final class ContactBlockLists
{
    public const BLOCKED_SENDERS = 'blockedSenders';
    public const BLOCKED_DOMAINS = 'blockedDomains';

    public static function values(): array
    {
        return [self::BLOCKED_SENDERS, self::BLOCKED_DOMAINS];
    }
}
