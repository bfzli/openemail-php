<?php

declare(strict_types=1);

namespace OpenEmail\Constants;

final class KnowledgeSuggestionStatuses
{
    public const PENDING = 'pending';
    public const ACCEPTED = 'accepted';
    public const DISMISSED = 'dismissed';

    public static function values(): array
    {
        return [self::PENDING, self::ACCEPTED, self::DISMISSED];
    }
}
