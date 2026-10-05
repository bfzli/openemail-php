<?php

declare(strict_types=1);

namespace OpenEmail\Constants;

final class ChatSorts
{
    public const NEWEST = 'newest';
    public const OLDEST = 'oldest';
    public const TITLE = 'title';

    public static function values(): array
    {
        return [self::NEWEST, self::OLDEST, self::TITLE];
    }
}
