<?php

declare(strict_types=1);

namespace OpenEmail\Constants;

final class KnowledgeConnectorKinds
{
    public const SITE = 'site';
    public const SITEMAP = 'sitemap';
    public const FEED = 'feed';
    public const ZENDESK = 'zendesk';

    public static function values(): array
    {
        return [self::SITE, self::SITEMAP, self::FEED, self::ZENDESK];
    }
}
