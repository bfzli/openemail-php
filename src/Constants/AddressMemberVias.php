<?php

declare(strict_types=1);

namespace OpenEmail\Constants;

final class AddressMemberVias
{
    public const OWNER = 'owner';
    public const EVERY_ADDRESS = 'every-address';
    public const WHOLE_DOMAIN = 'whole-domain';
    public const DIRECT = 'direct';

    public static function values(): array
    {
        return [self::OWNER, self::EVERY_ADDRESS, self::WHOLE_DOMAIN, self::DIRECT];
    }
}
