<?php

declare(strict_types=1);

namespace OpenEmail\Constants;

final class WebhookSignatureHeaders
{
    public const SIGNATURE = 'X-OpenEmail-Signature';
    public const EVENT = 'X-OpenEmail-Event';
    public const DELIVERY = 'X-OpenEmail-Delivery';

    public static function values(): array
    {
        return [self::SIGNATURE, self::EVENT, self::DELIVERY];
    }
}
