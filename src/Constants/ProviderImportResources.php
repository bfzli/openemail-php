<?php

declare(strict_types=1);

namespace OpenEmail\Constants;

final class ProviderImportResources
{
    public const SUPPRESSIONS = 'suppressions';
    public const AUDIENCES = 'audiences';
    public const CONTACTS = 'contacts';
    public const TEMPLATES = 'templates';
    public const WEBHOOKS = 'webhooks';
    public const DOMAINS = 'domains';
    public const API_KEYS = 'api-keys';

    public static function values(): array
    {
        return [
            self::SUPPRESSIONS,
            self::AUDIENCES,
            self::CONTACTS,
            self::TEMPLATES,
            self::WEBHOOKS,
            self::DOMAINS,
            self::API_KEYS,
        ];
    }
}
