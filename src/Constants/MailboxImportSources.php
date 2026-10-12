<?php

declare(strict_types=1);

namespace OpenEmail\Constants;

final class MailboxImportSources
{
    public const FILE = 'file';
    public const IMAP = 'imap';
    public const MICROSOFT = 'microsoft';

    public static function values(): array
    {
        return [self::FILE, self::IMAP, self::MICROSOFT];
    }
}
