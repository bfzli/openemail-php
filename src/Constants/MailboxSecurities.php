<?php

declare(strict_types=1);

namespace OpenEmail\Constants;

final class MailboxSecurities
{
    public const TLS = 'tls';
    public const STARTTLS = 'starttls';

    public static function values(): array
    {
        return [self::TLS, self::STARTTLS];
    }
}
