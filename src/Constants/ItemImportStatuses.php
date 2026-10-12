<?php

declare(strict_types=1);

namespace OpenEmail\Constants;

final class ItemImportStatuses
{
    public const UPLOADING = 'uploading';
    public const QUEUED = 'queued';
    public const RUNNING = 'running';
    public const COMPLETED = 'completed';
    public const FAILED = 'failed';
    public const CANCELLED = 'cancelled';
    public const UNDONE = 'undone';

    public static function values(): array
    {
        return [
            self::UPLOADING,
            self::QUEUED,
            self::RUNNING,
            self::COMPLETED,
            self::FAILED,
            self::CANCELLED,
            self::UNDONE,
        ];
    }
}
