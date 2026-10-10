<?php

declare(strict_types=1);

namespace OpenEmail\Resources;

use OpenEmail\Internal\ApiPaths;
use OpenEmail\Internal\Pagination;
use OpenEmail\Internal\Wire;
use OpenEmail\Result\Page;
use OpenEmail\Result\TemplateSends;

final class Templates extends Resource
{
    private const VALUE_MAPS = ['props', 'slots'];

    public function list(
        ?string $status = null,
        ?string $search = null,
        ?string $sort = null,
        ?int $limit = null,
        ?string $cursor = null,
        #[\SensitiveParameter]
        ?string $apiKey = null,
    ): Page {
        return $this->fetchPage(ApiPaths::TEMPLATES, $this->query(status: $status, search: $search, sort: $sort), limit: $limit, cursor: $cursor, apiKey: $apiKey);
    }

    public function listAll(
        ?string $status = null,
        ?string $search = null,
        ?string $sort = null,
        ?int $limit = null,
        ?string $cursor = null,
        #[\SensitiveParameter]
        ?string $apiKey = null,
    ): array {
        return $this->collectAll(ApiPaths::TEMPLATES, $this->query(status: $status, search: $search, sort: $sort), limit: $limit, cursor: $cursor, apiKey: $apiKey);
    }

    public function iterate(
        ?string $status = null,
        ?string $search = null,
        ?string $sort = null,
        ?int $limit = null,
        ?string $cursor = null,
        #[\SensitiveParameter]
        ?string $apiKey = null,
    ): \Generator {
        return $this->iteratePages(ApiPaths::TEMPLATES, $this->query(status: $status, search: $search, sort: $sort), limit: $limit, cursor: $cursor, apiKey: $apiKey);
    }

    public function get(string $idOrSlug, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call($this->fill(ApiPaths::TEMPLATE, id: $idOrSlug), apiKey: $apiKey);
    }

    public function create(array $body, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call(ApiPaths::TEMPLATES, 'POST', body: $body, apiKey: $apiKey);
    }

    public function update(string $idOrSlug, array $patch, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call($this->fill(ApiPaths::TEMPLATE, id: $idOrSlug), 'PATCH', body: $patch, apiKey: $apiKey);
    }

    public function duplicate(string $idOrSlug, ?array $body = null, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call($this->fill(ApiPaths::TEMPLATE_DUPLICATE, id: $idOrSlug), 'POST', body: $this->payload($body), apiKey: $apiKey);
    }

    public function replaceContent(string $idOrSlug, array $body, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call($this->fill(ApiPaths::TEMPLATE_CONTENT, id: $idOrSlug), 'POST', body: $body, apiKey: $apiKey);
    }

    public function delete(string $idOrSlug, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call($this->fill(ApiPaths::TEMPLATE, id: $idOrSlug), 'DELETE', apiKey: $apiKey);
    }

