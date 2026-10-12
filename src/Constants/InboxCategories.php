<?php

declare(strict_types=1);

namespace OpenEmail\Constants;

final class InboxCategories
{
    public const PRIMARY = 'primary';
    public const PROMOTIONS = 'promotions';
    public const UPDATES = 'updates';
    public const SOCIAL = 'social';
    public const FORUMS = 'forums';

    public static function values(): array
    {
        return [self::PRIMARY, self::PROMOTIONS, self::UPDATES, self::SOCIAL, self::FORUMS];
    }
}
