<?php

declare(strict_types=1);

namespace OpenEmail\Resources;

use OpenEmail\Internal\ApiPaths;
use OpenEmail\Result\Page;

final class Knowledge extends Resource
{
    public function list(
        ?string $scope = null,
        ?string $level = null,
        ?string $kind = null,
        ?string $status = null,
        ?bool $pinned = null,
        ?string $q = null,
        ?int $limit = null,
        ?string $cursor = null,
        #[\SensitiveParameter]
        ?string $apiKey = null,
    ): Page {
        return $this->fetchPage(ApiPaths::KNOWLEDGE, $this->listQuery($scope, $level, $kind, $status, $pinned, $q), limit: $limit, cursor: $cursor, apiKey: $apiKey);
    }

    public function listAll(
        ?string $scope = null,
        ?string $level = null,
        ?string $kind = null,
        ?string $status = null,
        ?bool $pinned = null,
        ?string $q = null,
        ?int $limit = null,
        ?string $cursor = null,
        #[\SensitiveParameter]
        ?string $apiKey = null,
    ): array {
        return $this->collectAll(ApiPaths::KNOWLEDGE, $this->listQuery($scope, $level, $kind, $status, $pinned, $q), limit: $limit, cursor: $cursor, apiKey: $apiKey);
    }

    public function iterate(
        ?string $scope = null,
        ?string $level = null,
        ?string $kind = null,
        ?string $status = null,
        ?bool $pinned = null,
        ?string $q = null,
        ?int $limit = null,
        ?string $cursor = null,
        #[\SensitiveParameter]
        ?string $apiKey = null,
    ): \Generator {
        return $this->iteratePages(ApiPaths::KNOWLEDGE, $this->listQuery($scope, $level, $kind, $status, $pinned, $q), limit: $limit, cursor: $cursor, apiKey: $apiKey);
    }

    public function levels(#[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->fetchList(ApiPaths::KNOWLEDGE_LEVELS, apiKey: $apiKey);
    }

    public function usage(#[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call(ApiPaths::KNOWLEDGE_USAGE, apiKey: $apiKey);
    }

    public function search(array $body, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call(ApiPaths::KNOWLEDGE_SEARCH, 'POST', body: $body, repeatable: true, apiKey: $apiKey);
    }

    public function createNote(array $body, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call(ApiPaths::KNOWLEDGE_NOTES, 'POST', body: $body, apiKey: $apiKey);
    }

    public function addLink(array $body, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call(ApiPaths::KNOWLEDGE_LINKS, 'POST', body: $body, apiKey: $apiKey);
    }

    public function uploadFile(
        mixed $data,
        ?string $filename = null,
        ?string $contentType = null,
        ?string $scope = null,
        ?string $title = null,
        int|float|null $timeout = null,
        #[\SensitiveParameter]
        ?string $apiKey = null,
    ): array {
        return $this->call(
            ApiPaths::KNOWLEDGE_FILES,
            'POST',
            query: $this->query(filename: self::uploadName($data, $filename), scope: $scope, title: $title),
            raw: $data,
            contentType: $this->rawContentType($data, $contentType),
            timeout: $timeout ?? $this->uploadTimeout(),
            apiKey: $apiKey,
        );
    }

    public function get(string $id, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call($this->fill(ApiPaths::KNOWLEDGE_ITEM, id: $id), apiKey: $apiKey);
    }

    public function update(string $id, array $patch, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call($this->fill(ApiPaths::KNOWLEDGE_ITEM, id: $id), 'PATCH', body: $patch, repeatable: true, apiKey: $apiKey);
    }

    public function delete(string $id, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call($this->fill(ApiPaths::KNOWLEDGE_ITEM, id: $id), 'DELETE', apiKey: $apiKey);
    }

    public function refresh(string $id, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call($this->fill(ApiPaths::KNOWLEDGE_ITEM_REFRESH, id: $id), 'POST', repeatable: true, apiKey: $apiKey);
    }

