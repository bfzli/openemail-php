<?php

declare(strict_types=1);

namespace OpenEmail\Resources;

use OpenEmail\Internal\ApiPaths;

final class Workspaces extends Resource
{
    public function list(#[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call(ApiPaths::WORKSPACES, apiKey: $apiKey);
    }

    public function create(array $body, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call(ApiPaths::WORKSPACES, 'POST', body: $body, apiKey: $apiKey);
    }

    public function getActive(#[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call(ApiPaths::WORKSPACES_ACTIVE, apiKey: $apiKey);
    }

    public function setActive(string $workspaceId, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call(ApiPaths::WORKSPACES_ACTIVE, 'PUT', body: ['workspaceId' => $workspaceId], repeatable: true, apiKey: $apiKey);
    }

    public function delete(string $id, string $confirm, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call($this->fill(ApiPaths::WORKSPACE, id: $id), 'DELETE', query: $this->query(confirm: $confirm), apiKey: $apiKey);
    }
}
