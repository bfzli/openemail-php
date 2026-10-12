<?php

declare(strict_types=1);

namespace OpenEmail\Constants;

final class AutomationEnrollmentSources
{
    public const TRIGGER = 'trigger';
    public const MANUAL = 'manual';
    public const API = 'api';

    public static function values(): array
    {
        return [self::TRIGGER, self::MANUAL, self::API];
    }
}
