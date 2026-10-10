<?php

declare(strict_types=1);

namespace OpenEmail\Constants;

final class KnowledgeFailures
{
    public const UNSUPPORTED = 'unsupported';
    public const EMPTY = 'empty';
    public const TOO_LARGE = 'too_large';
    public const FETCH_FAILED = 'fetch_failed';
    public const BLOCKED_URL = 'blocked_url';
    public const CONVERSION_FAILED = 'conversion_failed';
    public const INDEX_FAILED = 'index_failed';
    public const OVER_ALLOWANCE = 'over_allowance';
    public const MISSING_FILE = 'missing_file';

    public static function values(): array
    {
        return [
            self::UNSUPPORTED,
            self::EMPTY,
            self::TOO_LARGE,
            self::FETCH_FAILED,
            self::BLOCKED_URL,
            self::CONVERSION_FAILED,
            self::INDEX_FAILED,
            self::OVER_ALLOWANCE,
            self::MISSING_FILE,
        ];
    }
}
