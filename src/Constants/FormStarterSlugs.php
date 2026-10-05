<?php

declare(strict_types=1);

namespace OpenEmail\Constants;

final class FormStarterSlugs
{
    public const BLANK = 'blank';
    public const NEWSLETTER = 'newsletter';
    public const WAITLIST = 'waitlist';
    public const EVENT = 'event';
    public const EARLY_ACCESS = 'early-access';
    public const CONTACT = 'contact';

    public static function values(): array
    {
        return [self::BLANK, self::NEWSLETTER, self::WAITLIST, self::EVENT, self::EARLY_ACCESS, self::CONTACT];
    }
}
