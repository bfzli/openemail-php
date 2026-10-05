<?php

declare(strict_types=1);

namespace OpenEmail\Constants;

final class ProviderImportDomainStates
{
    public const ABSENT = 'absent';
    public const ADDED = 'added';
    public const VERIFIED = 'verified';

    public static function values(): array
    {
        return [self::ABSENT, self::ADDED, self::VERIFIED];
    }
}
