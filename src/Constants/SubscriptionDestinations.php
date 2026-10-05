<?php

declare(strict_types=1);

namespace OpenEmail\Constants;

final class SubscriptionDestinations
{
    public const ARCHIVE = 'archive';
    public const BIN = 'bin';
    public const LABEL = 'label';

    public static function values(): array
    {
        return [self::ARCHIVE, self::BIN, self::LABEL];
    }
}
