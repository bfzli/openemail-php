<?php

declare(strict_types=1);

namespace OpenEmail\Resources;

use OpenEmail\Internal\ApiPaths;

final class Settings extends Resource
{
    public function get(?string $address = null, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call(ApiPaths::SETTINGS, query: $this->query(address: $address), apiKey: $apiKey);
    }

    public function update(array $patch, ?string $address = null, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call(ApiPaths::SETTINGS, 'PATCH', query: $this->query(address: $address), body: $patch, repeatable: true, apiKey: $apiKey);
    }
}
