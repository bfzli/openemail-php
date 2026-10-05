<?php

declare(strict_types=1);

namespace OpenEmail\Resources;

use OpenEmail\Internal\ApiPaths;

final class Languages extends Resource
{
    public function list(#[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->fetchList(ApiPaths::LANGUAGES, apiKey: $apiKey);
    }
}
