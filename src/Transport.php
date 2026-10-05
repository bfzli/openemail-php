<?php

declare(strict_types=1);

namespace OpenEmail;

use OpenEmail\Constants\ErrorTypes;
use OpenEmail\Exception\ApiException;
use OpenEmail\Exception\InvalidArgumentException;
use OpenEmail\Exception\NetworkException;
use OpenEmail\Exception\OpenEmailException;
use OpenEmail\Http\CurlHttpClient;
use OpenEmail\Http\HttpClient;
use OpenEmail\Http\HttpClientException;
use OpenEmail\Http\HttpRequest;
use OpenEmail\Http\HttpResponse;
use OpenEmail\Http\Psr18HttpClient;
use OpenEmail\Internal\Credentials;
use OpenEmail\Internal\Defaults;
use OpenEmail\Internal\Endpoint;
use OpenEmail\Internal\Json;
use OpenEmail\Internal\Messages;
use OpenEmail\Internal\Protocol;
use OpenEmail\Internal\RawBody;
use OpenEmail\Internal\UpdateNotice;
use OpenEmail\Internal\Wire;

final class Transport
{
    public readonly string $baseUrl;

    public readonly float $timeout;

    private readonly ?\SensitiveParameterValue $apiKey;

    private readonly ?\SensitiveParameterValue $accessToken;

    private readonly ?\SensitiveParameterValue $inboxToken;

    private readonly bool $secureOrigin;

    private readonly HttpClient $httpClient;

    private readonly int $maxRetries;

    private readonly string $userAgent;

    private readonly \SensitiveParameterValue $headers;

    private readonly \Closure $sleep;

    public function __construct(
        #[\SensitiveParameter]
        ?string $apiKey = null,
        #[\SensitiveParameter]
        mixed $accessToken = null,
        #[\SensitiveParameter]
        ?string $inboxToken = null,
        #[\SensitiveParameter]
        ?string $baseUrl = null,
        ?HttpClient $httpClient = null,
        ?int $maxRetries = null,
        int|float|null $timeout = null,
        ?string $userAgent = null,
        #[\SensitiveParameter]
        array $headers = [],
        bool $disableUpdateNotice = false,
        ?\Closure $sleep = null,
    ) {
        $provider = Credentials::provider($accessToken);

        Credentials::assertCredential($apiKey, $provider, false);

        $this->apiKey = Credentials::isPresent($apiKey) ? new \SensitiveParameterValue($apiKey) : null;
        $this->accessToken = Credentials::isPresent($provider) ? new \SensitiveParameterValue($provider) : null;
        $this->inboxToken = $inboxToken === null ? null : new \SensitiveParameterValue($inboxToken);
        $this->baseUrl = Endpoint::validBaseUrl($baseUrl ?? Defaults::BASE_URL);
        $this->secureOrigin = Endpoint::isSecureOrigin($this->baseUrl);
        $this->httpClient = $httpClient ?? new CurlHttpClient();
        $this->maxRetries = self::retries($maxRetries);
        $this->timeout = $timeout === null ? (float) Defaults::TIMEOUT : self::seconds($timeout);
        $this->userAgent = self::headerValue($userAgent ?? Defaults::USER_AGENT);
        $this->headers = new \SensitiveParameterValue(self::customHeaders($headers));
        $this->sleep = $sleep ?? self::pause(...);

        if (!$disableUpdateNotice) {
            UpdateNotice::check();
        }
    }

    public function request(
        string $path,
        string $method = 'GET',
        ?array $query = null,
        #[\SensitiveParameter]
        ?array $body = null,
        #[\SensitiveParameter]
        mixed $raw = null,
        ?string $contentType = null,
        #[\SensitiveParameter]
        ?string $apiKey = null,
        #[\SensitiveParameter]
        ?string $inboxToken = null,
        bool $anonymous = false,
        bool $idempotent = false,
        ?string $idempotencyKey = null,
        ?bool $repeatable = null,
        ?string $accept = null,
        bool $binary = false,
        int|float|null $timeout = null,
    ): mixed {
        $verb = strtoupper($method);

        if (preg_match(Defaults::HEADER_NAME, $verb) !== 1) {
            throw new InvalidArgumentException(Messages::METHOD_SHAPE);
        }

        $outbound = new HttpRequest(
            $verb,
            Endpoint::buildUrl($this->baseUrl, $path, $query),
            $this->headersFor($raw !== null, $body !== null, $contentType, $apiKey, $inboxToken, $anonymous, $idempotent, $idempotencyKey, $accept),
            $raw !== null ? RawBody::read($raw) : ($body === null ? null : Wire::encodeBody($body)),
            self::effective($timeout === null ? $this->timeout : self::seconds($timeout)),
        );

        try {
            return $this->deliver($outbound, $repeatable ?? \in_array($verb, Protocol::RETRY['METHODS'], true), $accept, $binary);
        } finally {
            UpdateNotice::poll();
        }
    }

