<?php

declare(strict_types=1);

namespace OpenEmail\Constants;

final class StepUpErrorCodes
{
    public const STEP_UP_REQUIRED = 'step_up_required';
    public const STEP_UP_NOT_APPLICABLE = 'step_up_not_applicable';
    public const STEP_UP_THROTTLED = 'step_up_throttled';
    public const STEP_UP_UNDELIVERABLE = 'step_up_undeliverable';
    public const STEP_UP_CODE_INVALID = 'step_up_code_invalid';
    public const STEP_UP_CODE_EXPIRED = 'step_up_code_expired';
    public const STEP_UP_LOCKED = 'step_up_locked';

    public static function values(): array
    {
        return [
            self::STEP_UP_REQUIRED,
            self::STEP_UP_NOT_APPLICABLE,
            self::STEP_UP_THROTTLED,
            self::STEP_UP_UNDELIVERABLE,
            self::STEP_UP_CODE_INVALID,
            self::STEP_UP_CODE_EXPIRED,
            self::STEP_UP_LOCKED,
        ];
    }
}
