<?php

declare(strict_types=1);

namespace OpenEmail\Constants;

final class KnowledgeConnectorStatuses
{
    public const QUEUED = 'queued';
    public const SYNCING = 'syncing';
    public const READY = 'ready';
    public const FAILED = 'failed';

    public static function values(): array
    {
        return [self::QUEUED, self::SYNCING, self::READY, self::FAILED];
    }
}
