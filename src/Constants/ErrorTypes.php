<?php

declare(strict_types=1);

namespace OpenEmail\Constants;

final class ErrorTypes
{
    public const INVALID_REQUEST_ERROR = 'invalid_request_error';
    public const AUTHENTICATION_ERROR = 'authentication_error';
    public const PERMISSION_ERROR = 'permission_error';
    public const NOT_FOUND_ERROR = 'not_found_error';
    public const CONFLICT_ERROR = 'conflict_error';
    public const VALIDATION_ERROR = 'validation_error';
    public const RATE_LIMIT_ERROR = 'rate_limit_error';
    public const API_ERROR = 'api_error';

    public static function values(): array
    {
        return [
            self::INVALID_REQUEST_ERROR,
            self::AUTHENTICATION_ERROR,
            self::PERMISSION_ERROR,
            self::NOT_FOUND_ERROR,
            self::CONFLICT_ERROR,
            self::VALIDATION_ERROR,
            self::RATE_LIMIT_ERROR,
            self::API_ERROR,
        ];
    }
}
