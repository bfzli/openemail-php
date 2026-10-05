<?php

declare(strict_types=1);

namespace OpenEmail\Constants;

final class BrandImageVariants
{
    public const MARK = 'mark';
    public const WORDMARK = 'wordmark';
    public const WORDMARK_DARK = 'wordmark-dark';
    public const LOGIN_BACKGROUND = 'login-background';

    public static function values(): array
    {
        return [self::MARK, self::WORDMARK, self::WORDMARK_DARK, self::LOGIN_BACKGROUND];
    }
}
