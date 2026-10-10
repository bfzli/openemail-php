<?php

declare(strict_types=1);

namespace OpenEmail\Constants;

final class KnowledgeOrigins
{
    public const APP = 'app';
    public const API = 'api';
    public const ASSISTANT = 'assistant';
    public const MCP = 'mcp';

    public static function values(): array
    {
        return [self::APP, self::API, self::ASSISTANT, self::MCP];
    }
}
