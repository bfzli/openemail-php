<?php

declare(strict_types=1);

namespace OpenEmail\Exception;

final class NetworkException extends \RuntimeException implements OpenEmailException
{
    public function __construct(string $message, private readonly bool $timeout = false, ?\Throwable $previous = null)
    {
        parent::__construct($message, 0, $previous);
    }

    public function isTimeout(): bool
    {
        return $this->timeout;
    }
}
