<?php

declare(strict_types=1);

namespace OpenEmail\Constants;

final class ImportPauseReasons
{
    public const THROTTLED = 'throttled';
    public const DAILY_LIMIT = 'daily-limit';
    public const UNREACHABLE = 'unreachable';
    public const SIGN_IN = 'sign-in';

    public static function values(): array
    {
        return [self::THROTTLED, self::DAILY_LIMIT, self::UNREACHABLE, self::SIGN_IN];
    }
}
