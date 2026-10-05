<?php

declare(strict_types=1);

namespace OpenEmail\Resources;

use OpenEmail\Internal\ApiPaths;

final class DnsConnections extends Resource
{
    public function list(#[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call(ApiPaths::DNS_CONNECTIONS, apiKey: $apiKey);
    }

    public function get(string $id, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call($this->fill(ApiPaths::DNS_CONNECTION, id: $id), apiKey: $apiKey);
    }

    public function delete(string $id, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call($this->fill(ApiPaths::DNS_CONNECTION, id: $id), 'DELETE', apiKey: $apiKey);
    }
}
