<?php

declare(strict_types=1);

namespace OpenEmail\Constants;

final class ExportStatuses
{
    public const QUEUED = 'queued';
    public const RUNNING = 'running';
    public const READY = 'ready';
    public const FAILED = 'failed';
    public const EXPIRED = 'expired';

    public static function values(): array
    {
        return [self::QUEUED, self::RUNNING, self::READY, self::FAILED, self::EXPIRED];
    }
}
