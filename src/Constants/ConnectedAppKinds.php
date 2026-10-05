<?php

declare(strict_types=1);

namespace OpenEmail\Constants;

final class ConnectedAppKinds
{
    public const APP = 'app';
    public const CLI = 'cli';

    public static function values(): array
    {
        return [self::APP, self::CLI];
    }
}
