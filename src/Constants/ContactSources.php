<?php

declare(strict_types=1);

namespace OpenEmail\Constants;

final class ContactSources
{
    public const MANUAL = 'manual';
    public const AUTO = 'auto';
    public const FORM = 'form';

    public static function values(): array
    {
        return [self::MANUAL, self::AUTO, self::FORM];
    }
}
