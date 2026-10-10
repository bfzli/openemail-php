<?php

declare(strict_types=1);

namespace OpenEmail\Constants;

final class MailAppProtocols
{
    public const IMAP = 'imap';
    public const SMTP = 'smtp';
    public const POP3 = 'pop3';
    public const CALDAV = 'caldav';
    public const CARDDAV = 'carddav';

    public static function values(): array
    {
        return [self::IMAP, self::SMTP, self::POP3, self::CALDAV, self::CARDDAV];
    }
}
