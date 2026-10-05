<?php

declare(strict_types=1);

namespace OpenEmail\Internal;

use OpenEmail\Result\AddressBookPage;
use OpenEmail\Result\Page;
use OpenEmail\Result\PeoplePage;
use OpenEmail\Result\TempMessagesPage;
use OpenEmail\Transport;

final class Pagination
{
    public static function fetchPage(
        Transport $transport,
        string $path,
        ?array $query,
        array $style,
        ?int $limit,
        ?string $cursor,
        #[\SensitiveParameter]
        ?string $apiKey,
    ): Page {
        $payload = $transport->request($path, query: self::pageQuery($query, $style, $limit, $cursor), apiKey: $apiKey);
        $envelope = \is_array($payload) ? $payload : [];
        $field = \is_string($style['FIELD'] ?? null) ? $style['FIELD'] : Protocol::CURSOR_STYLES['CURSOR']['FIELD'];
        $nextCursor = self::cursorOf($envelope[$field] ?? null);

        return new Page(self::itemsOf($envelope), self::hasMore($envelope, $nextCursor), $nextCursor);
    }

    public static function walk(\Closure $fetch, ?string $cursor): \Generator
    {
        $current = $cursor;
        $followed = $cursor === null ? [] : [$cursor => true];

        while (true) {
            $page = $fetch($current);

            foreach (self::itemsFor($page) as $item) {
                yield $item;
            }

            $next = self::nextFor($page);

            if (!self::moreFor($page) || $next === null || isset($followed[$next])) {
                return;
            }

            $followed[$next] = true;
            $current = $next;
        }
    }

    private static function itemsFor(mixed $page): array
    {
        return match (true) {
            $page instanceof Page, $page instanceof PeoplePage, $page instanceof TempMessagesPage => $page->items,
            $page instanceof AddressBookPage => $page->addresses,
            default => [],
        };
    }

    private static function moreFor(mixed $page): bool
    {
        return ($page instanceof Page || $page instanceof PeoplePage || $page instanceof TempMessagesPage || $page instanceof AddressBookPage)
            && $page->hasMore;
    }

    private static function nextFor(mixed $page): ?string
    {
        return $page instanceof Page || $page instanceof PeoplePage || $page instanceof TempMessagesPage || $page instanceof AddressBookPage
            ? $page->nextCursor
            : null;
    }

    public static function fetchList(Transport $transport, string $path, ?array $query, #[\SensitiveParameter] ?string $apiKey): array
    {
        $payload = $transport->request($path, query: $query, apiKey: $apiKey);

        return self::itemsOf(\is_array($payload) ? $payload : []);
    }

    public static function pageQuery(?array $query, array $style, ?int $limit, ?string $cursor): array
    {
        $param = \is_string($style['PARAM'] ?? null) ? $style['PARAM'] : Protocol::CURSOR_STYLES['CURSOR']['PARAM'];
        $merged = $query ?? [];
        $merged[Protocol::QUERY_KEYS['LIMIT']] = $limit;
        $merged[$param] = $cursor;

        return $merged;
    }

    public static function itemsOf(array $envelope): array
    {
        $data = $envelope['data'] ?? null;

        return \is_array($data) && array_is_list($data) ? $data : [];
    }

    public static function hasMore(array $envelope, ?string $nextCursor): bool
    {
        $flag = $envelope['hasMore'] ?? null;

        return \is_bool($flag) ? $flag : $nextCursor !== null;
    }

    public static function cursorOf(mixed $value): ?string
    {
        return \is_string($value) && $value !== '' ? $value : null;
    }
}
