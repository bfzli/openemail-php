<?php

declare(strict_types=1);

namespace OpenEmail\Constants;

final class KnowledgeKinds
{
    public const NOTE = 'note';
    public const FILE = 'file';
    public const LINK = 'link';

    public static function values(): array
    {
        return [self::NOTE, self::FILE, self::LINK];
    }
}
