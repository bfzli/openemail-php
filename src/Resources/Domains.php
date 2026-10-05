<?php

declare(strict_types=1);

namespace OpenEmail\Resources;

use OpenEmail\Internal\ApiPaths;
use OpenEmail\Result\Page;

final class Domains extends Resource
{
    public function list(?int $limit = null, ?string $cursor = null, #[\SensitiveParameter] ?string $apiKey = null): Page
    {
        return $this->fetchPage(ApiPaths::DOMAINS, limit: $limit, cursor: $cursor, apiKey: $apiKey);
    }

    public function listAll(?int $limit = null, ?string $cursor = null, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->collectAll(ApiPaths::DOMAINS, limit: $limit, cursor: $cursor, apiKey: $apiKey);
    }

    public function iterate(?int $limit = null, ?string $cursor = null, #[\SensitiveParameter] ?string $apiKey = null): \Generator
    {
        return $this->iteratePages(ApiPaths::DOMAINS, limit: $limit, cursor: $cursor, apiKey: $apiKey);
    }

    public function get(string $id, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call($this->fill(ApiPaths::DOMAIN, id: $id), apiKey: $apiKey);
    }

    public function create(array $body, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call(ApiPaths::DOMAINS, 'POST', body: $body, apiKey: $apiKey);
    }

    public function verify(string $id, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call($this->fill(ApiPaths::DOMAIN_VERIFY, id: $id), 'POST', repeatable: true, apiKey: $apiKey);
    }

    public function update(string $id, array $patch, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call($this->fill(ApiPaths::DOMAIN, id: $id), 'PATCH', body: $patch, repeatable: true, apiKey: $apiKey);
    }

    public function delete(string $id, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call($this->fill(ApiPaths::DOMAIN, id: $id), 'DELETE', apiKey: $apiKey);
    }

    public function getLogo(string $id, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call($this->fill(ApiPaths::DOMAIN_LOGO, id: $id), apiKey: $apiKey);
    }

    public function setLogo(string $id, array $body, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call($this->fill(ApiPaths::DOMAIN_LOGO, id: $id), 'PUT', body: $body, repeatable: true, apiKey: $apiKey);
    }

    public function removeLogo(string $id, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call($this->fill(ApiPaths::DOMAIN_LOGO, id: $id), 'DELETE', repeatable: true, apiKey: $apiKey);
    }

    public function setLogoCertificate(string $id, array $body, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call($this->fill(ApiPaths::DOMAIN_LOGO_CERTIFICATE, id: $id), 'PUT', body: $body, repeatable: true, apiKey: $apiKey);
    }

    public function removeLogoCertificate(string $id, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call($this->fill(ApiPaths::DOMAIN_LOGO_CERTIFICATE, id: $id), 'DELETE', repeatable: true, apiKey: $apiKey);
    }

    public function listAddresses(string $id, ?int $limit = null, ?string $cursor = null, #[\SensitiveParameter] ?string $apiKey = null): Page
    {
        return $this->fetchPage($this->fill(ApiPaths::DOMAIN_ADDRESSES, id: $id), limit: $limit, cursor: $cursor, apiKey: $apiKey);
    }

    public function listAllAddresses(string $id, ?int $limit = null, ?string $cursor = null, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->collectAll($this->fill(ApiPaths::DOMAIN_ADDRESSES, id: $id), limit: $limit, cursor: $cursor, apiKey: $apiKey);
    }

    public function iterateAddresses(string $id, ?int $limit = null, ?string $cursor = null, #[\SensitiveParameter] ?string $apiKey = null): \Generator
    {
        return $this->iteratePages($this->fill(ApiPaths::DOMAIN_ADDRESSES, id: $id), limit: $limit, cursor: $cursor, apiKey: $apiKey);
    }

    public function createAddress(string $id, array $body, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call($this->fill(ApiPaths::DOMAIN_ADDRESSES, id: $id), 'POST', body: $body, repeatable: true, apiKey: $apiKey);
    }

    public function getAddress(string $id, string $addressId, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call($this->fill(ApiPaths::DOMAIN_ADDRESS, id: $id, addressId: $addressId), apiKey: $apiKey);
    }

    public function updateAddress(string $id, string $addressId, array $patch, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call($this->fill(ApiPaths::DOMAIN_ADDRESS, id: $id, addressId: $addressId), 'PATCH', body: $patch, repeatable: true, apiKey: $apiKey);
    }

    public function setAddressPhoto(string $id, string $addressId, mixed $data, ?string $contentType = null, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call(
            $this->fill(ApiPaths::DOMAIN_ADDRESS_PHOTO, id: $id, addressId: $addressId),
            'PUT',
            raw: $data,
            contentType: $this->rawContentType($data, $contentType),
            repeatable: true,
            apiKey: $apiKey,
        );
    }

    public function removeAddressPhoto(string $id, string $addressId, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call($this->fill(ApiPaths::DOMAIN_ADDRESS_PHOTO, id: $id, addressId: $addressId), 'DELETE', repeatable: true, apiKey: $apiKey);
    }

    public function deleteAddress(string $id, string $addressId, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call($this->fill(ApiPaths::DOMAIN_ADDRESS, id: $id, addressId: $addressId), 'DELETE', apiKey: $apiKey);
    }

    public function listAddressForwards(string $id, string $addressId, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call($this->fill(ApiPaths::DOMAIN_ADDRESS_FORWARDS, id: $id, addressId: $addressId), apiKey: $apiKey);
    }

    public function addAddressForwards(string $id, string $addressId, array $body, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call($this->fill(ApiPaths::DOMAIN_ADDRESS_FORWARDS, id: $id, addressId: $addressId), 'POST', body: $body, apiKey: $apiKey);
    }

    public function updateAddressForward(string $id, string $addressId, string $forwardId, array $patch, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call(
            $this->fill(ApiPaths::DOMAIN_ADDRESS_FORWARD, id: $id, addressId: $addressId, forwardId: $forwardId),
            'PATCH',
            body: $patch,
            repeatable: true,
            apiKey: $apiKey,
        );
    }

    public function deleteAddressForward(string $id, string $addressId, string $forwardId, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call($this->fill(ApiPaths::DOMAIN_ADDRESS_FORWARD, id: $id, addressId: $addressId, forwardId: $forwardId), 'DELETE', apiKey: $apiKey);
    }

    public function resendAddressForwardConsent(string $id, string $addressId, string $forwardId, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call($this->fill(ApiPaths::DOMAIN_ADDRESS_FORWARD_RESEND, id: $id, addressId: $addressId, forwardId: $forwardId), 'POST', apiKey: $apiKey);
    }

    public function listAddressMembers(string $id, string $addressId, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call($this->fill(ApiPaths::DOMAIN_ADDRESS_MEMBERS, id: $id, addressId: $addressId), apiKey: $apiKey);
    }

    public function getAddressLogin(string $id, string $addressId, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call($this->fill(ApiPaths::DOMAIN_ADDRESS_LOGIN, id: $id, addressId: $addressId), apiKey: $apiKey);
    }

    public function setAddressLogin(string $id, string $addressId, #[\SensitiveParameter] array $body, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call($this->fill(ApiPaths::DOMAIN_ADDRESS_LOGIN, id: $id, addressId: $addressId), 'PUT', body: $body, apiKey: $apiKey);
    }

    public function deleteAddressLogin(string $id, string $addressId, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call($this->fill(ApiPaths::DOMAIN_ADDRESS_LOGIN, id: $id, addressId: $addressId), 'DELETE', apiKey: $apiKey);
    }

    public function getDns(string $id, ?bool $refresh = null, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call($this->fill(ApiPaths::DOMAIN_DNS, id: $id), query: $this->query(refresh: $refresh), apiKey: $apiKey);
    }

    public function setDnsZone(string $id, array $body, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call($this->fill(ApiPaths::DOMAIN_DNS, id: $id), 'PUT', body: $body, apiKey: $apiKey);
    }

    public function syncDns(string $id, ?array $body = null, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call($this->fill(ApiPaths::DOMAIN_DNS_SYNC, id: $id), 'POST', body: $this->payload($body), apiKey: $apiKey);
    }
}
