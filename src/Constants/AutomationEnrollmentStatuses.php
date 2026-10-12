<?php

declare(strict_types=1);

namespace OpenEmail\Constants;

final class AutomationEnrollmentStatuses
{
    public const ACTIVE = 'active';
    public const COMPLETED = 'completed';
    public const EXITED = 'exited';

    public static function values(): array
    {
        return [self::ACTIVE, self::COMPLETED, self::EXITED];
    }
}
