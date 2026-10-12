<?php

declare(strict_types=1);

namespace OpenEmail\Constants;

final class DedicatedIpIneligibleReasons
{
    public const PLAN = 'plan';
    public const VOLUME = 'volume';

    public static function values(): array
    {
        return [self::PLAN, self::VOLUME];
    }
}
