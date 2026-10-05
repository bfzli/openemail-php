<?php

declare(strict_types=1);

namespace OpenEmail\Resources;

use OpenEmail\Internal\ApiPaths;
use OpenEmail\Result\Page;

final class Chats extends Resource
{
    public function list(?string $q = null, ?string $sort = null, ?int $limit = null, ?string $cursor = null, #[\SensitiveParameter] ?string $apiKey = null): Page
    {
        return $this->fetchPage(ApiPaths::CHATS, $this->query(q: $q, sort: $sort), limit: $limit, cursor: $cursor, apiKey: $apiKey);
    }

    public function listAll(?string $q = null, ?string $sort = null, ?int $limit = null, ?string $cursor = null, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->collectAll(ApiPaths::CHATS, $this->query(q: $q, sort: $sort), limit: $limit, cursor: $cursor, apiKey: $apiKey);
    }

    public function iterate(?string $q = null, ?string $sort = null, ?int $limit = null, ?string $cursor = null, #[\SensitiveParameter] ?string $apiKey = null): \Generator
    {
        return $this->iteratePages(ApiPaths::CHATS, $this->query(q: $q, sort: $sort), limit: $limit, cursor: $cursor, apiKey: $apiKey);
    }

    public function get(string $id, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call($this->fill(ApiPaths::CHAT, id: $id), apiKey: $apiKey);
    }

    public function rename(string $id, string $title, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call($this->fill(ApiPaths::CHAT, id: $id), 'PATCH', body: ['title' => $title], repeatable: true, apiKey: $apiKey);
    }

    public function delete(string $id, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call($this->fill(ApiPaths::CHAT, id: $id), 'DELETE', apiKey: $apiKey);
    }
}
