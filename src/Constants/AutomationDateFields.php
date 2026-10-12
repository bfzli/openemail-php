<?php

declare(strict_types=1);

namespace OpenEmail\Constants;

final class AutomationDateFields
{
    public const BIRTHDAY = 'birthday';
    public const JOINED = 'joined';

    public static function values(): array
    {
        return [self::BIRTHDAY, self::JOINED];
    }
}
