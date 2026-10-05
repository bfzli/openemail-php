<?php

declare(strict_types=1);

namespace OpenEmail\Resources;

use OpenEmail\Internal\ApiPaths;

final class AppHost extends Resource
{
    public function get(#[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call(ApiPaths::APP_HOST, apiKey: $apiKey);
    }

    public function set(array $body, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call(ApiPaths::APP_HOST, 'PUT', body: $body, repeatable: true, apiKey: $apiKey);
    }

    public function verify(#[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call(ApiPaths::APP_HOST_VERIFY, 'POST', repeatable: true, apiKey: $apiKey);
    }

    public function delete(#[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call(ApiPaths::APP_HOST, 'DELETE', repeatable: true, apiKey: $apiKey);
    }
}
