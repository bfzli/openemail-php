<?php

declare(strict_types=1);

namespace OpenEmail\Constants;

final class ExportPhases
{
    public const PLANNING = 'planning';
    public const MAIL = 'mail';
    public const RECORDS = 'records';
    public const FINISHING = 'finishing';

    public static function values(): array
    {
        return [self::PLANNING, self::MAIL, self::RECORDS, self::FINISHING];
    }
}
