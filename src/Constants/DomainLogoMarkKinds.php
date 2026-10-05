<?php

declare(strict_types=1);

namespace OpenEmail\Constants;

final class DomainLogoMarkKinds
{
    public const VERIFIED = 'verified';
    public const COMMON = 'common';
    public const UNKNOWN = 'unknown';

    public static function values(): array
    {
        return [self::VERIFIED, self::COMMON, self::UNKNOWN];
    }
}
