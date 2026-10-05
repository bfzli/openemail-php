<?php

declare(strict_types=1);

namespace OpenEmail\Constants;

final class CalendarResponses
{
    public const ACCEPTED = 'ACCEPTED';
    public const DECLINED = 'DECLINED';
    public const TENTATIVE = 'TENTATIVE';

    public static function values(): array
    {
        return [self::ACCEPTED, self::DECLINED, self::TENTATIVE];
    }
}
