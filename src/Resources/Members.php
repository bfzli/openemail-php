<?php

declare(strict_types=1);

namespace OpenEmail\Resources;

use OpenEmail\Internal\ApiPaths;
use OpenEmail\Result\Page;

final class Members extends Resource
{
    public function list(?int $limit = null, ?string $cursor = null, #[\SensitiveParameter] ?string $apiKey = null): Page
    {
        return $this->fetchPage(ApiPaths::MEMBERS, limit: $limit, cursor: $cursor, apiKey: $apiKey);
    }

    public function listAll(?int $limit = null, ?string $cursor = null, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->collectAll(ApiPaths::MEMBERS, limit: $limit, cursor: $cursor, apiKey: $apiKey);
    }

    public function iterate(?int $limit = null, ?string $cursor = null, #[\SensitiveParameter] ?string $apiKey = null): \Generator
    {
        return $this->iteratePages(ApiPaths::MEMBERS, limit: $limit, cursor: $cursor, apiKey: $apiKey);
    }

    public function get(string $userId, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call($this->fill(ApiPaths::MEMBER, userId: $userId), apiKey: $apiKey);
    }

    public function add(array $body, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call(ApiPaths::MEMBERS, 'POST', body: $body, apiKey: $apiKey);
    }

    public function update(string $userId, array $patch, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call($this->fill(ApiPaths::MEMBER, userId: $userId), 'PATCH', body: $patch, repeatable: true, apiKey: $apiKey);
    }

    public function remove(string $userId, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call($this->fill(ApiPaths::MEMBER, userId: $userId), 'DELETE', apiKey: $apiKey);
    }

    public function grantAddress(string $userId, array $body, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call($this->fill(ApiPaths::MEMBER_ADDRESSES, userId: $userId), 'POST', body: $body, repeatable: true, apiKey: $apiKey);
    }

    public function revokeAddress(string $userId, string $addressId, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call($this->fill(ApiPaths::MEMBER_ADDRESS, userId: $userId, addressId: $addressId), 'DELETE', apiKey: $apiKey);
    }

    public function grantDomain(string $userId, array $body, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call($this->fill(ApiPaths::MEMBER_DOMAINS, userId: $userId), 'POST', body: $body, repeatable: true, apiKey: $apiKey);
    }

    public function revokeDomain(string $userId, string $domainId, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call($this->fill(ApiPaths::MEMBER_DOMAIN, userId: $userId, domainId: $domainId), 'DELETE', apiKey: $apiKey);
    }

    public function listInvitations(?int $limit = null, ?string $cursor = null, #[\SensitiveParameter] ?string $apiKey = null): Page
    {
        return $this->fetchPage(ApiPaths::MEMBER_INVITATIONS, limit: $limit, cursor: $cursor, apiKey: $apiKey);
    }

    public function listAllInvitations(?int $limit = null, ?string $cursor = null, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->collectAll(ApiPaths::MEMBER_INVITATIONS, limit: $limit, cursor: $cursor, apiKey: $apiKey);
    }

    public function iterateInvitations(?int $limit = null, ?string $cursor = null, #[\SensitiveParameter] ?string $apiKey = null): \Generator
    {
        return $this->iteratePages(ApiPaths::MEMBER_INVITATIONS, limit: $limit, cursor: $cursor, apiKey: $apiKey);
    }

    public function revokeInvitation(string $invitationId, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call($this->fill(ApiPaths::MEMBER_INVITATION, invitationId: $invitationId), 'DELETE', apiKey: $apiKey);
    }

    public function resendInvitation(string $invitationId, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call($this->fill(ApiPaths::MEMBER_INVITATION_RESEND, invitationId: $invitationId), 'POST', apiKey: $apiKey);
    }
}
