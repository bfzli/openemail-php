<?php

declare(strict_types=1);

namespace OpenEmail\Constants;

final class AutomationValueSources
{
    public const STATIC = 'static';
    public const CONTACT = 'contact';
    public const EVENT = 'event';

    public static function values(): array
    {
        return [self::STATIC, self::CONTACT, self::EVENT];
    }
}
