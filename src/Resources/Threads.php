<?php

declare(strict_types=1);

namespace OpenEmail\Resources;

use OpenEmail\Internal\ApiPaths;
use OpenEmail\Internal\Pagination;
use OpenEmail\Result\Page;

final class Threads extends Resource
{
    public function list(
        ?string $folder = null,
        ?string $query = null,
        string|array|null $labelIds = null,
        ?string $sort = null,
        string|\DateTimeInterface|null $dateFrom = null,
        string|\DateTimeInterface|null $dateTo = null,
        ?bool $fromContacts = null,
        ?int $limit = null,
        ?string $cursor = null,
        #[\SensitiveParameter]
        ?string $apiKey = null,
    ): Page {
        return $this->fetchPage(
            ApiPaths::THREADS,
            $this->listQuery($folder, $query, $labelIds, $sort, $dateFrom, $dateTo, $fromContacts),
            self::PAGE_TOKEN,
            $limit,
            $cursor,
            $apiKey,
        );
    }

    public function listAll(
        ?string $folder = null,
        ?string $query = null,
        string|array|null $labelIds = null,
        ?string $sort = null,
        string|\DateTimeInterface|null $dateFrom = null,
        string|\DateTimeInterface|null $dateTo = null,
        ?bool $fromContacts = null,
        ?int $limit = null,
        ?string $cursor = null,
        #[\SensitiveParameter]
        ?string $apiKey = null,
    ): array {
        return $this->collectAll(
            ApiPaths::THREADS,
            $this->listQuery($folder, $query, $labelIds, $sort, $dateFrom, $dateTo, $fromContacts),
            self::PAGE_TOKEN,
            $limit,
            $cursor,
            $apiKey,
        );
    }

    public function iterate(
        ?string $folder = null,
        ?string $query = null,
        string|array|null $labelIds = null,
        ?string $sort = null,
        string|\DateTimeInterface|null $dateFrom = null,
        string|\DateTimeInterface|null $dateTo = null,
        ?bool $fromContacts = null,
        ?int $limit = null,
        ?string $cursor = null,
        #[\SensitiveParameter]
        ?string $apiKey = null,
    ): \Generator {
        return $this->iteratePages(
            ApiPaths::THREADS,
            $this->listQuery($folder, $query, $labelIds, $sort, $dateFrom, $dateTo, $fromContacts),
            self::PAGE_TOKEN,
            $limit,
            $cursor,
            $apiKey,
        );
    }

    public function get(string $id, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call($this->fill(ApiPaths::THREAD, id: $id), apiKey: $apiKey);
    }

    public function update(string $id, array $patch, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call($this->fill(ApiPaths::THREAD, id: $id), 'PATCH', body: $patch, repeatable: true, apiKey: $apiKey);
    }

    public function trash(string $id, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call($this->fill(ApiPaths::THREAD_TRASH, id: $id), 'POST', repeatable: true, apiKey: $apiKey);
    }

    public function snooze(string $id, string|\DateTimeInterface $wakeAt, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call(
            $this->fill(ApiPaths::THREAD_SNOOZE, id: $id),
            'POST',
            body: ['wakeAt' => $this->instant($wakeAt)],
            repeatable: true,
            apiKey: $apiKey,
        );
    }

    public function unsnooze(string $id, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call($this->fill(ApiPaths::THREAD_UNSNOOZE, id: $id), 'POST', repeatable: true, apiKey: $apiKey);
    }

    public function listAttachments(string $id, string $messageId, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->fetchList($this->fill(ApiPaths::THREAD_MESSAGE_ATTACHMENTS, id: $id, messageId: $messageId), apiKey: $apiKey);
    }

    public function listNotes(string $id, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->fetchList($this->fill(ApiPaths::THREAD_NOTES, id: $id), apiKey: $apiKey);
    }

    public function createNote(string $id, array $body, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call($this->fill(ApiPaths::THREAD_NOTES, id: $id), 'POST', body: $body, apiKey: $apiKey);
    }

    public function updateNote(string $id, string $noteId, array $patch, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call($this->fill(ApiPaths::THREAD_NOTE, id: $id, noteId: $noteId), 'PATCH', body: $patch, repeatable: true, apiKey: $apiKey);
    }

    public function deleteNote(string $id, string $noteId, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call($this->fill(ApiPaths::THREAD_NOTE, id: $id, noteId: $noteId), 'DELETE', apiKey: $apiKey);
    }

    public function reorderNotes(string $id, array $ids, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        $envelope = $this->call(
            $this->fill(ApiPaths::THREAD_NOTES_REORDER, id: $id),
            'POST',
            body: ['ids' => array_values($ids)],
            repeatable: true,
            apiKey: $apiKey,
        );

        return Pagination::itemsOf($envelope);
    }

    public function counts(?string $address = null, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call(ApiPaths::THREADS_COUNTS, query: $this->query(address: $address), apiKey: $apiKey);
    }

    public function summary(string $id, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call($this->fill(ApiPaths::THREAD_SUMMARY, id: $id), apiKey: $apiKey);
    }

    public function restore(string $id, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call($this->fill(ApiPaths::THREAD_RESTORE, id: $id), 'POST', repeatable: true, apiKey: $apiKey);
    }

    public function delete(string $id, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call($this->fill(ApiPaths::THREAD, id: $id), 'DELETE', apiKey: $apiKey);
    }

    public function unsubscribe(string $id, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call($this->fill(ApiPaths::THREAD_UNSUBSCRIBE, id: $id), 'POST', apiKey: $apiKey);
    }

    public function getEvent(string $id, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call($this->fill(ApiPaths::THREAD_EVENT, id: $id), apiKey: $apiKey);
    }

    private function listQuery(
        ?string $folder,
        ?string $query,
        string|array|null $labelIds,
        ?string $sort,
        string|\DateTimeInterface|null $dateFrom,
        string|\DateTimeInterface|null $dateTo,
        ?bool $fromContacts,
    ): array {
        return $this->query(
            folder: $folder,
            query: $query,
            labelIds: $this->joined($labelIds),
            sort: $sort,
            dateFrom: $this->instant($dateFrom),
            dateTo: $this->instant($dateTo),
            fromContacts: $this->flag($fromContacts),
        );
    }
}
