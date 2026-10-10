<?php

declare(strict_types=1);

namespace OpenEmail\Constants;

final class ContactCardLabels
{
    public const HOME = 'home';
    public const WORK = 'work';
    public const MOBILE = 'mobile';
    public const MAIN = 'main';
    public const FAX = 'fax';
    public const PAGER = 'pager';
    public const OTHER = 'other';

    public static function values(): array
    {
        return [self::HOME, self::WORK, self::MOBILE, self::MAIN, self::FAX, self::PAGER, self::OTHER];
    }
}
