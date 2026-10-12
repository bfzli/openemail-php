<?php

declare(strict_types=1);

namespace OpenEmail\Constants;

final class AddressConfidences
{
    public const HIGH = 'high';
    public const MEDIUM = 'medium';
    public const LOW = 'low';

    public static function values(): array
    {
        return [self::HIGH, self::MEDIUM, self::LOW];
    }
}
