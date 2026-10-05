<?php

declare(strict_types=1);

namespace OpenEmail\Constants;

final class RewriteTargets
{
    public const SUBJECT = 'subject';
    public const BODY = 'body';

    public static function values(): array
    {
        return [self::SUBJECT, self::BODY];
    }
}
