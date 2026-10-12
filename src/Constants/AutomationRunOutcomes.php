<?php

declare(strict_types=1);

namespace OpenEmail\Constants;

final class AutomationRunOutcomes
{
    public const SENT = 'sent';
    public const SKIPPED = 'skipped';
    public const WAITED = 'waited';
    public const YES = 'yes';
    public const NO = 'no';
    public const ADDED = 'added';
    public const REMOVED = 'removed';
    public const UPDATED = 'updated';
    public const CALLED = 'called';
    public const EXITED = 'exited';
    public const FAILED = 'failed';

    public static function values(): array
    {
        return [
            self::SENT,
            self::SKIPPED,
            self::WAITED,
            self::YES,
            self::NO,
            self::ADDED,
            self::REMOVED,
            self::UPDATED,
            self::CALLED,
            self::EXITED,
            self::FAILED,
        ];
    }
}
