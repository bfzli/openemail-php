<?php

declare(strict_types=1);

namespace OpenEmail\Resources;

use OpenEmail\Internal\ApiPaths;

final class OutOfOffice extends Resource
{
    public function list(?string $address = null, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->fetchList(ApiPaths::OUT_OF_OFFICE, $this->query(address: $address), $apiKey);
    }

    public function set(string $address, array $body, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        foreach (['startsAt', 'endsAt'] as $field) {
            if (\array_key_exists($field, $body)) {
                $body[$field] = $this->instant($body[$field]);
            }
        }

        return $this->call(ApiPaths::OUT_OF_OFFICE, 'PUT', query: $this->query(address: $address), body: $body, repeatable: true, apiKey: $apiKey);
    }

    public function clear(string $address, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call(ApiPaths::OUT_OF_OFFICE, 'DELETE', query: $this->query(address: $address), repeatable: true, apiKey: $apiKey);
    }
}
