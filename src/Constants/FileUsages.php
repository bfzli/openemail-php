<?php

declare(strict_types=1);

namespace OpenEmail\Constants;

final class FileUsages
{
    public const RECEIVED = 'received';
    public const SENT = 'sent';
    public const LINKED = 'linked';
    public const SCHEDULED = 'scheduled';

    public static function values(): array
    {
        return [self::RECEIVED, self::SENT, self::LINKED, self::SCHEDULED];
    }
}
