<?php

declare(strict_types=1);

namespace OpenEmail\Constants;

final class DomainRecordStatuses
{
    public const FOUND = 'found';
    public const MISSING = 'missing';

    public static function values(): array
    {
        return [self::FOUND, self::MISSING];
    }
}
