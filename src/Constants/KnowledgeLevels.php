<?php

declare(strict_types=1);

namespace OpenEmail\Constants;

final class KnowledgeLevels
{
    public const WORKSPACE = 'workspace';
    public const DOMAIN = 'domain';
    public const ADDRESS = 'address';

    public static function values(): array
    {
        return [self::WORKSPACE, self::DOMAIN, self::ADDRESS];
    }
}
