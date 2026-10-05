<?php

declare(strict_types=1);

namespace OpenEmail\Constants;

final class PeopleSorts
{
    public const RECENT = 'recent';
    public const NAME = 'name';
    public const THREADS = 'threads';

    public static function values(): array
    {
        return [self::RECENT, self::NAME, self::THREADS];
    }
}
