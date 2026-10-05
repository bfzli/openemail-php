<?php

declare(strict_types=1);

namespace OpenEmail\Resources;

use OpenEmail\Internal\ApiPaths;
use OpenEmail\Internal\Pagination;
use OpenEmail\Result\AddressBook;
use OpenEmail\Result\AddressBookPage;

final class Addresses extends Resource
{
    public function list(?int $limit = null, ?string $cursor = null, #[\SensitiveParameter] ?string $apiKey = null): AddressBookPage
    {
        $payload = $this->call(ApiPaths::ADDRESSES, query: Pagination::pageQuery(null, self::CURSOR, $limit, $cursor), apiKey: $apiKey);
        $nextCursor = Pagination::cursorOf($payload['nextCursor'] ?? null);
        $unrestricted = $payload['unrestricted'] ?? null;
        $domains = $payload['domains'] ?? null;

        return new AddressBookPage(
            \is_bool($unrestricted) ? $unrestricted : null,
            Pagination::itemsOf($payload),
            \is_array($domains) && array_is_list($domains) ? $domains : [],
            Pagination::hasMore($payload, $nextCursor),
            $nextCursor,
        );
    }

    public function listAll(?int $limit = null, ?string $cursor = null, #[\SensitiveParameter] ?string $apiKey = null): AddressBook
    {
        $current = $this->list($limit, $cursor, $apiKey);
        $addresses = $current->addresses;
        $followed = $cursor === null ? [] : [$cursor => true];

        while ($current->hasMore && $current->nextCursor !== null && !isset($followed[$current->nextCursor])) {
            $cursor = $current->nextCursor;
            $followed[$cursor] = true;
            $current = $this->list($limit, $cursor, $apiKey);
            array_push($addresses, ...$current->addresses);
        }

        return new AddressBook($current->unrestricted, $addresses, $current->domains);
    }

    public function iterate(?int $limit = null, ?string $cursor = null, #[\SensitiveParameter] ?string $apiKey = null): \Generator
    {
        return $this->iteratePages(ApiPaths::ADDRESSES, limit: $limit, cursor: $cursor, apiKey: $apiKey);
    }
}
