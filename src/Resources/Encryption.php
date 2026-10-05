<?php

declare(strict_types=1);

namespace OpenEmail\Resources;

use OpenEmail\Internal\ApiPaths;

final class Encryption extends Resource
{
    public function listKeys(#[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->fetchList(ApiPaths::ENCRYPTION_KEYS, apiKey: $apiKey);
    }

    public function publishKey(array $body, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call(ApiPaths::ENCRYPTION_KEYS, 'POST', body: $body, apiKey: $apiKey);
    }

    public function lookupKeys(string|array $addresses, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->fetchList(ApiPaths::ENCRYPTION_KEYS_LOOKUP, $this->query(addresses: $this->joined($addresses)), $apiKey);
    }
}
