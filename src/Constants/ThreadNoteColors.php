<?php

declare(strict_types=1);

namespace OpenEmail\Constants;

final class ThreadNoteColors
{
    public const DEFAULT = 'default';
    public const RED = 'red';
    public const ORANGE = 'orange';
    public const YELLOW = 'yellow';
    public const GREEN = 'green';
    public const BLUE = 'blue';
    public const PURPLE = 'purple';
    public const PINK = 'pink';

    public static function values(): array
    {
        return [
            self::DEFAULT,
            self::RED,
            self::ORANGE,
            self::YELLOW,
            self::GREEN,
            self::BLUE,
            self::PURPLE,
            self::PINK,
        ];
    }
}
