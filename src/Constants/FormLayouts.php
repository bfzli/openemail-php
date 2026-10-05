<?php

declare(strict_types=1);

namespace OpenEmail\Constants;

final class FormLayouts
{
    public const CARD = 'card';
    public const PLAIN = 'plain';

    public static function values(): array
    {
        return [self::CARD, self::PLAIN];
    }
}
