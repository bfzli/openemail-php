<?php

declare(strict_types=1);

namespace OpenEmail\Constants;

final class SettingsImageCompressions
{
    public const LOW = 'low';
    public const MEDIUM = 'medium';
    public const ORIGINAL = 'original';

    public static function values(): array
    {
        return [self::LOW, self::MEDIUM, self::ORIGINAL];
    }
}
