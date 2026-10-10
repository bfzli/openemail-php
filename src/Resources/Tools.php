<?php

declare(strict_types=1);

namespace OpenEmail\Resources;

use OpenEmail\Internal\ApiPaths;

final class Tools extends Resource
{
    public function checkDmarc(string $domain, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call(ApiPaths::TOOLS_DMARC, query: $this->query(domain: $domain), apiKey: $apiKey);
    }

    public function checkBimi(string $domain, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call(ApiPaths::TOOLS_BIMI, query: $this->query(domain: $domain), apiKey: $apiKey);
    }

    public function checkDeliverability(string $domain, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call(ApiPaths::TOOLS_DELIVERABILITY, query: $this->query(domain: $domain), apiKey: $apiKey);
    }

    public function readVerificationCode(#[\SensitiveParameter] array $body, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call(ApiPaths::TOOLS_VERIFICATION_CODE, 'POST', body: $body, apiKey: $apiKey);
    }

    public function estimateCost(
        ?int $sends = null,
        ?int $domains = null,
        ?int $people = null,
        ?int $aiActions = null,
        ?string $interval = null,
        #[\SensitiveParameter]
        ?string $apiKey = null,
    ): array {
        $query = $this->query(sends: $sends, domains: $domains, people: $people, aiActions: $aiActions, interval: $interval);

        return $this->call(ApiPaths::TOOLS_COST, query: $query, apiKey: $apiKey);
    }
}
