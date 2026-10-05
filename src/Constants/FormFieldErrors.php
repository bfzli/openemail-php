<?php

declare(strict_types=1);

namespace OpenEmail\Constants;

final class FormFieldErrors
{
    public const REQUIRED = 'required';
    public const EMAIL = 'email';
    public const NUMBER = 'number';
    public const MIN = 'min';
    public const MAX = 'max';
    public const TOO_SHORT = 'too_short';
    public const TOO_LONG = 'too_long';
    public const URL = 'url';
    public const DATE = 'date';
    public const PHONE = 'phone';
    public const OPTION = 'option';

    public static function values(): array
    {
        return [
            self::REQUIRED,
            self::EMAIL,
            self::NUMBER,
            self::MIN,
            self::MAX,
            self::TOO_SHORT,
            self::TOO_LONG,
            self::URL,
            self::DATE,
            self::PHONE,
            self::OPTION,
        ];
    }
}
