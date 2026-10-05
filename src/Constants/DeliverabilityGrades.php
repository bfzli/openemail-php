<?php

declare(strict_types=1);

namespace OpenEmail\Constants;

final class DeliverabilityGrades
{
    public const A = 'A';
    public const B = 'B';
    public const C = 'C';
    public const D = 'D';
    public const F = 'F';

    public static function values(): array
    {
        return [self::A, self::B, self::C, self::D, self::F];
    }
}
