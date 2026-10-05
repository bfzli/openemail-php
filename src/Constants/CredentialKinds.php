<?php

declare(strict_types=1);

namespace OpenEmail\Constants;

final class CredentialKinds
{
    public const API_KEY = 'apiKey';
    public const OAUTH = 'oauth';

    public static function values(): array
    {
        return [self::API_KEY, self::OAUTH];
    }
}
