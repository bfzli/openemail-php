<?php

declare(strict_types=1);

namespace OpenEmail\Constants;

final class FormSenderIssues
{
    public const MISSING = 'missing';
    public const NOT_SENDABLE = 'not_sendable';
    public const NOT_ALLOWED = 'not_allowed';

    public static function values(): array
    {
        return [self::MISSING, self::NOT_SENDABLE, self::NOT_ALLOWED];
    }
}
