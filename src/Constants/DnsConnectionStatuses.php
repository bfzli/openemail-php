<?php

declare(strict_types=1);

namespace OpenEmail\Constants;

final class DnsConnectionStatuses
{
    public const ACTIVE = 'active';
    public const NEEDS_REAUTH = 'needs-reauth';
    public const REVOKED = 'revoked';
    public const ERROR = 'error';

    public static function values(): array
    {
        return [self::ACTIVE, self::NEEDS_REAUTH, self::REVOKED, self::ERROR];
    }
}
