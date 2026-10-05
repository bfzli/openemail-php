<?php

declare(strict_types=1);

namespace OpenEmail\Constants;

final class BimiStatuses
{
    public const PRESENT = 'present';
    public const PARTIAL = 'partial';
    public const ABSENT = 'absent';
    public const INVALID = 'invalid';

    public static function values(): array
    {
        return [self::PRESENT, self::PARTIAL, self::ABSENT, self::INVALID];
    }
}
