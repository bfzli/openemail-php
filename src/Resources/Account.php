<?php

declare(strict_types=1);

namespace OpenEmail\Resources;

use OpenEmail\Internal\ApiPaths;

final class Account extends Resource
{
    public function getNotifications(#[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call(ApiPaths::ACCOUNT_NOTIFICATIONS, apiKey: $apiKey);
    }

    public function setEmailNotification(string $category, array $body, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        $wire = \array_key_exists('enabled', $body) ? ['enabled' => $body['enabled']] : [];

        return $this->call($this->fill(ApiPaths::ACCOUNT_NOTIFICATION_EMAIL, category: $category), 'PUT', body: $wire, repeatable: true, apiKey: $apiKey);
    }

    public function setPushMuted(string $workspaceId, array $body, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        $wire = \array_key_exists('muted', $body) ? ['muted' => $body['muted']] : [];

        return $this->call($this->fill(ApiPaths::ACCOUNT_NOTIFICATION_PUSH, workspaceId: $workspaceId), 'PUT', body: $wire, repeatable: true, apiKey: $apiKey);
    }

    public function setPhoto(mixed $data, ?string $contentType = null, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call(
            ApiPaths::ACCOUNT_PHOTO,
            'PUT',
            raw: $data,
            contentType: $this->rawContentType($data, $contentType),
            repeatable: true,
            apiKey: $apiKey,
        );
    }

    public function removePhoto(#[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call(ApiPaths::ACCOUNT_PHOTO, 'DELETE', repeatable: true, apiKey: $apiKey);
    }

    public function getUsername(#[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call(ApiPaths::ACCOUNT_USERNAME, apiKey: $apiKey);
    }

    public function checkUsername(string $username, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call(ApiPaths::ACCOUNT_USERNAME_AVAILABILITY, query: $this->query(username: $username), apiKey: $apiKey);
    }

    public function setUsername(string $username, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call(ApiPaths::ACCOUNT_USERNAME, 'PUT', body: ['username' => $username], apiKey: $apiKey);
    }

    public function listConnectedApps(#[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->fetchList(ApiPaths::ACCOUNT_CONNECTED_APPS, apiKey: $apiKey);
    }

    public function revokeConnectedApp(string $clientId, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call($this->fill(ApiPaths::ACCOUNT_CONNECTED_APP, clientId: $clientId), 'DELETE', apiKey: $apiKey);
    }

    public function listInvitations(#[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->fetchList(ApiPaths::ACCOUNT_INVITATIONS, apiKey: $apiKey);
    }

    public function acceptInvitation(string $invitationId, ?bool $activate = null, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call(
            $this->fill(ApiPaths::ACCOUNT_INVITATION_ACCEPT, invitationId: $invitationId),
            'POST',
            body: $activate === null ? [] : ['activate' => $activate],
            repeatable: true,
            apiKey: $apiKey,
        );
    }

    public function declineInvitation(string $invitationId, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call($this->fill(ApiPaths::ACCOUNT_INVITATION_DECLINE, invitationId: $invitationId), 'POST', repeatable: true, apiKey: $apiKey);
    }
}
