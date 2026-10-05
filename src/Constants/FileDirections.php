<?php

declare(strict_types=1);

namespace OpenEmail\Constants;

final class FileDirections
{
    public const INBOUND = 'inbound';
    public const OUTBOUND = 'outbound';
    public const UPLOADED = 'uploaded';

    public static function values(): array
    {
        return [self::INBOUND, self::OUTBOUND, self::UPLOADED];
    }
}
