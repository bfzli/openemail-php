<?php

declare(strict_types=1);

namespace OpenEmail\Constants;

final class MailboxFolderRoles
{
    public const INBOX = 'inbox';
    public const SENT = 'sent';
    public const DRAFTS = 'drafts';
    public const SPAM = 'spam';
    public const TRASH = 'trash';
    public const ARCHIVE = 'archive';
    public const ALL = 'all';
    public const FLAGGED = 'flagged';
    public const IMPORTANT = 'important';
    public const FOLDER = 'folder';

    public static function values(): array
    {
        return [
            self::INBOX,
            self::SENT,
            self::DRAFTS,
            self::SPAM,
            self::TRASH,
            self::ARCHIVE,
            self::ALL,
            self::FLAGGED,
            self::IMPORTANT,
            self::FOLDER,
        ];
    }
}
