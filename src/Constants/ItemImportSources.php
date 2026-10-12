<?php

declare(strict_types=1);

namespace OpenEmail\Constants;

final class ItemImportSources
{
    public const FILE = 'file';
    public const TAKEOUT = 'takeout';
    public const CALDAV = 'caldav';
    public const CARDDAV = 'carddav';
    public const MICROSOFT = 'microsoft';

    public static function values(): array
    {
        return [self::FILE, self::TAKEOUT, self::CALDAV, self::CARDDAV, self::MICROSOFT];
    }
}
