<?php

declare(strict_types=1);

namespace OpenEmail\Constants;

final class KnowledgeStatuses
{
    public const QUEUED = 'queued';
    public const PROCESSING = 'processing';
    public const READY = 'ready';
    public const FAILED = 'failed';

    public static function values(): array
    {
        return [self::QUEUED, self::PROCESSING, self::READY, self::FAILED];
    }
}
