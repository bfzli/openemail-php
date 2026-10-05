<?php

declare(strict_types=1);

namespace OpenEmail\Resources;

use OpenEmail\Internal\ApiPaths;

final class Exports extends Resource
{
    public function preview(#[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call(ApiPaths::MAILBOX_EXPORTS_PREVIEW, apiKey: $apiKey);
    }

    public function list(?int $limit = null, ?int $offset = null, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call(ApiPaths::MAILBOX_EXPORTS, query: $this->query(limit: $limit, offset: $offset), apiKey: $apiKey);
    }

    public function get(string $id, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call($this->fill(ApiPaths::MAILBOX_EXPORT, id: $id), apiKey: $apiKey);
    }

    public function start(#[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call(ApiPaths::MAILBOX_EXPORTS, 'POST', apiKey: $apiKey);
    }

    public function download(string $id, #[\SensitiveParameter] ?string $apiKey = null): string
    {
        return $this->bytes($this->fill(ApiPaths::MAILBOX_EXPORT_CONTENT, id: $id), $apiKey);
    }
}
