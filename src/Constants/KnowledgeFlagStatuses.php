<?php

declare(strict_types=1);

namespace OpenEmail\Constants;

final class KnowledgeFlagStatuses
{
    public const OPEN = 'open';
    public const DISMISSED = 'dismissed';

    public static function values(): array
    {
        return [self::OPEN, self::DISMISSED];
    }
}
