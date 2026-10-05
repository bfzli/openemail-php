<?php

declare(strict_types=1);

namespace OpenEmail\Constants;

final class DnsProvisionStates
{
    public const UNMANAGED = 'unmanaged';
    public const READY = 'ready';
    public const AWAITING_SYNC = 'awaiting-sync';
    public const AWAITING_SIGNING = 'awaiting-signing';
    public const CONFLICT = 'conflict';
    public const BLOCKED = 'blocked';

    public static function values(): array
    {
        return [
            self::UNMANAGED,
            self::READY,
            self::AWAITING_SYNC,
            self::AWAITING_SIGNING,
            self::CONFLICT,
            self::BLOCKED,
        ];
    }
}
