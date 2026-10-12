<?php

declare(strict_types=1);

namespace OpenEmail\Constants;

final class SendStreams
{
    public const TRANSACTIONAL = 'transactional';
    public const BROADCAST = 'broadcast';

    public static function values(): array
    {
        return [self::TRANSACTIONAL, self::BROADCAST];
    }
}
