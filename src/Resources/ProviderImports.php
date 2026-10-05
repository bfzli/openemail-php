<?php

declare(strict_types=1);

namespace OpenEmail\Resources;

use OpenEmail\Internal\ApiPaths;
use OpenEmail\Result\Page;

final class ProviderImports extends Resource
{
    public function inspect(#[\SensitiveParameter] string $providerKey, ?string $provider = null, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        $body = $provider === null ? ['apiKey' => $providerKey] : ['apiKey' => $providerKey, 'provider' => $provider];

        return $this->call(ApiPaths::PROVIDER_IMPORTS_INSPECT, 'POST', body: $body, apiKey: $apiKey);
    }

    public function create(#[\SensitiveParameter] array $body, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call(ApiPaths::PROVIDER_IMPORTS, 'POST', body: $body, apiKey: $apiKey);
    }

    public function list(?int $limit = null, ?string $cursor = null, #[\SensitiveParameter] ?string $apiKey = null): Page
    {
        return $this->fetchPage(ApiPaths::PROVIDER_IMPORTS, limit: $limit, cursor: $cursor, apiKey: $apiKey);
    }

    public function listAll(?int $limit = null, ?string $cursor = null, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->collectAll(ApiPaths::PROVIDER_IMPORTS, limit: $limit, cursor: $cursor, apiKey: $apiKey);
    }

    public function iterate(?int $limit = null, ?string $cursor = null, #[\SensitiveParameter] ?string $apiKey = null): \Generator
    {
        return $this->iteratePages(ApiPaths::PROVIDER_IMPORTS, limit: $limit, cursor: $cursor, apiKey: $apiKey);
    }

    public function get(string $id, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call($this->fill(ApiPaths::PROVIDER_IMPORT, id: $id), apiKey: $apiKey);
    }

    public function cancel(string $id, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call($this->fill(ApiPaths::PROVIDER_IMPORT_CANCEL, id: $id), 'POST', apiKey: $apiKey);
    }
}
