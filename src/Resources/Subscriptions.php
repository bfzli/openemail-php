<?php

declare(strict_types=1);

namespace OpenEmail\Resources;

use OpenEmail\Internal\ApiPaths;

final class Subscriptions extends Resource
{
    public function list(
        ?string $status = null,
        ?string $q = null,
        ?string $sort = null,
        ?int $limit = null,
        ?int $offset = null,
        ?string $address = null,
        #[\SensitiveParameter]
        ?string $apiKey = null,
    ): array {
        return $this->call(ApiPaths::SUBSCRIPTIONS, query: $this->listQuery($status, $q, $sort, $limit, $offset, $address), apiKey: $apiKey);
    }

    public function listDomains(
        ?string $status = null,
        ?string $q = null,
        ?string $sort = null,
        ?int $limit = null,
        ?int $offset = null,
        ?string $address = null,
        #[\SensitiveParameter]
        ?string $apiKey = null,
    ): array {
        return $this->call(ApiPaths::SUBSCRIPTION_DOMAINS, query: $this->listQuery($status, $q, $sort, $limit, $offset, $address), apiKey: $apiKey);
    }

    public function unsubscribe(string $id, ?array $body = null, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call($this->fill(ApiPaths::SUBSCRIPTION_UNSUBSCRIBE, id: $id), 'POST', body: $this->payload($body), apiKey: $apiKey);
    }

    public function unsubscribeDomain(string $domain, ?array $body = null, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call($this->fill(ApiPaths::SUBSCRIPTION_DOMAIN_UNSUBSCRIBE, domain: $domain), 'POST', body: $this->payload($body), apiKey: $apiKey);
    }

    public function move(string $id, array $body, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call($this->fill(ApiPaths::SUBSCRIPTION_MOVE, id: $id), 'POST', body: $body, repeatable: true, apiKey: $apiKey);
    }

    private function listQuery(?string $status, ?string $q, ?string $sort, ?int $limit, ?int $offset, ?string $address): array
    {
        return $this->query(status: $status, q: $q, sort: $sort, limit: $limit, offset: $offset, address: $address);
    }
}
