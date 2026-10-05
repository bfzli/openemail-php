<?php

declare(strict_types=1);

namespace OpenEmail\Exception;

use OpenEmail\Constants\ErrorTypes;
use OpenEmail\Constants\StepUpErrorCodes;
use OpenEmail\Internal\Protocol;

class ApiException extends \RuntimeException implements OpenEmailException
{
    public function __construct(
        string $message,
        public readonly ?int $status,
        public readonly string $type,
        public readonly string $errorCode,
        public readonly ?string $param = null,
        public readonly ?string $docUrl = null,
        public readonly ?string $requestId = null,
        public readonly int|float|null $retryAfterSeconds = null,
        public readonly ?array $fields = null,
        public readonly mixed $body = null,
        ?\Throwable $previous = null,
    ) {
        parent::__construct($message, $status ?? 0, $previous);
    }

    public static function create(
        string $message,
        ?int $status,
        string $type,
        string $errorCode,
        ?string $param = null,
        ?string $docUrl = null,
        ?string $requestId = null,
        int|float|null $retryAfterSeconds = null,
        ?array $fields = null,
        mixed $body = null,
    ): self {
        $arguments = [$message, $status, $type, $errorCode, $param, $docUrl, $requestId, $retryAfterSeconds, $fields, $body];

        return match ($type) {
            ErrorTypes::INVALID_REQUEST_ERROR => new InvalidRequestException(...$arguments),
            ErrorTypes::AUTHENTICATION_ERROR => new AuthenticationException(...$arguments),
            ErrorTypes::PERMISSION_ERROR => new PermissionException(...$arguments),
            ErrorTypes::NOT_FOUND_ERROR => new NotFoundException(...$arguments),
            ErrorTypes::CONFLICT_ERROR => new ConflictException(...$arguments),
            ErrorTypes::VALIDATION_ERROR => new ValidationException(...$arguments),
            ErrorTypes::RATE_LIMIT_ERROR => new RateLimitException(...$arguments),
            default => new self(...$arguments),
        };
    }

    public function isAuth(): bool
    {
        return $this->type === ErrorTypes::AUTHENTICATION_ERROR;
    }

    public function isPermission(): bool
    {
        return $this->type === ErrorTypes::PERMISSION_ERROR;
    }

    public function isScopeMissing(): bool
    {
        return $this->errorCode === Protocol::ERROR_CODES['INSUFFICIENT_SCOPE'];
    }

    public function isStepUpRequired(): bool
    {
        return $this->errorCode === StepUpErrorCodes::STEP_UP_REQUIRED;
    }

    public function isInvalidRequest(): bool
    {
        return $this->type === ErrorTypes::INVALID_REQUEST_ERROR;
    }

    public function isValidation(): bool
    {
        return $this->type === ErrorTypes::VALIDATION_ERROR;
    }

    public function isNotFound(): bool
    {
        return $this->type === ErrorTypes::NOT_FOUND_ERROR;
    }

    public function isConflict(): bool
    {
        return $this->type === ErrorTypes::CONFLICT_ERROR;
    }

    public function isRateLimited(): bool
    {
        return $this->type === ErrorTypes::RATE_LIMIT_ERROR;
    }

    public function isServerError(): bool
    {
        return $this->status !== null && $this->status >= 500;
    }

    public function isRetryable(): bool
    {
        return $this->status !== null && \in_array($this->status, Protocol::RETRY['STATUSES'], true);
    }
}
