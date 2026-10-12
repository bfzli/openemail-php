<?php

declare(strict_types=1);

namespace OpenEmail\Constants;

final class AddressCheckResults
{
    public const VALID = 'valid';
    public const RISKY = 'risky';
    public const INVALID = 'invalid';
    public const UNKNOWN = 'unknown';

    public static function values(): array
    {
        return [self::VALID, self::RISKY, self::INVALID, self::UNKNOWN];
    }
}
