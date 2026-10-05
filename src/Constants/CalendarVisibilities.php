<?php

declare(strict_types=1);

namespace OpenEmail\Constants;

final class CalendarVisibilities
{
    public const PUBLIC = 'PUBLIC';
    public const PRIVATE = 'PRIVATE';
    public const CONFIDENTIAL = 'CONFIDENTIAL';

    public static function values(): array
    {
        return [self::PUBLIC, self::PRIVATE, self::CONFIDENTIAL];
    }
}
