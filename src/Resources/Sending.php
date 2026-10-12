<?php

declare(strict_types=1);

namespace OpenEmail\Resources;

use OpenEmail\Internal\ApiPaths;

final class Sending extends Resource
{
    public function listStreams(#[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call(ApiPaths::SENDING_STREAMS, apiKey: $apiKey);
    }

    public function resumeStream(string $stream, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call($this->fill(ApiPaths::SENDING_STREAM_RESUME, stream: $stream), 'POST', apiKey: $apiKey);
    }

    public function listDedicatedIps(#[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call(ApiPaths::SENDING_DEDICATED_IPS, apiKey: $apiKey);
    }

    public function getDedicatedIp(string $ip, #[\SensitiveParameter] ?string $apiKey = null): array
    {
        return $this->call($this->fill(ApiPaths::SENDING_DEDICATED_IP, ip: $ip), apiKey: $apiKey);
    }
}
