<?php

declare(strict_types=1);

namespace OpenEmail\Constants;

final class AddressMxStatuses
{
    public const FOUND = 'found';
    public const IMPLICIT = 'implicit';
    public const MISSING = 'missing';
    public const NULL_MX = 'null_mx';
    public const DOMAIN_NOT_FOUND = 'domain_not_found';
    public const UNKNOWN = 'unknown';

    public static function values(): array
    {
        return [self::FOUND, self::IMPLICIT, self::MISSING, self::NULL_MX, self::DOMAIN_NOT_FOUND, self::UNKNOWN];
    }
}
