<?php

declare(strict_types=1);

namespace OpenEmail\Resources;

use OpenEmail\Internal\ApiPaths;
use OpenEmail\Result\Page;

final class Automations extends Resource
{
    public function list(?string $status = null, ?int $limit = null, ?string $cursor = null, #[\SensitiveParameter] ?string $apiKey = null): Page
    {
        return $this->fetchPage(ApiPaths::AUTOMATIONS, $this->query(status: $status), limit: $limit, cursor: $cursor, apiKey: $apiKey);
    }

    public function listAll(?string $status = null, ?int $limit = null, ?string $cursor = null, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->collectAll(ApiPaths::AUTOMATIONS, $this->query(status: $status), limit: $limit, cursor: $cursor, apiKey: $apiKey);
    }

    public function iterate(?string $status = null, ?int $limit = null, ?string $cursor = null, #[\SensitiveParameter] ?string $apiKey = null): \Generator
    {
        return $this->iteratePages(ApiPaths::AUTOMATIONS, $this->query(status: $status), limit: $limit, cursor: $cursor, apiKey: $apiKey);
    }

    public function create(array $body, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call(ApiPaths::AUTOMATIONS, 'POST', body: $body, apiKey: $apiKey);
    }

    public function listStarters(#[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->fetchList(ApiPaths::AUTOMATION_STARTERS, apiKey: $apiKey);
    }

    public function get(string $id, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call($this->fill(ApiPaths::AUTOMATION, id: $id), apiKey: $apiKey);
    }

    public function update(string $id, array $patch, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call($this->fill(ApiPaths::AUTOMATION, id: $id), 'PATCH', body: $patch, repeatable: true, apiKey: $apiKey);
    }

    public function delete(string $id, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call($this->fill(ApiPaths::AUTOMATION, id: $id), 'DELETE', apiKey: $apiKey);
    }

    public function publish(string $id, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call($this->fill(ApiPaths::AUTOMATION_PUBLISH, id: $id), 'POST', repeatable: true, apiKey: $apiKey);
    }

    public function pause(string $id, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call($this->fill(ApiPaths::AUTOMATION_PAUSE, id: $id), 'POST', repeatable: true, apiKey: $apiKey);
    }

    public function resume(string $id, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call($this->fill(ApiPaths::AUTOMATION_RESUME, id: $id), 'POST', repeatable: true, apiKey: $apiKey);
    }

    public function archive(string $id, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call($this->fill(ApiPaths::AUTOMATION_ARCHIVE, id: $id), 'POST', repeatable: true, apiKey: $apiKey);
    }

    public function duplicate(string $id, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call($this->fill(ApiPaths::AUTOMATION_DUPLICATE, id: $id), 'POST', apiKey: $apiKey);
    }

    public function sendTest(string $id, array $body, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call($this->fill(ApiPaths::AUTOMATION_TEST, id: $id), 'POST', body: $body, apiKey: $apiKey);
    }

    public function listVersions(string $id, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->fetchList($this->fill(ApiPaths::AUTOMATION_VERSIONS, id: $id), apiKey: $apiKey);
    }

    public function restoreVersion(string $id, int $version, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call($this->fill(ApiPaths::AUTOMATION_VERSION_RESTORE, id: $id, version: $version), 'POST', repeatable: true, apiKey: $apiKey);
    }

    public function stats(
        string $id,
        string|\DateTimeInterface|null $since = null,
        string|\DateTimeInterface|null $until = null,
        #[\SensitiveParameter]
        ?string $apiKey = null,
    ): array {
        return $this->call($this->fill(ApiPaths::AUTOMATION_STATS, id: $id), query: $this->windowQuery($since, $until), apiKey: $apiKey);
    }

    public function listEnrollments(
        string $id,
        ?string $status = null,
        ?string $stepKey = null,
        ?string $q = null,
        ?int $limit = null,
        ?string $cursor = null,
        #[\SensitiveParameter]
        ?string $apiKey = null,
    ): Page {
        return $this->fetchPage($this->fill(ApiPaths::AUTOMATION_ENROLLMENTS, id: $id), $this->query(status: $status, stepKey: $stepKey, q: $q), limit: $limit, cursor: $cursor, apiKey: $apiKey);
    }

    public function listAllEnrollments(
        string $id,
        ?string $status = null,
        ?string $stepKey = null,
        ?string $q = null,
        ?int $limit = null,
        ?string $cursor = null,
        #[\SensitiveParameter]
        ?string $apiKey = null,
    ): array {
        return $this->collectAll($this->fill(ApiPaths::AUTOMATION_ENROLLMENTS, id: $id), $this->query(status: $status, stepKey: $stepKey, q: $q), limit: $limit, cursor: $cursor, apiKey: $apiKey);
    }

    public function iterateEnrollments(
        string $id,
        ?string $status = null,
        ?string $stepKey = null,
        ?string $q = null,
        ?int $limit = null,
        ?string $cursor = null,
        #[\SensitiveParameter]
        ?string $apiKey = null,
    ): \Generator {
        return $this->iteratePages($this->fill(ApiPaths::AUTOMATION_ENROLLMENTS, id: $id), $this->query(status: $status, stepKey: $stepKey, q: $q), limit: $limit, cursor: $cursor, apiKey: $apiKey);
    }

    public function getEnrollment(string $id, string $enrollmentId, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call($this->fill(ApiPaths::AUTOMATION_ENROLLMENT, id: $id, enrollmentId: $enrollmentId), apiKey: $apiKey);
    }

    public function enroll(string $id, array $body, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call($this->fill(ApiPaths::AUTOMATION_ENROLLMENTS, id: $id), 'POST', body: $body, apiKey: $apiKey);
    }

    public function exitEnrollment(string $id, string $enrollmentId, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call($this->fill(ApiPaths::AUTOMATION_ENROLLMENT_EXIT, id: $id, enrollmentId: $enrollmentId), 'POST', apiKey: $apiKey);
    }
}
