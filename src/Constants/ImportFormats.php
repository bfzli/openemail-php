<?php

declare(strict_types=1);

namespace OpenEmail\Constants;

final class ImportFormats
{
    public const GMAIL = 'gmail';
    public const OPENEMAIL = 'openemail';
    public const THUNDERBIRD = 'thunderbird';
    public const PROTON = 'proton';
    public const MBOX = 'mbox';
    public const EML = 'eml';

    public static function values(): array
    {
        return [self::GMAIL, self::OPENEMAIL, self::THUNDERBIRD, self::PROTON, self::MBOX, self::EML];
    }
}
