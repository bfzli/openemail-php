<?php

declare(strict_types=1);

namespace OpenEmail\Resources;

use OpenEmail\Internal\ApiPaths;

final class Analytics extends Resource
{
    public function mailbox(
        string|\DateTimeInterface|null $from = null,
        string|\DateTimeInterface|null $to = null,
        ?int $offsetMinutes = null,
        ?string $grain = null,
        ?string $address = null,
        #[\SensitiveParameter]
        ?string $apiKey = null,
    ): array {
        $query = $this->query(from: $this->instant($from), to: $this->instant($to), offsetMinutes: $offsetMinutes, grain: $grain, address: $address);

        return $this->call(ApiPaths::ANALYTICS_MAILBOX, query: $query, apiKey: $apiKey);
    }

    public function sending(
        ?int $days = null,
        ?int $minutes = null,
        ?int $offsetMinutes = null,
        ?string $grain = null,
        ?string $address = null,
        #[\SensitiveParameter]
        ?string $apiKey = null,
    ): array {
        $query = $this->query(days: $days, minutes: $minutes, offsetMinutes: $offsetMinutes, grain: $grain, address: $address);

        return $this->call(ApiPaths::ANALYTICS_SENDING, query: $query, apiKey: $apiKey);
    }
}
