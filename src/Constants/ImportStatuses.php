<?php

declare(strict_types=1);

namespace OpenEmail\Constants;

final class ImportStatuses
{
    public const UPLOADING = 'uploading';
    public const QUEUED = 'queued';
    public const RUNNING = 'running';
    public const PARKED = 'parked';
    public const NEEDS_PASSWORD = 'needs-password';
    public const COMPLETED = 'completed';
    public const FAILED = 'failed';
    public const CANCELLED = 'cancelled';

    public static function values(): array
    {
        return [
            self::UPLOADING,
            self::QUEUED,
            self::RUNNING,
            self::PARKED,
            self::NEEDS_PASSWORD,
            self::COMPLETED,
            self::FAILED,
            self::CANCELLED,
        ];
    }
}
