<?php

declare(strict_types=1);

namespace OpenEmail\Constants;

final class ContactThreadSorts
{
    public const NEWEST = 'newest';
    public const OLDEST = 'oldest';
    public const SENDER = 'sender';
    public const SUBJECT = 'subject';

    public static function values(): array
    {
        return [self::NEWEST, self::OLDEST, self::SENDER, self::SUBJECT];
    }
}
