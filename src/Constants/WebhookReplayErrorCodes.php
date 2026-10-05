<?php

declare(strict_types=1);

namespace OpenEmail\Constants;

final class WebhookReplayErrorCodes
{
    public const RESOURCE_NOT_FOUND = 'resource_not_found';
    public const WEBHOOK_DISABLED = 'webhook_disabled';
    public const EVENT_NOT_SUBSCRIBED = 'event_not_subscribed';
    public const EVENT_OUT_OF_SCOPE = 'event_out_of_scope';
    public const DELIVERY_NOT_REPLAYABLE = 'delivery_not_replayable';
    public const RETRY_IN_PROGRESS = 'retry_in_progress';
    public const REPLAY_IN_PROGRESS = 'replay_in_progress';

    public static function values(): array
    {
        return [
            self::RESOURCE_NOT_FOUND,
            self::WEBHOOK_DISABLED,
            self::EVENT_NOT_SUBSCRIBED,
            self::EVENT_OUT_OF_SCOPE,
            self::DELIVERY_NOT_REPLAYABLE,
            self::RETRY_IN_PROGRESS,
            self::REPLAY_IN_PROGRESS,
        ];
    }
}
