<?php

declare(strict_types=1);

namespace OpenEmail\Resources;

use OpenEmail\Internal\ApiPaths;

final class Branding extends Resource
{
    public function get(#[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call(ApiPaths::BRANDING, apiKey: $apiKey);
    }

    public function update(array $body, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call(ApiPaths::BRANDING, 'PATCH', body: $body, repeatable: true, apiKey: $apiKey);
    }

    public function uploadImage(string $variant, mixed $data, ?string $contentType = null, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call(
            $this->fill(ApiPaths::BRANDING_IMAGE, variant: $variant),
            'PUT',
            raw: $data,
            contentType: $this->rawContentType($data, $contentType),
            repeatable: true,
            apiKey: $apiKey,
        );
    }

    public function removeImage(string $variant, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call($this->fill(ApiPaths::BRANDING_IMAGE, variant: $variant), 'DELETE', repeatable: true, apiKey: $apiKey);
    }
}