    public function listVersions(string $idOrSlug, ?int $limit = null, ?string $cursor = null, #[\SensitiveParameter] ?string $apiKey = null): Page
    {
        return $this->fetchPage($this->fill(ApiPaths::TEMPLATE_VERSIONS, id: $idOrSlug), limit: $limit, cursor: $cursor, apiKey: $apiKey);
    }

    public function listAllVersions(string $idOrSlug, ?int $limit = null, ?string $cursor = null, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->collectAll($this->fill(ApiPaths::TEMPLATE_VERSIONS, id: $idOrSlug), limit: $limit, cursor: $cursor, apiKey: $apiKey);
    }

    public function iterateVersions(string $idOrSlug, ?int $limit = null, ?string $cursor = null, #[\SensitiveParameter] ?string $apiKey = null): \Generator
    {
        return $this->iteratePages($this->fill(ApiPaths::TEMPLATE_VERSIONS, id: $idOrSlug), limit: $limit, cursor: $cursor, apiKey: $apiKey);
    }

    public function getVersion(string $idOrSlug, int $version, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call($this->fill(ApiPaths::TEMPLATE_VERSION, id: $idOrSlug, version: $version), apiKey: $apiKey);
    }

    public function publish(string $idOrSlug, ?int $expectedVersion = null, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call(
            $this->fill(ApiPaths::TEMPLATE_VERSIONS, id: $idOrSlug),
            'POST',
            body: $expectedVersion === null ? null : ['expectedVersion' => $expectedVersion],
            repeatable: true,
            apiKey: $apiKey,
        );
    }

    public function restoreVersion(string $idOrSlug, int $version, ?array $body = null, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call($this->fill(ApiPaths::TEMPLATE_VERSION_RESTORE, id: $idOrSlug, version: $version), 'POST', body: $this->payload($body), apiKey: $apiKey);
    }

    public function deleteVersion(string $idOrSlug, int $version, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call($this->fill(ApiPaths::TEMPLATE_VERSION, id: $idOrSlug, version: $version), 'DELETE', apiKey: $apiKey);
    }

    public function listStarters(#[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->fetchList(ApiPaths::TEMPLATE_STARTERS, apiKey: $apiKey);
    }

    public function getStarter(string $slug, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call($this->fill(ApiPaths::TEMPLATE_STARTER, slug: $slug), apiKey: $apiKey);
    }

    public function listFonts(#[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->fetchList(ApiPaths::TEMPLATE_FONTS, apiKey: $apiKey);
    }

    public function render(array $body, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call(ApiPaths::TEMPLATE_RENDER, 'POST', body: $body, repeatable: true, apiKey: $apiKey);
    }

    public function preview(string $idOrSlug, ?array $body = null, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call($this->fill(ApiPaths::TEMPLATE_PREVIEW, id: $idOrSlug), 'POST', body: Wire::withObjects($this->payload($body), self::VALUE_MAPS), repeatable: true, apiKey: $apiKey);
    }

    public function getAnalytics(
        string $idOrSlug,
        ?int $days = null,
        ?int $minutes = null,
        ?string $grain = null,
        ?int $offsetMinutes = null,
        #[\SensitiveParameter]
        ?string $apiKey = null,
    ): array {
        return $this->call(
            $this->fill(ApiPaths::TEMPLATE_ANALYTICS, id: $idOrSlug),
            query: $this->query(days: $days, minutes: $minutes, grain: $grain, offsetMinutes: $offsetMinutes),
            apiKey: $apiKey,
        );
    }

    public function listSends(
        string $idOrSlug,
        ?int $page = null,
        ?int $pageSize = null,
        ?string $search = null,
        ?string $source = null,
        ?int $version = null,
        ?bool $opened = null,
        ?bool $clicked = null,
        ?bool $tracked = null,
        ?int $days = null,
        ?int $minutes = null,
        ?string $grain = null,
        ?int $offsetMinutes = null,
        #[\SensitiveParameter]
        ?string $apiKey = null,
    ): TemplateSends {
        $query = $this->query(
            days: $days,
            minutes: $minutes,
            grain: $grain,
            offsetMinutes: $offsetMinutes,
            page: $page,
            pageSize: $pageSize,
            search: $search,
            source: $source,
            version: $version,
            opened: $this->flag($opened),
            clicked: $this->flag($clicked),
            tracked: $this->flag($tracked),
        );
        $envelope = $this->call($this->fill(ApiPaths::TEMPLATE_SENDS, id: $idOrSlug), query: $query, apiKey: $apiKey);
        $total = $envelope['total'] ?? null;
        $current = $envelope['page'] ?? null;
        $size = $envelope['pageSize'] ?? null;

        return new TemplateSends(
            Pagination::itemsOf($envelope),
            \is_int($total) ? $total : 0,
            \is_int($current) ? $current : 1,
            \is_int($size) ? $size : 0,
        );
    }

    public function send(string $idOrSlug, array $body, ?string $idempotencyKey = null, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call(
            $this->fill(ApiPaths::TEMPLATE_SEND, id: $idOrSlug),
            'POST',
            body: Wire::withObjects(Wire::recipients($body), self::VALUE_MAPS),
            idempotent: true,
            idempotencyKey: $idempotencyKey,
            repeatable: true,
            apiKey: $apiKey,
        );
    }

    public function listImages(?int $limit = null, ?string $cursor = null, #[\SensitiveParameter] ?string $apiKey = null): Page
    {
        return $this->fetchPage(ApiPaths::TEMPLATE_IMAGES, limit: $limit, cursor: $cursor, apiKey: $apiKey);
    }

    public function listAllImages(?int $limit = null, ?string $cursor = null, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->collectAll(ApiPaths::TEMPLATE_IMAGES, limit: $limit, cursor: $cursor, apiKey: $apiKey);
    }

    public function iterateImages(?int $limit = null, ?string $cursor = null, #[\SensitiveParameter] ?string $apiKey = null): \Generator
    {
        return $this->iteratePages(ApiPaths::TEMPLATE_IMAGES, limit: $limit, cursor: $cursor, apiKey: $apiKey);
    }

    public function uploadImage(mixed $data, ?string $contentType = null, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call(ApiPaths::TEMPLATE_IMAGES, 'POST', raw: $data, contentType: $this->rawContentType($data, $contentType), apiKey: $apiKey);
    }

    public function design(array $body, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call(ApiPaths::TEMPLATES_DESIGN, 'POST', body: $body, apiKey: $apiKey);
    }

    public function redesign(string $idOrSlug, array $body, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call($this->fill(ApiPaths::TEMPLATE_REDESIGN, id: $idOrSlug), 'POST', body: $body, apiKey: $apiKey);
    }
}
