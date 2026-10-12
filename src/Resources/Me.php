<?php

declare(strict_types=1);

namespace OpenEmail\Resources;

use OpenEmail\Internal\ApiPaths;
use OpenEmail\Result\Page;

final class Me extends Resource
{
    public function get(#[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call(ApiPaths::KEY_SELF, apiKey: $apiKey);
    }

    public function ping(#[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call(ApiPaths::PING, apiKey: $apiKey);
    }

    public function rotate(#[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call(ApiPaths::KEY_SELF_ROTATE, 'POST', apiKey: $apiKey);
    }

    public function listRequests(
        ?bool $failedOnly = null,
        string|array|null $statuses = null,
        ?string $path = null,
        string|\DateTimeInterface|null $since = null,
        string|\DateTimeInterface|null $until = null,
        ?int $limit = null,
        ?string $cursor = null,
        #[\SensitiveParameter]
        ?string $apiKey = null,
    ): Page {
        return $this->fetchPage(ApiPaths::KEY_SELF_REQUESTS, $this->requestQuery($since, $until, $failedOnly, $statuses, $path), limit: $limit, cursor: $cursor, apiKey: $apiKey);
    }

    public function listAllRequests(
        ?bool $failedOnly = null,
        string|array|null $statuses = null,
        ?string $path = null,
        string|\DateTimeInterface|null $since = null,
        string|\DateTimeInterface|null $until = null,
        ?int $limit = null,
        ?string $cursor = null,
        #[\SensitiveParameter]
        ?string $apiKey = null,
    ): array {
        return $this->collectAll(ApiPaths::KEY_SELF_REQUESTS, $this->requestQuery($since, $until, $failedOnly, $statuses, $path), limit: $limit, cursor: $cursor, apiKey: $apiKey);
    }

    public function iterateRequests(
        ?bool $failedOnly = null,
        string|array|null $statuses = null,
        ?string $path = null,
        string|\DateTimeInterface|null $since = null,
        string|\DateTimeInterface|null $until = null,
        ?int $limit = null,
        ?string $cursor = null,
        #[\SensitiveParameter]
        ?string $apiKey = null,
    ): \Generator {
        return $this->iteratePages(ApiPaths::KEY_SELF_REQUESTS, $this->requestQuery($since, $until, $failedOnly, $statuses, $path), limit: $limit, cursor: $cursor, apiKey: $apiKey);
    }

    public function listActivity(
        string|\DateTimeInterface|null $since = null,
        string|\DateTimeInterface|null $until = null,
        ?int $limit = null,
        ?string $cursor = null,
        #[\SensitiveParameter]
        ?string $apiKey = null,
    ): Page {
        return $this->fetchPage(ApiPaths::KEY_SELF_ACTIVITY, $this->windowQuery($since, $until), limit: $limit, cursor: $cursor, apiKey: $apiKey);
    }

    public function listAllActivity(
        string|\DateTimeInterface|null $since = null,
        string|\DateTimeInterface|null $until = null,
        ?int $limit = null,
        ?string $cursor = null,
        #[\SensitiveParameter]
        ?string $apiKey = null,
    ): array {
        return $this->collectAll(ApiPaths::KEY_SELF_ACTIVITY, $this->windowQuery($since, $until), limit: $limit, cursor: $cursor, apiKey: $apiKey);
    }

    public function iterateActivity(
        string|\DateTimeInterface|null $since = null,
        string|\DateTimeInterface|null $until = null,
        ?int $limit = null,
        ?string $cursor = null,
        #[\SensitiveParameter]
        ?string $apiKey = null,
    ): \Generator {
        return $this->iteratePages(ApiPaths::KEY_SELF_ACTIVITY, $this->windowQuery($since, $until), limit: $limit, cursor: $cursor, apiKey: $apiKey);
    }

    public function stats(
        string|\DateTimeInterface|null $since = null,
        string|\DateTimeInterface|null $until = null,
        ?string $grain = null,
        ?int $offsetMinutes = null,
        #[\SensitiveParameter]
        ?string $apiKey = null,
    ): array {
        $query = [...$this->windowQuery($since, $until), ...$this->query(grain: $grain, offsetMinutes: $offsetMinutes)];

        return $this->call(ApiPaths::KEY_SELF_STATS, query: $query, apiKey: $apiKey);
    }
}
