<?php

declare(strict_types=1);

namespace OpenEmail\Http;

use OpenEmail\Internal\Defaults;
use OpenEmail\Internal\Protocol;

final class HttpRequest implements \JsonSerializable
{
    public function __construct(
        public readonly string $method,
        public readonly string $url,
        public readonly array $headers,
        public readonly ?string $body = null,
        public readonly ?float $timeout = null,
    ) {}

    public function header(string $name): ?string
    {
        foreach ($this->headers as $key => $value) {
            if (strcasecmp((string) $key, $name) === 0) {
                return \is_string($value) ? $value : null;
            }
        }

        return null;
    }

    public function __debugInfo(): array
    {
        $headers = [];

        foreach ($this->headers as $name => $value) {
            $redacted = strcasecmp((string) $name, Protocol::HEADER_KEYS['AUTHORIZATION']) === 0;
            $headers[$name] = $redacted ? Defaults::REDACTED : $value;
        }

        return [
            'method' => $this->method,
            'url' => $this->url,
            'headers' => $headers,
            'body' => $this->body === null ? null : \strlen($this->body) . ' bytes',
            'timeout' => $this->timeout,
        ];
    }

    public function jsonSerialize(): array
    {
        return $this->__debugInfo();
    }
}
