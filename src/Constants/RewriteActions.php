<?php

declare(strict_types=1);

namespace OpenEmail\Constants;

final class RewriteActions
{
    public const SHORTEN = 'shorten';
    public const LENGTHEN = 'lengthen';
    public const REPHRASE = 'rephrase';
    public const FORMAL = 'formal';
    public const CASUAL = 'casual';
    public const CUSTOM = 'custom';

    public static function values(): array
    {
        return [self::SHORTEN, self::LENGTHEN, self::REPHRASE, self::FORMAL, self::CASUAL, self::CUSTOM];
    }
}
