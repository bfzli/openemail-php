<?php

declare(strict_types=1);

namespace OpenEmail\Constants;

final class AppHostStatuses
{
    public const NONE = 'none';
    public const PENDING = 'pending';
    public const ACTIVE = 'active';
    public const FAILED = 'failed';

    public static function values(): array
    {
        return [self::NONE, self::PENDING, self::ACTIVE, self::FAILED];
    }
}
