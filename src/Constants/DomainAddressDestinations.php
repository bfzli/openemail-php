<?php

declare(strict_types=1);

namespace OpenEmail\Constants;

final class DomainAddressDestinations
{
    public const MAILBOX = 'mailbox';
    public const FORWARD = 'forward';

    public static function values(): array
    {
        return [self::MAILBOX, self::FORWARD];
    }
}
