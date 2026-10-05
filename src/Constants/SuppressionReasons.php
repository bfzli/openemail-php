<?php

declare(strict_types=1);

namespace OpenEmail\Constants;

final class SuppressionReasons
{
    public const BOUNCE = 'bounce';
    public const COMPLAINT = 'complaint';
    public const MANUAL = 'manual';

    public static function values(): array
    {
        return [self::BOUNCE, self::COMPLAINT, self::MANUAL];
    }
}
