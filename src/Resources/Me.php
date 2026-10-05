<?php

declare(strict_types=1);

namespace OpenEmail\Resources;

use OpenEmail\Internal\ApiPaths;

final class Me extends Resource
{
    public function get(#[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call(ApiPaths::KEY_SELF, apiKey: $apiKey);
    }

    public function ping(#[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call(ApiPaths::PING, apiKey: $apiKey);
    }

    public function rotate(#[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call(ApiPaths::KEY_SELF_ROTATE, 'POST', apiKey: $apiKey);
    }
}
