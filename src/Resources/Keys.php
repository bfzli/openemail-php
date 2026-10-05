<?php

declare(strict_types=1);

namespace OpenEmail\Resources;

use OpenEmail\Internal\ApiPaths;
use OpenEmail\Result\Page;

final class Keys extends Resource
{
    public function list(?int $limit = null, ?string $cursor = null, #[\SensitiveParameter] ?string $apiKey = null): Page
    {
        return $this->fetchPage(ApiPaths::KEYS, limit: $limit, cursor: $cursor, apiKey: $apiKey);
    }

    public function listAll(?int $limit = null, ?string $cursor = null, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->collectAll(ApiPaths::KEYS, limit: $limit, cursor: $cursor, apiKey: $apiKey);
    }

    public function iterate(?int $limit = null, ?string $cursor = null, #[\SensitiveParameter] ?string $apiKey = null): \Generator
    {
        return $this->iteratePages(ApiPaths::KEYS, limit: $limit, cursor: $cursor, apiKey: $apiKey);
    }

    public function get(string $id, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call($this->fill(ApiPaths::KEY, id: $id), apiKey: $apiKey);
    }

    public function create(array $body, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call(ApiPaths::KEYS, 'POST', body: $body, apiKey: $apiKey);
    }

    public function update(string $id, array $patch, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call($this->fill(ApiPaths::KEY, id: $id), 'PATCH', body: $patch, repeatable: true, apiKey: $apiKey);
    }

    public function delete(string $id, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call($this->fill(ApiPaths::KEY, id: $id), 'DELETE', apiKey: $apiKey);
    }

    public function rotate(string $id, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call($this->fill(ApiPaths::KEY_ROTATE, id: $id), 'POST', apiKey: $apiKey);
    }

    public function revoke(string $id, ?array $body = null, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call($this->fill(ApiPaths::KEY_REVOKE, id: $id), 'POST', body: $this->payload($body), repeatable: true, apiKey: $apiKey);
    }

    public function listRequests(
        string $id,
        ?bool $failedOnly = null,
        string|\DateTimeInterface|null $since = null,
        string|\DateTimeInterface|null $until = null,
        ?int $limit = null,
        ?string $cursor = null,
        #[\SensitiveParameter]
        ?string $apiKey = null,
    ): Page {
        return $this->fetchPage($this->fill(ApiPaths::KEY_REQUESTS, id: $id), $this->requestQuery($since, $until, $failedOnly), limit: $limit, cursor: $cursor, apiKey: $apiKey);
    }

    public function listAllRequests(
        string $id,
        ?bool $failedOnly = null,
        string|\DateTimeInterface|null $since = null,
        string|\DateTimeInterface|null $until = null,
        ?int $limit = null,
        ?string $cursor = null,
        #[\SensitiveParameter]
        ?string $apiKey = null,
    ): array {
        return $this->collectAll($this->fill(ApiPaths::KEY_REQUESTS, id: $id), $this->requestQuery($since, $until, $failedOnly), limit: $limit, cursor: $cursor, apiKey: $apiKey);
    }

    public function iterateRequests(
        string $id,
        ?bool $failedOnly = null,
        string|\DateTimeInterface|null $since = null,
        string|\DateTimeInterface|null $until = null,
        ?int $limit = null,
        ?string $cursor = null,
        #[\SensitiveParameter]
        ?string $apiKey = null,
    ): \Generator {
        return $this->iteratePages($this->fill(ApiPaths::KEY_REQUESTS, id: $id), $this->requestQuery($since, $until, $failedOnly), limit: $limit, cursor: $cursor, apiKey: $apiKey);
    }

    public function listActivity(
        string $id,
        string|\DateTimeInterface|null $since = null,
        string|\DateTimeInterface|null $until = null,
        ?int $limit = null,
        ?string $cursor = null,
        #[\SensitiveParameter]
        ?string $apiKey = null,
    ): Page {
        return $this->fetchPage($this->fill(ApiPaths::KEY_ACTIVITY, id: $id), $this->windowQuery($since, $until), limit: $limit, cursor: $cursor, apiKey: $apiKey);
    }

    public function listAllActivity(
        string $id,
        string|\DateTimeInterface|null $since = null,
        string|\DateTimeInterface|null $until = null,
        ?int $limit = null,
        ?string $cursor = null,
        #[\SensitiveParameter]
        ?string $apiKey = null,
    ): array {
        return $this->collectAll($this->fill(ApiPaths::KEY_ACTIVITY, id: $id), $this->windowQuery($since, $until), limit: $limit, cursor: $cursor, apiKey: $apiKey);
    }

    public function iterateActivity(
        string $id,
        string|\DateTimeInterface|null $since = null,
        string|\DateTimeInterface|null $until = null,
        ?int $limit = null,
        ?string $cursor = null,
        #[\SensitiveParameter]
        ?string $apiKey = null,
    ): \Generator {
        return $this->iteratePages($this->fill(ApiPaths::KEY_ACTIVITY, id: $id), $this->windowQuery($since, $until), limit: $limit, cursor: $cursor, apiKey: $apiKey);
    }

    public function listWorkspaceRequests(
        string|array|null $keyIds = null,
        ?bool $failedOnly = null,
        string|\DateTimeInterface|null $since = null,
        string|\DateTimeInterface|null $until = null,
        ?int $limit = null,
        ?string $cursor = null,
        #[\SensitiveParameter]
        ?string $apiKey = null,
    ): Page {
        return $this->fetchPage(ApiPaths::KEYS_REQUESTS, $this->workspaceRequestQuery($since, $until, $failedOnly, $keyIds), limit: $limit, cursor: $cursor, apiKey: $apiKey);
    }

    public function listAllWorkspaceRequests(
        string|array|null $keyIds = null,
        ?bool $failedOnly = null,
        string|\DateTimeInterface|null $since = null,
        string|\DateTimeInterface|null $until = null,
        ?int $limit = null,
        ?string $cursor = null,
        #[\SensitiveParameter]
        ?string $apiKey = null,
    ): array {
        return $this->collectAll(ApiPaths::KEYS_REQUESTS, $this->workspaceRequestQuery($since, $until, $failedOnly, $keyIds), limit: $limit, cursor: $cursor, apiKey: $apiKey);
    }

    public function iterateWorkspaceRequests(
        string|array|null $keyIds = null,
        ?bool $failedOnly = null,
        string|\DateTimeInterface|null $since = null,
        string|\DateTimeInterface|null $until = null,
        ?int $limit = null,
        ?string $cursor = null,
        #[\SensitiveParameter]
        ?string $apiKey = null,
    ): \Generator {
        return $this->iteratePages(ApiPaths::KEYS_REQUESTS, $this->workspaceRequestQuery($since, $until, $failedOnly, $keyIds), limit: $limit, cursor: $cursor, apiKey: $apiKey);
    }

    public function listWorkspaceActivity(
        string|array|null $keyIds = null,
        string|\DateTimeInterface|null $since = null,
        string|\DateTimeInterface|null $until = null,
        ?int $limit = null,
        ?string $cursor = null,
        #[\SensitiveParameter]
        ?string $apiKey = null,
    ): Page {
        return $this->fetchPage(ApiPaths::KEYS_ACTIVITY, $this->workspaceActivityQuery($since, $until, $keyIds), limit: $limit, cursor: $cursor, apiKey: $apiKey);
    }

    public function listAllWorkspaceActivity(
        string|array|null $keyIds = null,
        string|\DateTimeInterface|null $since = null,
        string|\DateTimeInterface|null $until = null,
        ?int $limit = null,
        ?string $cursor = null,
        #[\SensitiveParameter]
        ?string $apiKey = null,
    ): array {
        return $this->collectAll(ApiPaths::KEYS_ACTIVITY, $this->workspaceActivityQuery($since, $until, $keyIds), limit: $limit, cursor: $cursor, apiKey: $apiKey);
    }

    public function iterateWorkspaceActivity(
        string|array|null $keyIds = null,
        string|\DateTimeInterface|null $since = null,
        string|\DateTimeInterface|null $until = null,
        ?int $limit = null,
        ?string $cursor = null,
        #[\SensitiveParameter]
        ?string $apiKey = null,
    ): \Generator {
        return $this->iteratePages(ApiPaths::KEYS_ACTIVITY, $this->workspaceActivityQuery($since, $until, $keyIds), limit: $limit, cursor: $cursor, apiKey: $apiKey);
    }

    public function stats(
        string|array|null $keyIds = null,
        string|\DateTimeInterface|null $since = null,
        string|\DateTimeInterface|null $until = null,
        ?string $grain = null,
        ?int $offsetMinutes = null,
        #[\SensitiveParameter]
        ?string $apiKey = null,
    ): array {
        $query = [...$this->windowQuery($since, $until), ...$this->query(keyIds: $this->joined($keyIds), grain: $grain, offsetMinutes: $offsetMinutes)];

        return $this->call(ApiPaths::KEYS_STATS, query: $query, apiKey: $apiKey);
    }

    private function windowQuery(string|\DateTimeInterface|null $since, string|\DateTimeInterface|null $until): array
    {
        return $this->query(since: $this->instant($since), until: $this->instant($until));
    }

    private function requestQuery(string|\DateTimeInterface|null $since, string|\DateTimeInterface|null $until, ?bool $failedOnly): array
    {
        return [...$this->windowQuery($since, $until), ...$this->query(failedOnly: $this->flag($failedOnly))];
    }

    private function workspaceRequestQuery(
        string|\DateTimeInterface|null $since,
        string|\DateTimeInterface|null $until,
        ?bool $failedOnly,
        string|array|null $keyIds,
    ): array {
        return [...$this->requestQuery($since, $until, $failedOnly), ...$this->query(keyIds: $this->joined($keyIds))];
    }

    private function workspaceActivityQuery(string|\DateTimeInterface|null $since, string|\DateTimeInterface|null $until, string|array|null $keyIds): array
    {
        return [...$this->windowQuery($since, $until), ...$this->query(keyIds: $this->joined($keyIds))];
    }
}
