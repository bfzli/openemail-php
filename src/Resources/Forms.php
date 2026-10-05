<?php

declare(strict_types=1);

namespace OpenEmail\Resources;

use OpenEmail\Internal\ApiPaths;
use OpenEmail\Result\Page;

final class Forms extends Resource
{
    public function list(?int $limit = null, ?string $cursor = null, #[\SensitiveParameter] ?string $apiKey = null): Page
    {
        return $this->fetchPage(ApiPaths::FORMS, limit: $limit, cursor: $cursor, apiKey: $apiKey);
    }

    public function listAll(?int $limit = null, ?string $cursor = null, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->collectAll(ApiPaths::FORMS, limit: $limit, cursor: $cursor, apiKey: $apiKey);
    }

    public function iterate(?int $limit = null, ?string $cursor = null, #[\SensitiveParameter] ?string $apiKey = null): \Generator
    {
        return $this->iteratePages(ApiPaths::FORMS, limit: $limit, cursor: $cursor, apiKey: $apiKey);
    }

    public function create(array $body, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call(ApiPaths::FORMS, 'POST', body: $body, apiKey: $apiKey);
    }

    public function design(array $body, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call(ApiPaths::FORMS_DESIGN, 'POST', body: $body, apiKey: $apiKey);
    }

    public function redesign(string $id, array $body, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call($this->fill(ApiPaths::FORM_REDESIGN, id: $id), 'POST', body: $body, apiKey: $apiKey);
    }

    public function listStarters(#[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->fetchList(ApiPaths::FORM_STARTERS, apiKey: $apiKey);
    }

    public function getStarter(string $slug, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call($this->fill(ApiPaths::FORM_STARTER, slug: $slug), apiKey: $apiKey);
    }

    public function get(string $id, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call($this->fill(ApiPaths::FORM, id: $id), apiKey: $apiKey);
    }

    public function update(string $id, array $patch, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call($this->fill(ApiPaths::FORM, id: $id), 'PATCH', body: $patch, repeatable: true, apiKey: $apiKey);
    }

    public function delete(string $id, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call($this->fill(ApiPaths::FORM, id: $id), 'DELETE', apiKey: $apiKey);
    }

    public function publish(string $id, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call($this->fill(ApiPaths::FORM_PUBLISH, id: $id), 'POST', repeatable: true, apiKey: $apiKey);
    }

    public function pause(string $id, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call($this->fill(ApiPaths::FORM_PAUSE, id: $id), 'POST', repeatable: true, apiKey: $apiKey);
    }

    public function resume(string $id, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call($this->fill(ApiPaths::FORM_RESUME, id: $id), 'POST', repeatable: true, apiKey: $apiKey);
    }

    public function duplicate(string $id, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call($this->fill(ApiPaths::FORM_DUPLICATE, id: $id), 'POST', apiKey: $apiKey);
    }

    public function analytics(
        string $id,
        ?int $days = null,
        ?int $minutes = null,
        ?string $grain = null,
        ?int $offsetMinutes = null,
        #[\SensitiveParameter]
        ?string $apiKey = null,
    ): array {
        return $this->call(
            $this->fill(ApiPaths::FORM_ANALYTICS, id: $id),
            query: $this->query(days: $days, minutes: $minutes, grain: $grain, offsetMinutes: $offsetMinutes),
            apiKey: $apiKey,
        );
    }

    public function listSubmissions(string $id, ?string $q = null, ?string $status = null, ?int $limit = null, ?string $cursor = null, #[\SensitiveParameter] ?string $apiKey = null): Page
    {
        return $this->fetchPage($this->fill(ApiPaths::FORM_SUBMISSIONS, id: $id), $this->query(q: $q, status: $status), limit: $limit, cursor: $cursor, apiKey: $apiKey);
    }

    public function listAllSubmissions(string $id, ?string $q = null, ?string $status = null, ?int $limit = null, ?string $cursor = null, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->collectAll($this->fill(ApiPaths::FORM_SUBMISSIONS, id: $id), $this->query(q: $q, status: $status), limit: $limit, cursor: $cursor, apiKey: $apiKey);
    }

    public function iterateSubmissions(string $id, ?string $q = null, ?string $status = null, ?int $limit = null, ?string $cursor = null, #[\SensitiveParameter] ?string $apiKey = null): \Generator
    {
        return $this->iteratePages($this->fill(ApiPaths::FORM_SUBMISSIONS, id: $id), $this->query(q: $q, status: $status), limit: $limit, cursor: $cursor, apiKey: $apiKey);
    }

    public function getSubmission(string $id, string $submissionId, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call($this->fill(ApiPaths::FORM_SUBMISSION, id: $id, submissionId: $submissionId), apiKey: $apiKey);
    }

    public function deleteSubmission(string $id, string $submissionId, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call($this->fill(ApiPaths::FORM_SUBMISSION, id: $id, submissionId: $submissionId), 'DELETE', apiKey: $apiKey);
    }

    public function deleteSubmissions(string $id, array $submissionIds, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call($this->fill(ApiPaths::FORM_SUBMISSIONS_BATCH_REMOVE, id: $id), 'POST', body: ['ids' => array_values($submissionIds)], apiKey: $apiKey);
    }

    public function approveSubmission(string $id, string $submissionId, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call($this->fill(ApiPaths::FORM_SUBMISSION_APPROVE, id: $id, submissionId: $submissionId), 'POST', repeatable: true, apiKey: $apiKey);
    }

    public function resendConfirmation(string $id, string $submissionId, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call($this->fill(ApiPaths::FORM_SUBMISSION_RESEND, id: $id, submissionId: $submissionId), 'POST', apiKey: $apiKey);
    }

    public function subscribe(string $formId, array $values, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call($this->fill(ApiPaths::SUBSCRIBE_FORM, formId: $formId), 'POST', body: $values, apiKey: $apiKey, anonymous: true);
    }
}
