<?php

declare(strict_types=1);

namespace OpenEmail\Constants;

final class FileVisibilities
{
    public const PUBLIC = 'public';
    public const PRIVATE = 'private';

    public static function values(): array
    {
        return [self::PUBLIC, self::PRIVATE];
    }
}
