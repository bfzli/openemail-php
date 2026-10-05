<?php

declare(strict_types=1);

namespace OpenEmail\Constants;

final class DomainDmarcPolicies
{
    public const NONE = 'none';
    public const QUARANTINE = 'quarantine';
    public const REJECT = 'reject';

    public static function values(): array
    {
        return [self::NONE, self::QUARANTINE, self::REJECT];
    }
}
