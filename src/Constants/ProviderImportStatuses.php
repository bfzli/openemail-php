<?php

declare(strict_types=1);

namespace OpenEmail\Constants;

final class ProviderImportStatuses
{
    public const QUEUED = 'queued';
    public const RUNNING = 'running';
    public const COMPLETED = 'completed';
    public const FAILED = 'failed';
    public const CANCELLED = 'cancelled';

    public static function values(): array
    {
        return [self::QUEUED, self::RUNNING, self::COMPLETED, self::FAILED, self::CANCELLED];
    }
}
