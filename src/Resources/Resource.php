<?php

declare(strict_types=1);

namespace OpenEmail\Resources;

use OpenEmail\Constants\ErrorTypes;
use OpenEmail\Exception\ApiException;
use OpenEmail\Exception\InvalidArgumentException;
use OpenEmail\Internal\Defaults;
use OpenEmail\Internal\Endpoint;
use OpenEmail\Internal\Messages;
use OpenEmail\Internal\Pagination;
use OpenEmail\Internal\Protocol;
use OpenEmail\Internal\RawBody;
use OpenEmail\Internal\RequestPath;
use OpenEmail\Internal\Wire;
use OpenEmail\Result\Page;
use OpenEmail\Transport;

abstract class Resource
{
    protected const CURSOR = Protocol::CURSOR_STYLES['CURSOR'];

    protected const PAGE_TOKEN = Protocol::CURSOR_STYLES['PAGE_TOKEN'];

    public function __construct(protected readonly Transport $transport) {}

    public function __debugInfo(): array
    {
        return [];
    }

    protected function call(
        string $path,
        string $method = 'GET',
        ?array $query = null,
        #[\SensitiveParameter]
        ?array $body = null,
        #[\SensitiveParameter]
        mixed $raw = null,
        ?string $contentType = null,
        #[\SensitiveParameter]
        ?string $apiKey = null,
        #[\SensitiveParameter]
        ?string $inboxToken = null,
        bool $anonymous = false,
        bool $idempotent = false,
        ?string $idempotencyKey = null,
        ?bool $repeatable = null,
        int|float|null $timeout = null,
    ): array {
        $result = $this->transport->request(
            $path,
            method: $method,
            query: $query,
            body: $body,
            raw: $raw,
            contentType: $contentType,
            apiKey: $apiKey,
            inboxToken: $inboxToken,
            anonymous: $anonymous,
            idempotent: $idempotent,
            idempotencyKey: $idempotencyKey,
            repeatable: $repeatable,
            timeout: $timeout,
        );

        if (\is_array($result)) {
            return $result;
        }

        if ($result === null) {
            return [];
        }

        throw ApiException::create(
            Messages::NOT_JSON_OBJECT,
            null,
            ErrorTypes::API_ERROR,
            Protocol::ERROR_CODES['UNRECOGNISED_RESPONSE'],
            body: $result,
        );
    }

    protected function text(string $path, string $accept, #[\SensitiveParameter] ?string $apiKey = null): string
    {
        $result = $this->transport->request($path, accept: $accept, apiKey: $apiKey);

        return \is_string($result) ? $result : '';
    }

    protected function bytes(string $path, #[\SensitiveParameter] ?string $apiKey = null): string
    {
        $result = $this->transport->request($path, binary: true, apiKey: $apiKey);

        return \is_string($result) ? $result : '';
    }

    protected function fill(string $pattern, mixed ...$params): string
    {
        return RequestPath::fill($pattern, $params);
    }

    protected function query(mixed ...$values): array
    {
        $query = [];

        foreach ($values as $name => $value) {
            $query[self::queryKey((string) $name)] = $value;
        }

        return $query;
    }

    protected function joined(mixed $values): mixed
    {
        return \is_array($values) ? implode(Protocol::LIST_SEPARATOR, array_map(Endpoint::queryText(...), array_values($values))) : $values;
    }

    protected function flag(?bool $value): ?string
    {
        return $value === null ? null : ($value ? 'true' : 'false');
    }

    protected function instant(mixed $value): mixed
    {
        return Wire::instant($value);
    }

    protected function payload(?array $body): array
    {
        return Wire::body($body);
    }

    protected function fetchPage(
        string $path,
        ?array $query = null,
        array $style = self::CURSOR,
        ?int $limit = null,
        ?string $cursor = null,
        #[\SensitiveParameter]
        ?string $apiKey = null,
    ): Page {
        return Pagination::fetchPage($this->transport, $path, $query, $style, $limit, $cursor, $apiKey);
    }

    protected function iteratePages(
        string $path,
        ?array $query = null,
        array $style = self::CURSOR,
        ?int $limit = null,
        ?string $cursor = null,
        #[\SensitiveParameter]
        ?string $apiKey = null,
    ): \Generator {
        return Pagination::walk(
            fn(?string $current): Page => $this->fetchPage($path, $query, $style, $limit, $current, $apiKey),
            $cursor,
        );
    }

    protected function collectAll(
        string $path,
        ?array $query = null,
        array $style = self::CURSOR,
        ?int $limit = null,
        ?string $cursor = null,
        #[\SensitiveParameter]
        ?string $apiKey = null,
    ): array {
        return iterator_to_array($this->iteratePages($path, $query, $style, $limit, $cursor, $apiKey), false);
    }

    protected function fetchList(string $path, ?array $query = null, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return Pagination::fetchList($this->transport, $path, $query, $apiKey);
    }

    protected function rawContentType(mixed $data, ?string $contentType): ?string
    {
        return RawBody::contentType($data, $contentType);
    }

    protected function uploadTimeout(): float
    {
        $base = $this->transport->timeout;

        return $base > 0 ? max($base, (float) Defaults::UPLOAD_TIMEOUT) : $base;
    }

    protected static function uploadName(mixed $data, ?string $filename): string
    {
        $name = trim($filename ?? RawBody::fileName($data) ?? '');

        if ($name === '') {
            throw new InvalidArgumentException(Messages::FILENAME_REQUIRED);
        }

        return $name;
    }

    private static function queryKey(string $name): string
    {
        $constant = strtoupper((string) preg_replace('/(?<!^)[A-Z]/', '_$0', $name));
        $key = Protocol::QUERY_KEYS[$constant] ?? null;

        if (!\is_string($key)) {
            throw new \LogicException(\sprintf(Messages::UNKNOWN_QUERY_KEY, $name));
        }

        return $key;
    }
}
