<?php

declare(strict_types=1);

namespace OpenEmail\Constants;

final class MailAppSecurities
{
    public const SSL_TLS = 'ssl-tls';
    public const STARTTLS = 'starttls';

    public static function values(): array
    {
        return [self::SSL_TLS, self::STARTTLS];
    }
}
