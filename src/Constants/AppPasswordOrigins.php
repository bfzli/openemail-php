<?php

declare(strict_types=1);

namespace OpenEmail\Constants;

final class AppPasswordOrigins
{
    public const SETTINGS = 'settings';
    public const SETUP_LINK = 'setup-link';
    public const API = 'api';

    public static function values(): array
    {
        return [self::SETTINGS, self::SETUP_LINK, self::API];
    }
}
