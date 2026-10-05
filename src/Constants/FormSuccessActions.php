<?php

declare(strict_types=1);

namespace OpenEmail\Constants;

final class FormSuccessActions
{
    public const MESSAGE = 'message';
    public const REDIRECT = 'redirect';

    public static function values(): array
    {
        return [self::MESSAGE, self::REDIRECT];
    }
}
