<?php

declare(strict_types=1);

namespace OpenEmail\Constants;

final class AutomationTriggerKinds
{
    public const AUDIENCE_JOINED = 'audience_joined';
    public const FORM_SUBMITTED = 'form_submitted';
    public const EVENT = 'event';
    public const DATE = 'date';
    public const MANUAL = 'manual';

    public static function values(): array
    {
        return [self::AUDIENCE_JOINED, self::FORM_SUBMITTED, self::EVENT, self::DATE, self::MANUAL];
    }
}
