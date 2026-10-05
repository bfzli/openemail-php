<?php

declare(strict_types=1);

namespace OpenEmail\Constants;

final class FormAligns
{
    public const LEFT = 'left';
    public const CENTER = 'center';

    public static function values(): array
    {
        return [self::LEFT, self::CENTER];
    }
}
