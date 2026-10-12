<?php

declare(strict_types=1);

namespace OpenEmail\Constants;

final class AutomationFieldOperators
{
    public const EQUALS = 'equals';
    public const NOT_EQUALS = 'not_equals';
    public const CONTAINS = 'contains';
    public const NOT_CONTAINS = 'not_contains';
    public const STARTS_WITH = 'starts_with';
    public const ENDS_WITH = 'ends_with';
    public const IS_SET = 'is_set';
    public const IS_NOT_SET = 'is_not_set';
    public const GREATER_THAN = 'greater_than';
    public const LESS_THAN = 'less_than';

    public static function values(): array
    {
        return [
            self::EQUALS,
            self::NOT_EQUALS,
            self::CONTAINS,
            self::NOT_CONTAINS,
            self::STARTS_WITH,
            self::ENDS_WITH,
            self::IS_SET,
            self::IS_NOT_SET,
            self::GREATER_THAN,
            self::LESS_THAN,
        ];
    }
}
