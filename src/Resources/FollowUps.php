<?php

declare(strict_types=1);

namespace OpenEmail\Resources;

use OpenEmail\Internal\ApiPaths;

final class FollowUps extends Resource
{
    public function list(
        ?string $status = null,
        ?string $threadId = null,
        ?int $limit = null,
        #[\SensitiveParameter]
        ?string $apiKey = null,
    ): array {
        return $this->fetchList(ApiPaths::FOLLOW_UPS, $this->query(status: $status, threadId: $threadId, limit: $limit), $apiKey);
    }

    public function set(array $body, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        $wire = \array_key_exists('threadId', $body) ? ['threadId' => $body['threadId']] : [];

        if (\array_key_exists('remindAt', $body)) {
            $wire['remindAt'] = $this->instant($body['remindAt']);
        }

        return $this->call(ApiPaths::FOLLOW_UPS, 'POST', body: $wire, repeatable: true, apiKey: $apiKey);
    }

    public function cancel(string $id, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call($this->fill(ApiPaths::FOLLOW_UP, id: $id), 'DELETE', repeatable: true, apiKey: $apiKey);
    }
}
