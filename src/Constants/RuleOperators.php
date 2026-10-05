<?php

declare(strict_types=1);

namespace OpenEmail\Constants;

final class RuleOperators
{
    public const MATCHES = 'matches';
    public const CONTAINS = 'contains';
    public const EQUALS = 'equals';
    public const STARTS_WITH = 'starts_with';
    public const ENDS_WITH = 'ends_with';
    public const GT = 'gt';
    public const LT = 'lt';

    public static function values(): array
    {
        return [self::MATCHES, self::CONTAINS, self::EQUALS, self::STARTS_WITH, self::ENDS_WITH, self::GT, self::LT];
    }
}
