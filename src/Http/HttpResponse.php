<?php

declare(strict_types=1);

namespace OpenEmail\Http;

final class HttpResponse
{
    public readonly array $headers;

    public function __construct(public readonly int $status, array $headers, public readonly string $body)
    {
        $normalised = [];

        foreach ($headers as $name => $value) {
            $key = strtolower((string) $name);
            $parts = [];

            foreach (\is_array($value) ? $value : [$value] as $item) {
                if (\is_scalar($item)) {
                    $parts[] = \is_bool($item) ? ($item ? 'true' : 'false') : (string) $item;
                }
            }

            $text = implode(', ', $parts);
            $normalised[$key] = isset($normalised[$key]) ? $normalised[$key] . ', ' . $text : $text;
        }

        $this->headers = $normalised;
    }

    public function header(string $name): ?string
    {
        return $this->headers[strtolower($name)] ?? null;
    }

    public function isSuccessful(): bool
    {
        return $this->status >= 200 && $this->status <= 299;
    }
}
