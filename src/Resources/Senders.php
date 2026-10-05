<?php

declare(strict_types=1);

namespace OpenEmail\Resources;

use OpenEmail\Internal\ApiPaths;

final class Senders extends Resource
{
    public function get(string $email, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call($this->fill(ApiPaths::SENDER, email: $email), apiKey: $apiKey);
    }

    public function research(string $email, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call($this->fill(ApiPaths::SENDER_RESEARCH, email: $email), 'POST', repeatable: true, apiKey: $apiKey);
    }
}
