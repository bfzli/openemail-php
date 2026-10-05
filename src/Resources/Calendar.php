<?php

declare(strict_types=1);

namespace OpenEmail\Resources;

use OpenEmail\Internal\ApiPaths;
use OpenEmail\Internal\Protocol;
use OpenEmail\Result\Page;

final class Calendar extends Resource
{
    public function listEvents(
        string|\DateTimeInterface $from,
        string|\DateTimeInterface $to,
        ?string $timezone = null,
        ?int $limit = null,
        ?string $cursor = null,
        #[\SensitiveParameter]
        ?string $apiKey = null,
    ): Page {
        return $this->fetchPage(ApiPaths::CALENDAR_EVENTS, $this->rangeQuery($from, $to, $timezone), limit: $limit, cursor: $cursor, apiKey: $apiKey);
    }

    public function listAllEvents(
        string|\DateTimeInterface $from,
        string|\DateTimeInterface $to,
        ?string $timezone = null,
        ?int $limit = null,
        ?string $cursor = null,
        #[\SensitiveParameter]
        ?string $apiKey = null,
    ): array {
        return $this->collectAll(ApiPaths::CALENDAR_EVENTS, $this->rangeQuery($from, $to, $timezone), limit: $limit, cursor: $cursor, apiKey: $apiKey);
    }

    public function iterateEvents(
        string|\DateTimeInterface $from,
        string|\DateTimeInterface $to,
        ?string $timezone = null,
        ?int $limit = null,
        ?string $cursor = null,
        #[\SensitiveParameter]
        ?string $apiKey = null,
    ): \Generator {
        return $this->iteratePages(ApiPaths::CALENDAR_EVENTS, $this->rangeQuery($from, $to, $timezone), limit: $limit, cursor: $cursor, apiKey: $apiKey);
    }

    public function getEvent(string $id, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call($this->fill(ApiPaths::CALENDAR_EVENT, id: $id), apiKey: $apiKey);
    }

    public function getEventIcs(string $id, #[\SensitiveParameter] ?string $apiKey = null): string
    {
        return $this->text($this->fill(ApiPaths::CALENDAR_EVENT_ICS, id: $id), Protocol::CONTENT_TYPES['CALENDAR'], $apiKey);
    }

    public function createEvent(array $body, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call(ApiPaths::CALENDAR_EVENTS, 'POST', body: $this->eventWire($body), apiKey: $apiKey);
    }

    public function updateEvent(string $id, array $patch, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call($this->fill(ApiPaths::CALENDAR_EVENT, id: $id), 'PATCH', body: $this->eventWire($patch), apiKey: $apiKey);
    }

    public function deleteEvent(string $id, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call($this->fill(ApiPaths::CALENDAR_EVENT, id: $id), 'DELETE', apiKey: $apiKey);
    }

    public function cancelEvent(string $id, ?array $body = null, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call($this->fill(ApiPaths::CALENDAR_EVENT_CANCEL, id: $id), 'POST', body: $this->payload($body), apiKey: $apiKey);
    }

    public function respondToEvent(string $id, array $body, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call($this->fill(ApiPaths::CALENDAR_EVENT_RESPOND, id: $id), 'POST', body: $body, apiKey: $apiKey);
    }

    private function rangeQuery(string|\DateTimeInterface $from, string|\DateTimeInterface $to, ?string $timezone): array
    {
        return $this->query(from: $this->instant($from), to: $this->instant($to), timezone: $timezone);
    }

    private function eventWire(array $body): array
    {
        foreach (['start', 'end'] as $field) {
            if (\array_key_exists($field, $body)) {
                $body[$field] = $this->instant($body[$field]);
            }
        }

        return $body;
    }
}
