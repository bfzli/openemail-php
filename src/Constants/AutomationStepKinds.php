<?php

declare(strict_types=1);

namespace OpenEmail\Constants;

final class AutomationStepKinds
{
    public const SEND_EMAIL = 'send_email';
    public const WAIT = 'wait';
    public const BRANCH = 'branch';
    public const ADD_TO_AUDIENCE = 'add_to_audience';
    public const REMOVE_FROM_AUDIENCE = 'remove_from_audience';
    public const UPDATE_FIELD = 'update_field';
    public const WEBHOOK = 'webhook';
    public const EXIT = 'exit';

    public static function values(): array
    {
        return [
            self::SEND_EMAIL,
            self::WAIT,
            self::BRANCH,
            self::ADD_TO_AUDIENCE,
            self::REMOVE_FROM_AUDIENCE,
            self::UPDATE_FIELD,
            self::WEBHOOK,
            self::EXIT,
        ];
    }
}
