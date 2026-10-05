<?php

declare(strict_types=1);

namespace OpenEmail\Constants;

final class FileSorts
{
    public const NEWEST = 'newest';
    public const OLDEST = 'oldest';
    public const LARGEST = 'largest';
    public const NAME = 'name';

    public static function values(): array
    {
        return [self::NEWEST, self::OLDEST, self::LARGEST, self::NAME];
    }
}
