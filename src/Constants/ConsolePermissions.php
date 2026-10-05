<?php

declare(strict_types=1);

namespace OpenEmail\Constants;

final class ConsolePermissions
{
    public const ADDRESSES_ALL = 'addresses:all';
    public const API_KEYS_READ = 'api-keys:read';
    public const API_KEYS_WRITE = 'api-keys:write';
    public const BILLING_READ = 'billing:read';
    public const BILLING_WRITE = 'billing:write';
    public const WORKSPACE_MANAGE = 'workspace:manage';

    public static function values(): array
    {
        return [
            self::ADDRESSES_ALL,
            self::API_KEYS_READ,
            self::API_KEYS_WRITE,
            self::BILLING_READ,
            self::BILLING_WRITE,
            self::WORKSPACE_MANAGE,
        ];
    }
}
