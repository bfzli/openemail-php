<?php

declare(strict_types=1);

namespace OpenEmail\Constants;

final class MailboxSignIns
{
    public const APP_PASSWORD = 'app-password';
    public const PASSWORD = 'password';
    public const MICROSOFT = 'microsoft';

    public static function values(): array
    {
        return [self::APP_PASSWORD, self::PASSWORD, self::MICROSOFT];
    }
}
