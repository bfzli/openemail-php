<?php

declare(strict_types=1);

namespace OpenEmail\Constants;

final class KnowledgeFlagKinds
{
    public const DUPLICATE = 'duplicate';
    public const CONFLICT = 'conflict';

    public static function values(): array
    {
        return [self::DUPLICATE, self::CONFLICT];
    }
}
