<?php

declare(strict_types=1);

namespace OpenEmail\Constants;

final class AutomationPauseReasons
{
    public const MANUAL = 'manual';
    public const SENDER_REFUSED = 'sender_refused';
    public const KEY_REVOKED = 'key_revoked';
    public const TEMPLATE_UNAVAILABLE = 'template_unavailable';
    public const AUDIENCE_MISSING = 'audience_missing';
    public const PLAN_LIMIT = 'plan_limit';

    public static function values(): array
    {
        return [
            self::MANUAL,
            self::SENDER_REFUSED,
            self::KEY_REVOKED,
            self::TEMPLATE_UNAVAILABLE,
            self::AUDIENCE_MISSING,
            self::PLAN_LIMIT,
        ];
    }
}
