<?php

declare(strict_types=1);

namespace OpenEmail\Constants;

final class AutomationExitReasons
{
    public const COMPLETED = 'completed';
    public const EXIT_STEP = 'exit_step';
    public const UNSUBSCRIBED = 'unsubscribed';
    public const SUPPRESSED = 'suppressed';
    public const LEFT_AUDIENCE = 'left_audience';
    public const REMOVED = 'removed';
    public const ARCHIVED = 'archived';
    public const FAILED = 'failed';

    public static function values(): array
    {
        return [
            self::COMPLETED,
            self::EXIT_STEP,
            self::UNSUBSCRIBED,
            self::SUPPRESSED,
            self::LEFT_AUDIENCE,
            self::REMOVED,
            self::ARCHIVED,
            self::FAILED,
        ];
    }
}
