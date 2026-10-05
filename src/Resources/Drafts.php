<?php

declare(strict_types=1);

namespace OpenEmail\Resources;

use OpenEmail\Internal\ApiPaths;
use OpenEmail\Result\Page;

final class Drafts extends Resource
{
    public function list(?string $query = null, ?int $limit = null, ?string $cursor = null, #[\SensitiveParameter] ?string $apiKey = null): Page
    {
        return $this->fetchPage(ApiPaths::DRAFTS, $this->query(query: $query), self::PAGE_TOKEN, $limit, $cursor, $apiKey);
    }

    public function listAll(?string $query = null, ?int $limit = null, ?string $cursor = null, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->collectAll(ApiPaths::DRAFTS, $this->query(query: $query), self::PAGE_TOKEN, $limit, $cursor, $apiKey);
    }

    public function iterate(?string $query = null, ?int $limit = null, ?string $cursor = null, #[\SensitiveParameter] ?string $apiKey = null): \Generator
    {
        return $this->iteratePages(ApiPaths::DRAFTS, $this->query(query: $query), self::PAGE_TOKEN, $limit, $cursor, $apiKey);
    }

    public function get(string $id, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call($this->fill(ApiPaths::DRAFT, id: $id), apiKey: $apiKey);
    }

    public function create(array $body, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call(ApiPaths::DRAFTS, 'POST', body: $body, apiKey: $apiKey);
    }

    public function update(string $id, array $body, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call($this->fill(ApiPaths::DRAFT, id: $id), 'PATCH', body: $body, apiKey: $apiKey);
    }

    public function delete(string $id, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call($this->fill(ApiPaths::DRAFT, id: $id), 'DELETE', apiKey: $apiKey);
    }
}
