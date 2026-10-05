<?php

declare(strict_types=1);

namespace OpenEmail\Constants;

final class AddressForwardConsentStatuses
{
    public const SENT = 'sent';
    public const ALREADY_CONFIRMED = 'already-confirmed';
    public const REVOKED = 'revoked';
    public const TOO_SOON = 'too-soon';
    public const SEND_FAILED = 'send-failed';

    public static function values(): array
    {
        return [self::SENT, self::ALREADY_CONFIRMED, self::REVOKED, self::TOO_SOON, self::SEND_FAILED];
    }
}
