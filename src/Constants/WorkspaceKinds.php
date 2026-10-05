<?php

declare(strict_types=1);

namespace OpenEmail\Constants;

final class WorkspaceKinds
{
    public const BUSINESS = 'business';
    public const PERSONAL = 'personal';

    public static function values(): array
    {
        return [self::BUSINESS, self::PERSONAL];
    }
}
