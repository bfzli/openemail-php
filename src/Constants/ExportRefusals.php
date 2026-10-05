<?php

declare(strict_types=1);

namespace OpenEmail\Constants;

final class ExportRefusals
{
    public const NOT_PERMITTED = 'not-permitted';
    public const MAILBOX_LOGIN = 'mailbox-login';
    public const TWO_FACTOR_REQUIRED = 'two-factor-required';

    public static function values(): array
    {
        return [self::NOT_PERMITTED, self::MAILBOX_LOGIN, self::TWO_FACTOR_REQUIRED];
    }
}
