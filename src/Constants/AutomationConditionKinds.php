<?php

declare(strict_types=1);

namespace OpenEmail\Constants;

final class AutomationConditionKinds
{
    public const EMAIL_OPENED = 'email_opened';
    public const EMAIL_CLICKED = 'email_clicked';
    public const IN_AUDIENCE = 'in_audience';
    public const FIELD = 'field';
    public const EVENT = 'event';

    public static function values(): array
    {
        return [self::EMAIL_OPENED, self::EMAIL_CLICKED, self::IN_AUDIENCE, self::FIELD, self::EVENT];
    }
}
