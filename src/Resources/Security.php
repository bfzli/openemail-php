<?php

declare(strict_types=1);

namespace OpenEmail\Resources;

use OpenEmail\Internal\ApiPaths;

final class Security extends Resource
{
    public function stepUpStatus(#[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call(ApiPaths::SECURITY_STEP_UP, apiKey: $apiKey);
    }

    public function beginStepUp(?array $body = null, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call(ApiPaths::SECURITY_STEP_UP, 'POST', body: $this->payload($body), apiKey: $apiKey);
    }

    public function verifyStepUp(#[\SensitiveParameter] array $body, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call(ApiPaths::SECURITY_STEP_UP_VERIFY, 'POST', body: $body, apiKey: $apiKey);
    }
}
