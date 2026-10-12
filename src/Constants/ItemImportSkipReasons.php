<?php

declare(strict_types=1);

namespace OpenEmail\Constants;

final class ItemImportSkipReasons
{
    public const UID_TAKEN = 'uid-taken';
    public const NO_START = 'no-start';
    public const DUPLICATE = 'duplicate';
    public const LIMIT = 'limit';
    public const INVALID = 'invalid';
    public const EMPTY = 'empty';

    public static function values(): array
    {
        return [self::UID_TAKEN, self::NO_START, self::DUPLICATE, self::LIMIT, self::INVALID, self::EMPTY];
    }
}