    public function close(): void
    {
        if ($this->httpClient instanceof CurlHttpClient) {
            $this->httpClient->close();
        }
    }

    public function __debugInfo(): array
    {
        return [
            'baseUrl' => $this->baseUrl,
            'timeout' => $this->timeout,
            'maxRetries' => $this->maxRetries,
            'userAgent' => $this->userAgent,
            'apiKey' => $this->apiKey === null ? null : Defaults::REDACTED,
            'accessToken' => $this->accessToken === null ? null : Defaults::REDACTED,
            'inboxToken' => $this->inboxToken === null ? null : Defaults::REDACTED,
            'httpClient' => $this->httpClient::class,
        ];
    }

    private function deliver(#[\SensitiveParameter] HttpRequest $request, bool $repeatable, ?string $accept, bool $binary): mixed
    {
        $attempt = 0;

        while (true) {
            $outcome = $this->exchange($request);

            if ($outcome instanceof NetworkException) {
                if (!$repeatable || $attempt >= $this->maxRetries) {
                    throw $outcome;
                }

                ($this->sleep)(self::backoff($attempt));
                $attempt += 1;

                continue;
            }

            if ($outcome->isSuccessful()) {
                return $binary ? $outcome->body : self::parse($outcome, $accept);
            }

            $wait = self::retryAfter($outcome->header(Protocol::HEADER_KEYS['RETRY_AFTER']));

            if ($repeatable && $attempt < $this->maxRetries && self::isRetryStatus($outcome->status) && self::honoured($outcome->status, $wait)) {
                ($this->sleep)($wait ?? self::backoff($attempt));
                $attempt += 1;

                continue;
            }

            throw self::apiError($outcome, $wait);
        }
    }

    private function exchange(#[\SensitiveParameter] HttpRequest $request): HttpResponse|NetworkException
    {
        try {
            return $this->httpClient->send($request);
        } catch (HttpClientException $error) {
            if ($error->isTimeout()) {
                $message = $this->httpClient instanceof Psr18HttpClient && !$this->httpClient->appliesTimeout()
                    ? \sprintf(Messages::TIMED_OUT_IN_CLIENT, $error->getMessage())
                    : \sprintf(Messages::TIMED_OUT, Wire::numberText($request->timeout ?? 0.0));

                return new NetworkException($message, true, $error);
            }

            return new NetworkException(\sprintf(Messages::UNREACHABLE, $this->baseUrl, $error->getMessage()), false, $error);
        } catch (OpenEmailException|\LogicException $error) {
            throw $error;
        } catch (\Exception $error) {
            return new NetworkException(\sprintf(Messages::UNREACHABLE, $this->baseUrl, $error->getMessage()), false, $error);
        }
    }

    private function headersFor(
        bool $hasRaw,
        bool $hasBody,
        ?string $contentType,
        #[\SensitiveParameter]
        ?string $apiKey,
        #[\SensitiveParameter]
        ?string $inboxToken,
        bool $anonymous,
        bool $idempotent,
        ?string $idempotencyKey,
        ?string $accept,
    ): array {
        $headers = $this->customHeadersValue();

        self::put($headers, Protocol::HEADER_KEYS['ACCEPT'], self::headerValue($accept ?? Protocol::CONTENT_TYPES['JSON']));
        self::put($headers, Protocol::HEADER_KEYS['USER_AGENT'], $this->userAgent);

        if ($hasRaw) {
            self::put($headers, Protocol::HEADER_KEYS['CONTENT_TYPE'], self::headerValue($contentType ?? Protocol::CONTENT_TYPES['OCTET_STREAM']));
        } elseif ($hasBody) {
            self::put($headers, Protocol::HEADER_KEYS['CONTENT_TYPE'], Protocol::CONTENT_TYPES['JSON']);
        }

        if (!$anonymous) {
            $this->authorize($headers, $apiKey, $inboxToken);
        }

        if ($idempotent) {
            self::put($headers, Protocol::HEADER_KEYS['IDEMPOTENCY_KEY'], self::headerValue($idempotencyKey ?? self::idempotencyKey()));
        }

        return $headers;
    }

    private function authorize(array &$headers, #[\SensitiveParameter] ?string $apiKey, #[\SensitiveParameter] ?string $inboxToken): void
    {
        if ($apiKey !== null) {
            Credentials::assertApiKey($apiKey, Messages::CALL_API_KEY_SUBJECT);
        }

        $direct = $inboxToken ?? $apiKey ?? self::secretOf($this->inboxToken) ?? self::secretOf($this->apiKey);

        if (Credentials::isPresent($direct ?? $this->accessToken?->getValue()) && !$this->secureOrigin) {
            throw new InvalidArgumentException(\sprintf(Messages::CLEARTEXT, $this->baseUrl));
        }

        $credential = $direct ?? $this->resolvedAccessToken();

        if (!Credentials::isPresent($credential)) {
            return;
        }

        self::put($headers, Protocol::HEADER_KEYS['AUTHORIZATION'], self::headerValue('Bearer ' . $credential));
    }

    private function resolvedAccessToken(): ?string
    {
        $provider = $this->accessToken?->getValue();

        if (!$provider instanceof \Closure) {
            return \is_string($provider) ? $provider : null;
        }

        $token = $provider();

        if (Credentials::isAccessToken($token) && \is_string($token)) {
            return $token;
        }

        throw new InvalidArgumentException(Messages::ACCESS_TOKEN_PROVIDED . ' ' . Messages::ACCESS_TOKEN_SHAPE);
    }

    private static function parse(HttpResponse $response, ?string $accept): mixed
    {
        $text = Json::text($response->body);

        if ($accept !== null && $accept !== Protocol::CONTENT_TYPES['JSON']) {
            return $text;
        }

        if ($text === '') {
            return null;
        }

        try {
            return Json::decode($text);
        } catch (\JsonException) {
            throw ApiException::create(
                \sprintf(Messages::NOT_JSON, $response->status),
                $response->status,
                ErrorTypes::API_ERROR,
                Protocol::ERROR_CODES['UNRECOGNISED_RESPONSE'],
                requestId: $response->header(Protocol::HEADER_KEYS['REQUEST_ID']),
            );
        }
    }

    private static function apiError(HttpResponse $response, int|float|null $wait): ApiException
    {
        $text = Json::text($response->body);
        $body = Json::tryDecode($text);
        $payload = \is_array($body) && \is_array($body['error'] ?? null) ? $body['error'] : [];
        $type = $payload['type'] ?? null;

        return ApiException::create(
            self::textOf($payload['message'] ?? null) ?? self::fallbackMessage($response->status, $text),
            $response->status,
            \is_string($type) && \in_array($type, ErrorTypes::values(), true) ? $type : self::typeForStatus($response->status),
            self::textOf($payload['code'] ?? null) ?? Protocol::ERROR_CODES['UNRECOGNISED_RESPONSE'],
            self::textOf($payload['param'] ?? null),
            self::textOf($payload['docUrl'] ?? null),
            self::textOf($payload['requestId'] ?? null) ?? $response->header(Protocol::HEADER_KEYS['REQUEST_ID']),
            $wait,
            self::fieldsOf($payload['fields'] ?? null),
            $body,
        );
    }

    private static function fallbackMessage(int $status, string $text): string
    {
        if ($text === '') {
            return \sprintf(Messages::UNRECOGNISED_BODY, $status);
        }

        return preg_match('/^.{0,' . Defaults::ERROR_MESSAGE_LENGTH . '}/su', $text, $match) === 1 ? $match[0] : substr($text, 0, Defaults::ERROR_MESSAGE_LENGTH);
    }

    private static function typeForStatus(int $status): string
    {
        if ($status >= 500) {
            return ErrorTypes::API_ERROR;
        }

        $mapped = Protocol::ERROR_TYPE_BY_STATUS[$status] ?? null;

        return \is_string($mapped) && \in_array($mapped, ErrorTypes::values(), true) ? $mapped : ErrorTypes::INVALID_REQUEST_ERROR;
    }

    private static function fieldsOf(mixed $value): ?array
    {
        if (!\is_array($value)) {
            return null;
        }

        return array_values(array_filter(
            $value,
            static fn(mixed $field): bool => \is_array($field) && \is_string($field['key'] ?? null) && \is_string($field['error'] ?? null),
        ));
    }

    private static function textOf(mixed $value): ?string
    {
        return \is_string($value) && $value !== '' ? $value : null;
    }

    private static function isRetryStatus(int $status): bool
    {
        return \in_array($status, Protocol::RETRY['STATUSES'], true);
    }

    private static function honoured(int $status, int|float|null $wait): bool
    {
        if ($wait === null) {
            return $status !== Protocol::RETRY['RATE_LIMIT_STATUS'];
        }

        return $wait * Defaults::MILLISECONDS_PER_SECOND <= Protocol::RETRY['MAX_HONOURED_RETRY_AFTER_MS'];
    }

    private static function backoff(int $attempt): float
    {
        $ceiling = min(Protocol::RETRY['MAX_BACKOFF_MS'], Protocol::RETRY['BASE_BACKOFF_MS'] * (2 ** $attempt));
        $jitter = 0.5 + (random_int(0, Defaults::MICROSECONDS_PER_SECOND) / Defaults::MICROSECONDS_PER_SECOND) * 0.5;

        return round($ceiling * $jitter) / Defaults::MILLISECONDS_PER_SECOND;
    }

    private static function retryAfter(?string $value): int|float|null
    {
        $text = trim((string) $value);

        if ($text === '') {
            return null;
        }

        if (is_numeric($text)) {
            $seconds = (float) $text;

            return is_finite($seconds) && $seconds >= 0 ? Wire::number($seconds) : null;
        }

        $at = self::httpDate($text);

        return $at === null ? null : max(0, (int) ceil($at - microtime(true)));
    }

    private static function httpDate(string $text): ?int
    {
        $date = (string) preg_replace(Defaults::LEADING_WEEKDAY, '', $text);
        $parts = date_parse($date);

        if ($parts['error_count'] > 0 || isset($parts['relative']) || $parts['year'] === false || $parts['month'] === false || $parts['day'] === false) {
            return null;
        }

        try {
            return (new \DateTimeImmutable($date, new \DateTimeZone(Defaults::UTC)))->getTimestamp();
        } catch (\Exception) {
            return null;
        }
    }

    private static function pause(int|float $seconds): void
    {
        if ($seconds <= 0) {
            return;
        }

        $whole = (int) floor($seconds);
        $micros = (int) round(($seconds - $whole) * Defaults::MICROSECONDS_PER_SECOND);

        if ($whole > 0) {
            sleep($whole);
        }

        if ($micros > 0) {
            usleep($micros);
        }
    }

    private static function idempotencyKey(): string
    {
        $bytes = random_bytes(16);
        $bytes[6] = \chr((\ord($bytes[6]) & 0x0F) | 0x40);
        $bytes[8] = \chr((\ord($bytes[8]) & 0x3F) | 0x80);

        return Protocol::IDEMPOTENCY_KEY_PREFIX . vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($bytes), 4));
    }

    private static function retries(?int $value): int
    {
        return max(0, $value ?? Defaults::MAX_RETRIES);
    }

    private static function seconds(int|float $value): float
    {
        if (is_nan((float) $value) || $value < 0) {
            throw new InvalidArgumentException(Messages::TIMEOUT_SHAPE);
        }

        return is_infinite((float) $value) ? 0.0 : (float) $value;
    }

    private static function effective(float $seconds): ?float
    {
        return $seconds > 0 ? $seconds : null;
    }

    private static function customHeaders(#[\SensitiveParameter] array $headers): array
    {
        $custom = [];

        foreach ($headers as $name => $value) {
            if ($value === null) {
                continue;
            }

            if (\is_array($value) || (\is_object($value) && !$value instanceof \Stringable)) {
                throw new InvalidArgumentException(Messages::HEADER_SHAPE);
            }

            self::put($custom, self::headerName((string) $name), self::headerValue(Endpoint::queryText($value)));
        }

        return $custom;
    }

    private static function put(array &$headers, string $name, #[\SensitiveParameter] string $value): void
    {
        foreach (array_keys($headers) as $existing) {
            if (strcasecmp((string) $existing, $name) === 0) {
                unset($headers[$existing]);
            }
        }

        $headers[$name] = $value;
    }

    private static function headerName(string $name): string
    {
        if (preg_match(Defaults::HEADER_NAME, $name) !== 1) {
            throw new InvalidArgumentException(Messages::HEADER_SHAPE);
        }

        return $name;
    }

    private static function headerValue(#[\SensitiveParameter] string $value): string
    {
        $normalised = trim($value, Defaults::HTTP_WHITESPACE);

        if (preg_match(Defaults::HEADER_VALUE_FORBIDDEN, $normalised) === 1) {
            throw new InvalidArgumentException(Messages::HEADER_SHAPE);
        }

        return $normalised;
    }

    private function customHeadersValue(): array
    {
        return $this->headers->getValue();
    }

    private static function secretOf(?\SensitiveParameterValue $value): ?string
    {
        $secret = $value?->getValue();

        return \is_string($secret) ? $secret : null;
    }
}
