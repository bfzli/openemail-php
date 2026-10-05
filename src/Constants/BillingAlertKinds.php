<?php

declare(strict_types=1);

namespace OpenEmail\Constants;

final class BillingAlertKinds
{
    public const PAYMENT_FAILED = 'payment_failed';
    public const PAY_AS_YOU_GO_PAUSED = 'pay_as_you_go_paused';
    public const SENDING_PAUSED = 'sending_paused';
    public const SPEND_LIMIT_REACHED = 'spend_limit_reached';
    public const AI_USED_UP = 'ai_used_up';
    public const PLAN_ENDING = 'plan_ending';
    public const SENDS_NEARLY_USED = 'sends_nearly_used';
    public const SPEND_NEARLY_REACHED = 'spend_nearly_reached';
    public const UPCOMING_CHARGE = 'upcoming_charge';

    public static function values(): array
    {
        return [
            self::PAYMENT_FAILED,
            self::PAY_AS_YOU_GO_PAUSED,
            self::SENDING_PAUSED,
            self::SPEND_LIMIT_REACHED,
            self::AI_USED_UP,
            self::PLAN_ENDING,
            self::SENDS_NEARLY_USED,
            self::SPEND_NEARLY_REACHED,
            self::UPCOMING_CHARGE,
        ];
    }
}
