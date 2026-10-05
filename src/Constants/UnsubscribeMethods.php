<?php

declare(strict_types=1);

namespace OpenEmail\Constants;

final class UnsubscribeMethods
{
    public const ONE_CLICK = 'one-click';
    public const EMAIL = 'email';
    public const LINK = 'link';

    public static function values(): array
    {
        return [self::ONE_CLICK, self::EMAIL, self::LINK];
    }
}
