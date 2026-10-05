<?php

declare(strict_types=1);

namespace OpenEmail\Constants;

final class DeliverabilityCheckIds
{
    public const MX = 'mx';
    public const SPF = 'spf';
    public const DKIM = 'dkim';
    public const DMARC = 'dmarc';
    public const BIMI = 'bimi';

    public static function values(): array
    {
        return [self::MX, self::SPF, self::DKIM, self::DMARC, self::BIMI];
    }
}
