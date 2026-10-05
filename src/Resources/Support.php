<?php

declare(strict_types=1);

namespace OpenEmail\Resources;

use OpenEmail\Internal\ApiPaths;

final class Support extends Resource
{
    public function send(array $body, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call(ApiPaths::SUPPORT, 'POST', body: $body, apiKey: $apiKey);
    }
}
