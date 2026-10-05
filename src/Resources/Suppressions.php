<?php

declare(strict_types=1);

namespace OpenEmail\Resources;

use OpenEmail\Internal\ApiPaths;
use OpenEmail\Result\Page;

final class Suppressions extends Resource
{
    public function list(?string $q = null, ?string $reason = null, ?int $limit = null, ?string $cursor = null, #[\SensitiveParameter] ?string $apiKey = null): Page
    {
        return $this->fetchPage(ApiPaths::SUPPRESSIONS, $this->query(q: $q, reason: $reason), limit: $limit, cursor: $cursor, apiKey: $apiKey);
    }

    public function listAll(?string $q = null, ?string $reason = null, ?int $limit = null, ?string $cursor = null, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->collectAll(ApiPaths::SUPPRESSIONS, $this->query(q: $q, reason: $reason), limit: $limit, cursor: $cursor, apiKey: $apiKey);
    }

    public function iterate(?string $q = null, ?string $reason = null, ?int $limit = null, ?string $cursor = null, #[\SensitiveParameter] ?string $apiKey = null): \Generator
    {
        return $this->iteratePages(ApiPaths::SUPPRESSIONS, $this->query(q: $q, reason: $reason), limit: $limit, cursor: $cursor, apiKey: $apiKey);
    }

    public function get(string $id, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call($this->fill(ApiPaths::SUPPRESSION, id: $id), apiKey: $apiKey);
    }

    public function add(array $body, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call(ApiPaths::SUPPRESSIONS, 'POST', body: $body, repeatable: true, apiKey: $apiKey);
    }

    public function remove(string $id, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call($this->fill(ApiPaths::SUPPRESSION, id: $id), 'DELETE', apiKey: $apiKey);
    }
}