    public function stats(?int $days = null, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call(ApiPaths::KNOWLEDGE_STATS, query: $this->query(days: $days), apiKey: $apiKey);
    }

    public function listSuggestions(
        ?string $kind = null,
        ?string $status = null,
        ?int $limit = null,
        ?string $cursor = null,
        #[\SensitiveParameter]
        ?string $apiKey = null,
    ): Page {
        return $this->fetchPage(ApiPaths::KNOWLEDGE_SUGGESTIONS, $this->suggestionQuery($kind, $status), limit: $limit, cursor: $cursor, apiKey: $apiKey);
    }

    public function listAllSuggestions(
        ?string $kind = null,
        ?string $status = null,
        ?int $limit = null,
        ?string $cursor = null,
        #[\SensitiveParameter]
        ?string $apiKey = null,
    ): array {
        return $this->collectAll(ApiPaths::KNOWLEDGE_SUGGESTIONS, $this->suggestionQuery($kind, $status), limit: $limit, cursor: $cursor, apiKey: $apiKey);
    }

    public function iterateSuggestions(
        ?string $kind = null,
        ?string $status = null,
        ?int $limit = null,
        ?string $cursor = null,
        #[\SensitiveParameter]
        ?string $apiKey = null,
    ): \Generator {
        return $this->iteratePages(ApiPaths::KNOWLEDGE_SUGGESTIONS, $this->suggestionQuery($kind, $status), limit: $limit, cursor: $cursor, apiKey: $apiKey);
    }

    public function acceptSuggestion(string $id, ?array $body = null, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call($this->fill(ApiPaths::KNOWLEDGE_SUGGESTION_ACCEPT, id: $id), 'POST', body: $this->payload($body), apiKey: $apiKey);
    }

    public function dismissSuggestion(string $id, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call($this->fill(ApiPaths::KNOWLEDGE_SUGGESTION_DISMISS, id: $id), 'POST', apiKey: $apiKey);
    }

    public function listFlags(?string $itemId = null, ?int $limit = null, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->fetchList(ApiPaths::KNOWLEDGE_FLAGS, $this->query(itemId: $itemId, limit: $limit), $apiKey);
    }

    public function dismissFlag(string $id, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call($this->fill(ApiPaths::KNOWLEDGE_FLAG_DISMISS, id: $id), 'POST', repeatable: true, apiKey: $apiKey);
    }

    public function listConnectors(#[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->fetchList(ApiPaths::KNOWLEDGE_CONNECTORS, apiKey: $apiKey);
    }

    public function addConnector(array $body, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call(ApiPaths::KNOWLEDGE_CONNECTORS, 'POST', body: $body, apiKey: $apiKey);
    }

    public function getConnector(string $id, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call($this->fill(ApiPaths::KNOWLEDGE_CONNECTOR, id: $id), apiKey: $apiKey);
    }

    public function updateConnector(string $id, array $patch, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call($this->fill(ApiPaths::KNOWLEDGE_CONNECTOR, id: $id), 'PATCH', body: $patch, repeatable: true, apiKey: $apiKey);
    }

    public function deleteConnector(string $id, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call($this->fill(ApiPaths::KNOWLEDGE_CONNECTOR, id: $id), 'DELETE', apiKey: $apiKey);
    }

    public function syncConnector(string $id, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call($this->fill(ApiPaths::KNOWLEDGE_CONNECTOR_SYNC, id: $id), 'POST', repeatable: true, apiKey: $apiKey);
    }

    public function draftFromThread(array $body, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call(ApiPaths::KNOWLEDGE_DRAFTS, 'POST', body: $body, apiKey: $apiKey);
    }

    private function listQuery(?string $scope, ?string $level, ?string $kind, ?string $status, ?bool $pinned, ?string $q): array
    {
        return $this->query(scope: $scope, level: $level, kind: $kind, status: $status, pinned: $this->flag($pinned), q: $q);
    }

    private function suggestionQuery(?string $kind, ?string $status): array
    {
        return $this->query(kind: $kind, status: $status);
    }
}
