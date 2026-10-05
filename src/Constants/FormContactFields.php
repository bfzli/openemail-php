<?php

declare(strict_types=1);

namespace OpenEmail\Constants;

final class FormContactFields
{
    public const EMAIL = 'email';
    public const FIRST_NAME = 'firstName';
    public const LAST_NAME = 'lastName';
    public const NAME = 'name';

    public static function values(): array
    {
        return [self::EMAIL, self::FIRST_NAME, self::LAST_NAME, self::NAME];
    }
}
