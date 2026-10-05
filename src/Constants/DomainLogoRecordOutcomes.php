<?php

declare(strict_types=1);

namespace OpenEmail\Constants;

final class DomainLogoRecordOutcomes
{
    public const PUBLISHED = 'published';
    public const MANUAL = 'manual';
    public const BLOCKED = 'blocked';
    public const BUSY = 'busy';
    public const FAILED = 'failed';

    public static function values(): array
    {
        return [self::PUBLISHED, self::MANUAL, self::BLOCKED, self::BUSY, self::FAILED];
    }
}
