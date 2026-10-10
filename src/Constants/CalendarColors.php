<?php

declare(strict_types=1);

namespace OpenEmail\Constants;

final class CalendarColors
{
    public const BLUE = 'blue';
    public const SKY = 'sky';
    public const TEAL = 'teal';
    public const GREEN = 'green';
    public const LIME = 'lime';
    public const YELLOW = 'yellow';
    public const ORANGE = 'orange';
    public const RED = 'red';
    public const PINK = 'pink';
    public const PURPLE = 'purple';
    public const GRAY = 'gray';

    public static function values(): array
    {
        return [
            self::BLUE,
            self::SKY,
            self::TEAL,
            self::GREEN,
            self::LIME,
            self::YELLOW,
            self::ORANGE,
            self::RED,
            self::PINK,
            self::PURPLE,
            self::GRAY,
        ];
    }
}
