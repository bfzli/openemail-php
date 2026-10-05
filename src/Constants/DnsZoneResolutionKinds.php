<?php

declare(strict_types=1);

namespace OpenEmail\Constants;

final class DnsZoneResolutionKinds
{
    public const RESOLVED = 'resolved';
    public const AMBIGUOUS = 'ambiguous';
    public const NONE = 'none';
    public const UNUSABLE = 'unusable';

    public static function values(): array
    {
        return [self::RESOLVED, self::AMBIGUOUS, self::NONE, self::UNUSABLE];
    }
}
