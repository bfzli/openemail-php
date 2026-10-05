<?php

declare(strict_types=1);

namespace OpenEmail\Resources;

use OpenEmail\Internal\ApiPaths;
use OpenEmail\Result\Page;

final class Broadcasts extends Resource
{
    public function preview(array $body, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        $wire = \array_key_exists('audienceIds', $body) ? ['audienceIds' => $body['audienceIds']] : [];

        return $this->call(ApiPaths::BROADCASTS_PREVIEW, 'POST', body: $wire, repeatable: true, apiKey: $apiKey);
    }

    public function send(array $body, ?string $idempotencyKey = null, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        if (\array_key_exists('scheduledAt', $body)) {
            $body['scheduledAt'] = $this->instant($body['scheduledAt']);
        }

        return $this->call(
            ApiPaths::BROADCASTS,
            'POST',
            body: $body,
            idempotent: true,
            idempotencyKey: $idempotencyKey,
            repeatable: true,
            apiKey: $apiKey,
        );
    }

    public function list(?string $audienceId = null, ?int $limit = null, ?string $cursor = null, #[\SensitiveParameter] ?string $apiKey = null): Page
    {
        return $this->fetchPage(ApiPaths::BROADCASTS, $this->query(audienceId: $audienceId), limit: $limit, cursor: $cursor, apiKey: $apiKey);
    }

    public function listAll(?string $audienceId = null, ?int $limit = null, ?string $cursor = null, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->collectAll(ApiPaths::BROADCASTS, $this->query(audienceId: $audienceId), limit: $limit, cursor: $cursor, apiKey: $apiKey);
    }

    public function iterate(?string $audienceId = null, ?int $limit = null, ?string $cursor = null, #[\SensitiveParameter] ?string $apiKey = null): \Generator
    {
        return $this->iteratePages(ApiPaths::BROADCASTS, $this->query(audienceId: $audienceId), limit: $limit, cursor: $cursor, apiKey: $apiKey);
    }

    public function get(string $id, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call($this->fill(ApiPaths::BROADCAST, id: $id), apiKey: $apiKey);
    }

    public function stats(
        string $id,
        ?string $grain = null,
        ?int $offsetMinutes = null,
        ?int $days = null,
        ?int $minutes = null,
        #[\SensitiveParameter]
        ?string $apiKey = null,
    ): array {
        return $this->call(
            $this->fill(ApiPaths::BROADCAST_STATS, id: $id),
            query: $this->query(grain: $grain, offsetMinutes: $offsetMinutes, days: $days, minutes: $minutes),
            apiKey: $apiKey,
        );
    }

    public function analytics(
        string|array|null $broadcastIds = null,
        ?int $days = null,
        ?int $minutes = null,
        ?string $grain = null,
        ?int $offsetMinutes = null,
        #[\SensitiveParameter]
        ?string $apiKey = null,
    ): array {
        $query = $this->query(broadcastIds: $this->joined($broadcastIds), days: $days, minutes: $minutes, grain: $grain, offsetMinutes: $offsetMinutes);

        return $this->call(ApiPaths::BROADCASTS_ANALYTICS, query: $query, apiKey: $apiKey);
    }

    public function listRecipients(string $id, ?string $filter = null, ?string $q = null, ?int $limit = null, ?string $cursor = null, #[\SensitiveParameter] ?string $apiKey = null): Page
    {
        return $this->fetchPage($this->fill(ApiPaths::BROADCAST_RECIPIENTS, id: $id), $this->query(filter: $filter, q: $q), limit: $limit, cursor: $cursor, apiKey: $apiKey);
    }

    public function listAllRecipients(string $id, ?string $filter = null, ?string $q = null, ?int $limit = null, ?string $cursor = null, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->collectAll($this->fill(ApiPaths::BROADCAST_RECIPIENTS, id: $id), $this->query(filter: $filter, q: $q), limit: $limit, cursor: $cursor, apiKey: $apiKey);
    }

    public function iterateRecipients(string $id, ?string $filter = null, ?string $q = null, ?int $limit = null, ?string $cursor = null, #[\SensitiveParameter] ?string $apiKey = null): \Generator
    {
        return $this->iteratePages($this->fill(ApiPaths::BROADCAST_RECIPIENTS, id: $id), $this->query(filter: $filter, q: $q), limit: $limit, cursor: $cursor, apiKey: $apiKey);
    }

    public function getRecipient(string $id, string $emailId, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call($this->fill(ApiPaths::BROADCAST_RECIPIENT, id: $id, emailId: $emailId), apiKey: $apiKey);
    }

    public function cancel(string $id, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call($this->fill(ApiPaths::BROADCAST_CANCEL, id: $id), 'POST', repeatable: true, apiKey: $apiKey);
    }
}
