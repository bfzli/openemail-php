<?php

declare(strict_types=1);

namespace OpenEmail\Constants;

final class PlanBlockers
{
    public const DOMAINS = 'domains';
    public const PEOPLE = 'people';
    public const SENDS = 'sends';

    public static function values(): array
    {
        return [self::DOMAINS, self::PEOPLE, self::SENDS];
    }
}
