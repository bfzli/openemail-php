<?php

declare(strict_types=1);

namespace OpenEmail\Resources;

use OpenEmail\Internal\ApiPaths;
use OpenEmail\Result\Page;

final class Roles extends Resource
{
    public function list(?int $limit = null, ?string $cursor = null, #[\SensitiveParameter] ?string $apiKey = null): Page
    {
        return $this->fetchPage(ApiPaths::ROLES, limit: $limit, cursor: $cursor, apiKey: $apiKey);
    }

    public function listAll(?int $limit = null, ?string $cursor = null, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->collectAll(ApiPaths::ROLES, limit: $limit, cursor: $cursor, apiKey: $apiKey);
    }

    public function iterate(?int $limit = null, ?string $cursor = null, #[\SensitiveParameter] ?string $apiKey = null): \Generator
    {
        return $this->iteratePages(ApiPaths::ROLES, limit: $limit, cursor: $cursor, apiKey: $apiKey);
    }

    public function get(string $id, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call($this->fill(ApiPaths::ROLE, id: $id), apiKey: $apiKey);
    }

    public function create(array $body, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call(ApiPaths::ROLES, 'POST', body: $body, apiKey: $apiKey);
    }

    public function update(string $id, array $patch, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call($this->fill(ApiPaths::ROLE, id: $id), 'PATCH', body: $patch, repeatable: true, apiKey: $apiKey);
    }

    public function delete(string $id, ?string $reassignTo = null, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call($this->fill(ApiPaths::ROLE, id: $id), 'DELETE', query: $this->query(reassignTo: $reassignTo), apiKey: $apiKey);
    }

    public function listPermissions(#[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->fetchList(ApiPaths::ROLE_PERMISSIONS, apiKey: $apiKey);
    }
}
