<?php

declare(strict_types=1);

namespace OpenEmail\Resources;

use OpenEmail\Internal\ApiPaths;
use OpenEmail\Internal\Pagination;
use OpenEmail\Result\Page;

final class Rules extends Resource
{
    public function list(?bool $enabled = null, ?int $limit = null, ?string $cursor = null, #[\SensitiveParameter] ?string $apiKey = null): Page
    {
        return $this->fetchPage(ApiPaths::RULES, $this->query(enabled: $this->flag($enabled)), limit: $limit, cursor: $cursor, apiKey: $apiKey);
    }

    public function listAll(?bool $enabled = null, ?int $limit = null, ?string $cursor = null, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->collectAll(ApiPaths::RULES, $this->query(enabled: $this->flag($enabled)), limit: $limit, cursor: $cursor, apiKey: $apiKey);
    }

    public function iterate(?bool $enabled = null, ?int $limit = null, ?string $cursor = null, #[\SensitiveParameter] ?string $apiKey = null): \Generator
    {
        return $this->iteratePages(ApiPaths::RULES, $this->query(enabled: $this->flag($enabled)), limit: $limit, cursor: $cursor, apiKey: $apiKey);
    }

    public function get(string $id, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call($this->fill(ApiPaths::RULE, id: $id), apiKey: $apiKey);
    }

    public function create(array $body, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call(ApiPaths::RULES, 'POST', body: $body, apiKey: $apiKey);
    }

    public function update(string $id, array $patch, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call($this->fill(ApiPaths::RULE, id: $id), 'PATCH', body: $patch, apiKey: $apiKey);
    }

    public function delete(string $id, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call($this->fill(ApiPaths::RULE, id: $id), 'DELETE', apiKey: $apiKey);
    }

    public function reorder(array $ruleIds, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        $envelope = $this->call(ApiPaths::RULES_REORDER, 'POST', body: ['ruleIds' => array_values($ruleIds)], repeatable: true, apiKey: $apiKey);

        return Pagination::itemsOf($envelope);
    }

    public function test(string $id, ?array $body = null, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call($this->fill(ApiPaths::RULE_TEST, id: $id), 'POST', body: $this->payload($body), repeatable: true, apiKey: $apiKey);
    }

    public function listRuns(?string $ruleId = null, ?string $threadId = null, ?int $limit = null, ?string $cursor = null, #[\SensitiveParameter] ?string $apiKey = null): Page
    {
        return $this->fetchPage(ApiPaths::RULE_RUNS, $this->query(ruleId: $ruleId, threadId: $threadId), limit: $limit, cursor: $cursor, apiKey: $apiKey);
    }

    public function listAllRuns(?string $ruleId = null, ?string $threadId = null, ?int $limit = null, ?string $cursor = null, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->collectAll(ApiPaths::RULE_RUNS, $this->query(ruleId: $ruleId, threadId: $threadId), limit: $limit, cursor: $cursor, apiKey: $apiKey);
    }

    public function iterateRuns(?string $ruleId = null, ?string $threadId = null, ?int $limit = null, ?string $cursor = null, #[\SensitiveParameter] ?string $apiKey = null): \Generator
    {
        return $this->iteratePages(ApiPaths::RULE_RUNS, $this->query(ruleId: $ruleId, threadId: $threadId), limit: $limit, cursor: $cursor, apiKey: $apiKey);
    }
}
