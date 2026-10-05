<?php

declare(strict_types=1);

namespace OpenEmail\Constants;

final class UsernameStatuses
{
    public const AVAILABLE = 'available';
    public const CURRENT = 'current';
    public const TAKEN = 'taken';
    public const RESERVED = 'reserved';
    public const INVALID = 'invalid';
    public const TOO_SHORT = 'too_short';
    public const TOO_LONG = 'too_long';

    public static function values(): array
    {
        return [
            self::AVAILABLE,
            self::CURRENT,
            self::TAKEN,
            self::RESERVED,
            self::INVALID,
            self::TOO_SHORT,
            self::TOO_LONG,
        ];
    }
}
