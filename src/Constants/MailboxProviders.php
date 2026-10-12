<?php

declare(strict_types=1);

namespace OpenEmail\Constants;

final class MailboxProviders
{
    public const GMAIL = 'gmail';
    public const OUTLOOK = 'outlook';
    public const YAHOO = 'yahoo';
    public const AOL = 'aol';
    public const ICLOUD = 'icloud';
    public const FASTMAIL = 'fastmail';
    public const ZOHO = 'zoho';
    public const OTHER = 'other';

    public static function values(): array
    {
        return [
            self::GMAIL,
            self::OUTLOOK,
            self::YAHOO,
            self::AOL,
            self::ICLOUD,
            self::FASTMAIL,
            self::ZOHO,
            self::OTHER,
        ];
    }
}
