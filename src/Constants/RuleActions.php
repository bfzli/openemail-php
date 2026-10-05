<?php

declare(strict_types=1);

namespace OpenEmail\Constants;

final class RuleActions
{
    public const LABEL = 'label';
    public const REMOVE_LABEL = 'remove_label';
    public const ARCHIVE = 'archive';
    public const MARK_READ = 'mark_read';
    public const STAR = 'star';
    public const SPAM = 'spam';
    public const TRASH = 'trash';
    public const FORWARD = 'forward';
    public const REPLY = 'reply';
    public const BLOCK_SENDER = 'block_sender';
    public const REJECT = 'reject';

    public static function values(): array
    {
        return [
            self::LABEL,
            self::REMOVE_LABEL,
            self::ARCHIVE,
            self::MARK_READ,
            self::STAR,
            self::SPAM,
            self::TRASH,
            self::FORWARD,
            self::REPLY,
            self::BLOCK_SENDER,
            self::REJECT,
        ];
    }
}
