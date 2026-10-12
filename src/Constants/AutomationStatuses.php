<?php

declare(strict_types=1);

namespace OpenEmail\Constants;

final class AutomationStatuses
{
    public const DRAFT = 'draft';
    public const LIVE = 'live';
    public const PAUSED = 'paused';
    public const ARCHIVED = 'archived';

    public static function values(): array
    {
        return [self::DRAFT, self::LIVE, self::PAUSED, self::ARCHIVED];
    }
}
