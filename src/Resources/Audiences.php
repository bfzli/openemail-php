<?php

declare(strict_types=1);

namespace OpenEmail\Resources;

use OpenEmail\Internal\ApiPaths;
use OpenEmail\Result\Page;

final class Audiences extends Resource
{
    public function list(?int $limit = null, ?string $cursor = null, #[\SensitiveParameter] ?string $apiKey = null): Page
    {
        return $this->fetchPage(ApiPaths::AUDIENCES, limit: $limit, cursor: $cursor, apiKey: $apiKey);
    }

    public function listAll(?int $limit = null, ?string $cursor = null, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->collectAll(ApiPaths::AUDIENCES, limit: $limit, cursor: $cursor, apiKey: $apiKey);
    }

    public function iterate(?int $limit = null, ?string $cursor = null, #[\SensitiveParameter] ?string $apiKey = null): \Generator
    {
        return $this->iteratePages(ApiPaths::AUDIENCES, limit: $limit, cursor: $cursor, apiKey: $apiKey);
    }

    public function growth(
        string|array|null $audienceIds = null,
        ?int $days = null,
        ?int $minutes = null,
        ?string $grain = null,
        ?int $offsetMinutes = null,
        #[\SensitiveParameter]
        ?string $apiKey = null,
    ): array {
        $query = $this->query(audienceIds: $this->joined($audienceIds), days: $days, minutes: $minutes, grain: $grain, offsetMinutes: $offsetMinutes);

        return $this->call(ApiPaths::AUDIENCES_GROWTH, query: $query, apiKey: $apiKey);
    }

    public function get(string $id, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call($this->fill(ApiPaths::AUDIENCE, id: $id), apiKey: $apiKey);
    }

    public function create(array $body, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call(ApiPaths::AUDIENCES, 'POST', body: $body, apiKey: $apiKey);
    }

    public function update(string $id, array $patch, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call($this->fill(ApiPaths::AUDIENCE, id: $id), 'PATCH', body: $patch, repeatable: true, apiKey: $apiKey);
    }

    public function delete(string $id, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call($this->fill(ApiPaths::AUDIENCE, id: $id), 'DELETE', apiKey: $apiKey);
    }

    public function empty(string $id, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call($this->fill(ApiPaths::AUDIENCE_EMPTY, id: $id), 'POST', apiKey: $apiKey);
    }

    public function listContacts(
        string $id,
        ?string $q = null,
        ?string $source = null,
        ?string $sort = null,
        string|array|null $statuses = null,
        ?int $limit = null,
        ?string $cursor = null,
        #[\SensitiveParameter]
        ?string $apiKey = null,
    ): Page {
        return $this->fetchPage($this->fill(ApiPaths::AUDIENCE_CONTACTS, id: $id), $this->memberQuery($q, $source, $sort, $statuses), limit: $limit, cursor: $cursor, apiKey: $apiKey);
    }

    public function listAllContacts(
        string $id,
        ?string $q = null,
        ?string $source = null,
        ?string $sort = null,
        string|array|null $statuses = null,
        ?int $limit = null,
        ?string $cursor = null,
        #[\SensitiveParameter]
        ?string $apiKey = null,
    ): array {
        return $this->collectAll($this->fill(ApiPaths::AUDIENCE_CONTACTS, id: $id), $this->memberQuery($q, $source, $sort, $statuses), limit: $limit, cursor: $cursor, apiKey: $apiKey);
    }

    public function iterateContacts(
        string $id,
        ?string $q = null,
        ?string $source = null,
        ?string $sort = null,
        string|array|null $statuses = null,
        ?int $limit = null,
        ?string $cursor = null,
        #[\SensitiveParameter]
        ?string $apiKey = null,
    ): \Generator {
        return $this->iteratePages($this->fill(ApiPaths::AUDIENCE_CONTACTS, id: $id), $this->memberQuery($q, $source, $sort, $statuses), limit: $limit, cursor: $cursor, apiKey: $apiKey);
    }

    public function addContact(string $id, array $body, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call($this->fill(ApiPaths::AUDIENCE_CONTACTS, id: $id), 'POST', body: $body, repeatable: true, apiKey: $apiKey);
    }

    public function removeContact(string $id, string $email, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call($this->fill(ApiPaths::AUDIENCE_CONTACT, id: $id, email: $email), 'DELETE', apiKey: $apiKey);
    }

    public function addContacts(string $id, array $body, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call($this->fill(ApiPaths::AUDIENCE_CONTACTS_BATCH, id: $id), 'POST', body: $body, repeatable: true, apiKey: $apiKey);
    }

    public function removeContacts(string $id, array $body, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call($this->fill(ApiPaths::AUDIENCE_CONTACTS_BATCH_REMOVE, id: $id), 'POST', body: $body, repeatable: true, apiKey: $apiKey);
    }

    public function importContacts(string $id, array $body, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call($this->fill(ApiPaths::AUDIENCE_IMPORT, id: $id), 'POST', body: $body, repeatable: true, apiKey: $apiKey);
    }

    private function memberQuery(?string $q, ?string $source, ?string $sort, string|array|null $statuses): array
    {
        $status = $statuses === null || $statuses === [] || $statuses === '' ? null : $this->joined($statuses);

        return $this->query(q: $q, source: $source, sort: $sort, status: $status);
    }
}
