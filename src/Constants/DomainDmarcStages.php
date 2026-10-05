<?php

declare(strict_types=1);

namespace OpenEmail\Constants;

final class DomainDmarcStages
{
    public const MISSING = 'missing';
    public const INVALID = 'invalid';
    public const MONITOR = 'monitor';
    public const QUARANTINE = 'quarantine';
    public const REJECT = 'reject';

    public static function values(): array
    {
        return [self::MISSING, self::INVALID, self::MONITOR, self::QUARANTINE, self::REJECT];
    }
}
