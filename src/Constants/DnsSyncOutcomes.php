<?php

declare(strict_types=1);

namespace OpenEmail\Constants;

final class DnsSyncOutcomes
{
    public const SYNCED = 'synced';
    public const REFUSED = 'refused';

    public static function values(): array
    {
        return [self::SYNCED, self::REFUSED];
    }
}
