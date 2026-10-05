<?php

declare(strict_types=1);

namespace OpenEmail\Resources;

use OpenEmail\Internal\ApiPaths;

final class Billing extends Resource
{
    public function get(#[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call(ApiPaths::BILLING, apiKey: $apiKey);
    }

    public function listPlans(#[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call(ApiPaths::BILLING_PLANS, apiKey: $apiKey);
    }

    public function getUsage(int|string|null $days = null, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call(ApiPaths::BILLING_USAGE, query: $this->query(days: $days), apiKey: $apiKey);
    }

    public function listAlerts(#[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call(ApiPaths::BILLING_ALERTS, apiKey: $apiKey);
    }

    public function listCountries(#[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call(ApiPaths::BILLING_COUNTRIES, apiKey: $apiKey);
    }

    public function getPayAsYouGo(#[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call(ApiPaths::BILLING_PAY_AS_YOU_GO, apiKey: $apiKey);
    }

    public function setPayAsYouGo(array $body, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call(ApiPaths::BILLING_PAY_AS_YOU_GO, 'PATCH', body: $body, apiKey: $apiKey);
    }

    public function setPayAsYouGoLimit(array $body, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call(ApiPaths::BILLING_PAY_AS_YOU_GO_LIMIT, 'PUT', body: $body, apiKey: $apiKey);
    }

    public function confirmPayAsYouGo(#[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call(ApiPaths::BILLING_PAY_AS_YOU_GO_CONFIRM, 'POST', apiKey: $apiKey);
    }

    public function startCheckout(array $body, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call(ApiPaths::BILLING_CHECKOUT, 'POST', body: $body, apiKey: $apiKey);
    }

    public function openPortal(?array $body = null, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call(ApiPaths::BILLING_PORTAL, 'POST', body: $this->payload($body), apiKey: $apiKey);
    }

    public function listInvoices(
        ?int $page = null,
        ?int $limit = null,
        ?string $sort = null,
        ?string $search = null,
        #[\SensitiveParameter]
        ?string $apiKey = null,
    ): array {
        return $this->call(ApiPaths::BILLING_INVOICES, query: $this->query(page: $page, limit: $limit, sort: $sort, search: $search), apiKey: $apiKey);
    }

    public function getInvoice(string $orderId, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call($this->fill(ApiPaths::BILLING_INVOICE, orderId: $orderId), apiKey: $apiKey);
    }

    public function saveInvoiceDetails(string $orderId, array $body, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call($this->fill(ApiPaths::BILLING_INVOICE_DETAILS, orderId: $orderId), 'PUT', body: $body, apiKey: $apiKey);
    }
}
