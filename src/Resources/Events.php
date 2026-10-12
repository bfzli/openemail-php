<?php

declare(strict_types=1);

namespace OpenEmail\Resources;

use OpenEmail\Exception\InvalidArgumentException;
use OpenEmail\Internal\ApiPaths;
use OpenEmail\Internal\Messages;

final class Events extends Resource
{
    private const OCCURRED_AT = 'occurredAt';

    public function send(array $body, ?string $idempotencyKey = null, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call(
            ApiPaths::EVENTS,
            'POST',
            body: $this->eventToWire($body),
            idempotent: true,
            idempotencyKey: $idempotencyKey,
            repeatable: true,
            apiKey: $apiKey,
        );
    }

    public function sendBatch(array $events, ?string $idempotencyKey = null, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        $wire = [];

        foreach ($events as $event) {
            if (!\is_array($event)) {
                throw new InvalidArgumentException(Messages::BATCH_EVENT_SHAPE);
            }

            $wire[] = $this->eventToWire($event);
        }

        return $this->call(
            ApiPaths::EVENTS_BATCH,
            'POST',
            body: ['events' => $wire],
            idempotent: true,
            idempotencyKey: $idempotencyKey,
            repeatable: true,
            apiKey: $apiKey,
        );
    }

    public function listNames(#[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->fetchList(ApiPaths::EVENT_NAMES, apiKey: $apiKey);
    }

    private function eventToWire(array $event): array
    {
        if (\array_key_exists(self::OCCURRED_AT, $event)) {
            $event[self::OCCURRED_AT] = $this->instant($event[self::OCCURRED_AT]);
        }

        return $event;
    }
}
