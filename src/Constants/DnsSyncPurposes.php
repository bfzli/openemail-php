<?php

declare(strict_types=1);

namespace OpenEmail\Constants;

final class DnsSyncPurposes
{
    public const DMARC = 'dmarc';
    public const TRACKING = 'tracking';
    public const STORAGE = 'storage';
    public const BIMI = 'bimi';
    public const APP_HOST = 'app-host';

    public static function values(): array
    {
        return [self::DMARC, self::TRACKING, self::STORAGE, self::BIMI, self::APP_HOST];
    }
}
