<?php

declare(strict_types=1);

namespace OpenEmail\Constants;

final class ItemImportKinds
{
    public const CALENDAR = 'calendar';
    public const CONTACTS = 'contacts';

    public static function values(): array
    {
        return [self::CALENDAR, self::CONTACTS];
    }
}
