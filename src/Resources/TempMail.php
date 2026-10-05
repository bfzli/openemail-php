<?php

declare(strict_types=1);

namespace OpenEmail\Resources;

use OpenEmail\Internal\ApiPaths;
use OpenEmail\Internal\Pagination;
use OpenEmail\Result\TempMessagesPage;

final class TempMail extends Resource
{
    public function listDomains(#[\SensitiveParameter] ?string $inboxToken = null): array
    {
        $envelope = $this->call(ApiPaths::TEMP_MAIL_DOMAINS, anonymous: true);

        return Pagination::itemsOf($envelope);
    }

    public function create(?array $body = null, #[\SensitiveParameter] ?string $inboxToken = null): array
    {
        return $this->call(ApiPaths::TEMP_MAIL_INBOXES, 'POST', body: $this->payload($body), anonymous: true);
    }

    public function get(string $inboxId, #[\SensitiveParameter] ?string $inboxToken = null): array
    {
        return $this->call($this->fill(ApiPaths::TEMP_MAIL_INBOX, id: $inboxId), inboxToken: $inboxToken);
    }

    public function extend(string $inboxId, #[\SensitiveParameter] ?string $inboxToken = null): array
    {
        return $this->call($this->fill(ApiPaths::TEMP_MAIL_INBOX_EXTEND, id: $inboxId), 'POST', inboxToken: $inboxToken);
    }

    public function delete(string $inboxId, #[\SensitiveParameter] ?string $inboxToken = null): array
    {
        return $this->call($this->fill(ApiPaths::TEMP_MAIL_INBOX, id: $inboxId), 'DELETE', inboxToken: $inboxToken);
    }

    public function listMessages(string $inboxId, ?int $limit = null, ?string $cursor = null, #[\SensitiveParameter] ?string $inboxToken = null): TempMessagesPage
    {
        $envelope = $this->call(
            $this->fill(ApiPaths::TEMP_MAIL_MESSAGES, id: $inboxId),
            query: $this->query(limit: $limit, cursor: $cursor),
            inboxToken: $inboxToken,
        );
        $nextCursor = Pagination::cursorOf($envelope['nextCursor'] ?? null);
        $expiresAt = $envelope['expiresAt'] ?? null;

        return new TempMessagesPage(
            Pagination::itemsOf($envelope),
            Pagination::hasMore($envelope, $nextCursor),
            $nextCursor,
            \is_string($expiresAt) ? $expiresAt : null,
        );
    }

    public function listAllMessages(string $inboxId, ?int $limit = null, ?string $cursor = null, #[\SensitiveParameter] ?string $inboxToken = null): array
    {
        return iterator_to_array($this->iterateMessages($inboxId, $limit, $cursor, $inboxToken), false);
    }

    public function iterateMessages(string $inboxId, ?int $limit = null, ?string $cursor = null, #[\SensitiveParameter] ?string $inboxToken = null): \Generator
    {
        $this->fill(ApiPaths::TEMP_MAIL_MESSAGES, id: $inboxId);

        return Pagination::walk(
            fn(?string $current): TempMessagesPage => $this->listMessages($inboxId, $limit, $current, $inboxToken),
            $cursor,
        );
    }

    public function getMessage(string $inboxId, string $messageId, #[\SensitiveParameter] ?string $inboxToken = null): array
    {
        return $this->call($this->fill(ApiPaths::TEMP_MAIL_MESSAGE, id: $inboxId, messageId: $messageId), inboxToken: $inboxToken);
    }

    public function deleteMessage(string $inboxId, string $messageId, #[\SensitiveParameter] ?string $inboxToken = null): array
    {
        return $this->call($this->fill(ApiPaths::TEMP_MAIL_MESSAGE, id: $inboxId, messageId: $messageId), 'DELETE', inboxToken: $inboxToken);
    }

    public function listAttachments(string $inboxId, string $messageId, #[\SensitiveParameter] ?string $inboxToken = null): array
    {
        $envelope = $this->call($this->fill(ApiPaths::TEMP_MAIL_MESSAGE_ATTACHMENTS, id: $inboxId, messageId: $messageId), inboxToken: $inboxToken);

        return Pagination::itemsOf($envelope);
    }
}
