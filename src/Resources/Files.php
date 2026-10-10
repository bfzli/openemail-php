<?php

declare(strict_types=1);

namespace OpenEmail\Resources;

use OpenEmail\Internal\ApiPaths;
use OpenEmail\Result\Page;

final class Files extends Resource
{
    public function list(
        ?string $q = null,
        ?string $kind = null,
        ?string $direction = null,
        ?string $address = null,
        string|\DateTimeInterface|null $since = null,
        string|\DateTimeInterface|null $until = null,
        ?string $sort = null,
        ?int $limit = null,
        ?string $cursor = null,
        #[\SensitiveParameter]
        ?string $apiKey = null,
    ): Page {
        return $this->fetchPage(ApiPaths::FILES, $this->listQuery($q, $kind, $direction, $address, $since, $until, $sort), limit: $limit, cursor: $cursor, apiKey: $apiKey);
    }

    public function listAll(
        ?string $q = null,
        ?string $kind = null,
        ?string $direction = null,
        ?string $address = null,
        string|\DateTimeInterface|null $since = null,
        string|\DateTimeInterface|null $until = null,
        ?string $sort = null,
        ?int $limit = null,
        ?string $cursor = null,
        #[\SensitiveParameter]
        ?string $apiKey = null,
    ): array {
        return $this->collectAll(ApiPaths::FILES, $this->listQuery($q, $kind, $direction, $address, $since, $until, $sort), limit: $limit, cursor: $cursor, apiKey: $apiKey);
    }

    public function iterate(
        ?string $q = null,
        ?string $kind = null,
        ?string $direction = null,
        ?string $address = null,
        string|\DateTimeInterface|null $since = null,
        string|\DateTimeInterface|null $until = null,
        ?string $sort = null,
        ?int $limit = null,
        ?string $cursor = null,
        #[\SensitiveParameter]
        ?string $apiKey = null,
    ): \Generator {
        return $this->iteratePages(ApiPaths::FILES, $this->listQuery($q, $kind, $direction, $address, $since, $until, $sort), limit: $limit, cursor: $cursor, apiKey: $apiKey);
    }

    public function get(string $id, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call($this->fill(ApiPaths::FILE, id: $id), apiKey: $apiKey);
    }

    public function stats(#[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call(ApiPaths::FILES_STATS, apiKey: $apiKey);
    }

    public function download(string $id, #[\SensitiveParameter] ?string $apiKey = null): string
    {
        return $this->bytes($this->fill(ApiPaths::FILE_CONTENT, id: $id), $apiKey);
    }

    public function listLinks(string $id, ?int $limit = null, ?string $cursor = null, #[\SensitiveParameter] ?string $apiKey = null): Page
    {
        return $this->fetchPage($this->fill(ApiPaths::FILE_LINKS, id: $id), limit: $limit, cursor: $cursor, apiKey: $apiKey);
    }

    public function listAllLinks(string $id, ?int $limit = null, ?string $cursor = null, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->collectAll($this->fill(ApiPaths::FILE_LINKS, id: $id), limit: $limit, cursor: $cursor, apiKey: $apiKey);
    }

    public function iterateLinks(string $id, ?int $limit = null, ?string $cursor = null, #[\SensitiveParameter] ?string $apiKey = null): \Generator
    {
        return $this->iteratePages($this->fill(ApiPaths::FILE_LINKS, id: $id), limit: $limit, cursor: $cursor, apiKey: $apiKey);
    }

    public function createLink(string $id, ?string $domain = null, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        $body = $domain === null || $domain === '' ? [] : ['domain' => $domain];

        return $this->call($this->fill(ApiPaths::FILE_LINKS, id: $id), 'POST', body: $body, apiKey: $apiKey);
    }

    public function revokeLink(string $id, string $linkId, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call($this->fill(ApiPaths::FILE_LINK, id: $id, linkId: $linkId), 'DELETE', repeatable: true, apiKey: $apiKey);
    }

    public function revokeAllLinks(string $id, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call($this->fill(ApiPaths::FILE_LINKS, id: $id), 'DELETE', repeatable: true, apiKey: $apiKey);
    }

    public function upload(
        mixed $data,
        ?string $filename = null,
        ?string $contentType = null,
        int|float|null $timeout = null,
        #[\SensitiveParameter]
        ?string $apiKey = null,
    ): array {
        return $this->call(
            ApiPaths::FILES,
            'POST',
            query: $this->query(filename: self::uploadName($data, $filename)),
            raw: $data,
            contentType: $this->rawContentType($data, $contentType),
            timeout: $timeout ?? $this->uploadTimeout(),
            apiKey: $apiKey,
        );
    }

    public function delete(string $id, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call($this->fill(ApiPaths::FILE, id: $id), 'DELETE', apiKey: $apiKey);
    }

    public function deleteMany(array $ids, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call(ApiPaths::FILES_BATCH_DELETE, 'POST', body: ['ids' => array_values($ids)], apiKey: $apiKey);
    }

    private function listQuery(
        ?string $q,
        ?string $kind,
        ?string $direction,
        ?string $address,
        string|\DateTimeInterface|null $since,
        string|\DateTimeInterface|null $until,
        ?string $sort,
    ): array {
        return $this->query(
            q: $q,
            kind: $kind,
            direction: $direction,
            address: $address,
            since: $this->instant($since),
            until: $this->instant($until),
            sort: $sort,
        );
    }
}
