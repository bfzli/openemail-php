<?php

declare(strict_types=1);

namespace OpenEmail;

use OpenEmail\Http\HttpClient;
use OpenEmail\Internal\Credentials;
use OpenEmail\Internal\Defaults;
use OpenEmail\Internal\Endpoint;
use OpenEmail\Internal\LanguageIndex;
use OpenEmail\Internal\Protocol;
use OpenEmail\Internal\RawBody;
use OpenEmail\Resources\Account;
use OpenEmail\Resources\Addresses;
use OpenEmail\Resources\Analytics;
use OpenEmail\Resources\AppHost;
use OpenEmail\Resources\Audiences;
use OpenEmail\Resources\Billing;
use OpenEmail\Resources\Branding;
use OpenEmail\Resources\Broadcasts;
use OpenEmail\Resources\Calendar;
use OpenEmail\Resources\Chats;
use OpenEmail\Resources\Contacts;
use OpenEmail\Resources\DnsConnections;
use OpenEmail\Resources\Domains;
use OpenEmail\Resources\Drafts;
use OpenEmail\Resources\Emails;
use OpenEmail\Resources\Encryption;
use OpenEmail\Resources\Exports;
use OpenEmail\Resources\Files;
use OpenEmail\Resources\Forms;
use OpenEmail\Resources\Imports;
use OpenEmail\Resources\Keys;
use OpenEmail\Resources\Labels;
use OpenEmail\Resources\Languages;
use OpenEmail\Resources\Me;
use OpenEmail\Resources\Members;
use OpenEmail\Resources\ProviderImports;
use OpenEmail\Resources\Roles;
use OpenEmail\Resources\Rules;
use OpenEmail\Resources\Security;
use OpenEmail\Resources\Senders;
use OpenEmail\Resources\Settings;
use OpenEmail\Resources\Subscriptions;
use OpenEmail\Resources\Support;
use OpenEmail\Resources\Suppressions;
use OpenEmail\Resources\Templates;
use OpenEmail\Resources\TempMail;
use OpenEmail\Resources\Threads;
use OpenEmail\Resources\Tools;
use OpenEmail\Resources\Tracking;
use OpenEmail\Resources\Webhooks;
use OpenEmail\Resources\Workspaces;

final class OpenEmail
{
    public const VERSION = '0.0.1';

    private static ?self $default = null;

    public readonly string $mode;

    public readonly Transport $raw;

    public readonly Me $me;

    public readonly Security $security;

    public readonly Keys $keys;

    public readonly Addresses $addresses;

    public readonly Languages $languages;

    public readonly Emails $emails;

    public readonly Templates $templates;

    public readonly Tracking $tracking;

    public readonly Threads $threads;

    public readonly Drafts $drafts;

    public readonly Labels $labels;

    public readonly Contacts $contacts;

    public readonly Audiences $audiences;

    public readonly Billing $billing;

    public readonly Forms $forms;

    public readonly Broadcasts $broadcasts;

    public readonly Domains $domains;

    public readonly AppHost $appHost;

    public readonly Branding $branding;

    public readonly Rules $rules;

    public readonly Webhooks $webhooks;

    public readonly Imports $imports;

    public readonly ProviderImports $providerImports;

    public readonly Calendar $calendar;

    public readonly Settings $settings;

    public readonly Roles $roles;

    public readonly Members $members;

    public readonly Suppressions $suppressions;

    public readonly Files $files;

    public readonly TempMail $tempMail;

    public readonly Exports $exports;

    public readonly Chats $chats;

    public readonly Encryption $encryption;

    public readonly Support $support;

    public readonly Tools $tools;

    public readonly Senders $senders;

    public readonly Analytics $analytics;

    public readonly Subscriptions $subscriptions;

    public readonly DnsConnections $dnsConnections;

    public readonly Workspaces $workspaces;

    public readonly Account $account;

    public function __construct(
        #[\SensitiveParameter]
        ?string $apiKey = null,
        #[\SensitiveParameter]
        string|callable|null $accessToken = null,
        #[\SensitiveParameter]
        ?string $baseUrl = null,
        ?HttpClient $httpClient = null,
        ?int $maxRetries = null,
        int|float|null $timeout = null,
        ?string $userAgent = null,
        #[\SensitiveParameter]
        array $headers = [],
        bool $disableUpdateNotice = false,
    ) {
        $explicit = $apiKey !== null || $accessToken !== null;
        $key = $explicit ? $apiKey : Credentials::fromEnvironment(Protocol::ENV_VARS['API_KEY']);
        $token = $explicit || $key !== null ? $accessToken : Credentials::fromEnvironment(Protocol::ENV_VARS['ACCESS_TOKEN']);
        $provider = Credentials::provider($token);

        Credentials::assertCredential($key, $provider, true);

        $this->raw = new Transport(
            apiKey: $key,
            accessToken: $provider,
            baseUrl: self::baseUrl($baseUrl),
            httpClient: $httpClient,
            maxRetries: $maxRetries,
            timeout: $timeout,
            userAgent: $userAgent,
            headers: $headers,
            disableUpdateNotice: $disableUpdateNotice,
        );
        $this->mode = Credentials::modeOf($key);
        $this->me = new Me($this->raw);
        $this->security = new Security($this->raw);
        $this->keys = new Keys($this->raw);
        $this->addresses = new Addresses($this->raw);
        $this->languages = new Languages($this->raw);
        $this->emails = new Emails($this->raw);
        $this->templates = new Templates($this->raw);
        $this->tracking = new Tracking($this->raw);
        $this->threads = new Threads($this->raw);
        $this->drafts = new Drafts($this->raw);
        $this->labels = new Labels($this->raw);
        $this->contacts = new Contacts($this->raw);
        $this->audiences = new Audiences($this->raw);
        $this->billing = new Billing($this->raw);
        $this->forms = new Forms($this->raw);
        $this->broadcasts = new Broadcasts($this->raw);
        $this->domains = new Domains($this->raw);
        $this->appHost = new AppHost($this->raw);
        $this->branding = new Branding($this->raw);
        $this->rules = new Rules($this->raw);
        $this->webhooks = new Webhooks($this->raw);
        $this->imports = new Imports($this->raw);
        $this->providerImports = new ProviderImports($this->raw);
        $this->calendar = new Calendar($this->raw);
        $this->settings = new Settings($this->raw);
        $this->roles = new Roles($this->raw);
        $this->members = new Members($this->raw);
        $this->suppressions = new Suppressions($this->raw);
        $this->files = new Files($this->raw);
        $this->tempMail = new TempMail($this->raw);
        $this->exports = new Exports($this->raw);
        $this->chats = new Chats($this->raw);
        $this->encryption = new Encryption($this->raw);
        $this->support = new Support($this->raw);
        $this->tools = new Tools($this->raw);
        $this->senders = new Senders($this->raw);
        $this->analytics = new Analytics($this->raw);
        $this->subscriptions = new Subscriptions($this->raw);
        $this->dnsConnections = new DnsConnections($this->raw);
        $this->workspaces = new Workspaces($this->raw);
        $this->account = new Account($this->raw);
    }

    public static function createClient(
        #[\SensitiveParameter]
        ?string $apiKey = null,
        #[\SensitiveParameter]
        string|callable|null $accessToken = null,
        #[\SensitiveParameter]
        ?string $baseUrl = null,
        ?HttpClient $httpClient = null,
        ?int $maxRetries = null,
        int|float|null $timeout = null,
        ?string $userAgent = null,
        #[\SensitiveParameter]
        array $headers = [],
        bool $disableUpdateNotice = false,
    ): self {
        return new self($apiKey, $accessToken, $baseUrl, $httpClient, $maxRetries, $timeout, $userAgent, $headers, $disableUpdateNotice);
    }

    public static function init(
        #[\SensitiveParameter]
        ?string $apiKey = null,
        #[\SensitiveParameter]
        string|callable|null $accessToken = null,
        #[\SensitiveParameter]
        ?string $baseUrl = null,
        ?HttpClient $httpClient = null,
        ?int $maxRetries = null,
        int|float|null $timeout = null,
        ?string $userAgent = null,
        #[\SensitiveParameter]
        array $headers = [],
        bool $disableUpdateNotice = false,
    ): self {
        return self::$default = new self($apiKey, $accessToken, $baseUrl, $httpClient, $maxRetries, $timeout, $userAgent, $headers, $disableUpdateNotice);
    }

    public static function getClient(): self
    {
        return self::$default ??= new self();
    }

    public static function resetClient(): void
    {
        self::$default = null;
    }

    public static function createTempMail(
        #[\SensitiveParameter]
        ?string $baseUrl = null,
        #[\SensitiveParameter]
        ?string $inboxToken = null,
        ?HttpClient $httpClient = null,
        ?int $maxRetries = null,
        int|float|null $timeout = null,
        ?string $userAgent = null,
        #[\SensitiveParameter]
        array $headers = [],
        bool $disableUpdateNotice = false,
    ): TempMail {
        return new TempMail(new Transport(
            inboxToken: $inboxToken,
            baseUrl: self::baseUrl($baseUrl),
            httpClient: $httpClient,
            maxRetries: $maxRetries,
            timeout: $timeout,
            userAgent: $userAgent,
            headers: $headers,
            disableUpdateNotice: $disableUpdateNotice,
        ));
    }

    public static function verifyWebhookSignature(
        string $payload,
        mixed $headers,
        #[\SensitiveParameter]
        string $secret,
        ?int $toleranceSeconds = null,
    ): array {
        return Webhook::verify($payload, $headers, $secret, $toleranceSeconds);
    }

    public static function toBase64(mixed $bytes): string
    {
        return base64_encode(RawBody::read($bytes));
    }

    public static function isApiKey(#[\SensitiveParameter] mixed $value): bool
    {
        return Credentials::isApiKey($value);
    }

    public static function isAccessToken(#[\SensitiveParameter] mixed $value): bool
    {
        return Credentials::isAccessToken($value);
    }

    public static function isSealed(array $message): bool
    {
        $encryption = $message['encryption'] ?? null;
        $format = \is_array($encryption) ? ($encryption['format'] ?? null) : null;

        return \is_string($format) && \in_array($format, Protocol::SEALED_ENCRYPTION_FORMATS, true);
    }

    public static function resolveLanguage(?string $input): ?array
    {
        return LanguageIndex::resolve($input);
    }

    public static function languageByCode(?string $code): ?array
    {
        return LanguageIndex::byCode($code);
    }

    public static function isRtlLanguage(?string $code): bool
    {
        return LanguageIndex::isRtl($code);
    }

    public function close(): void
    {
        $this->raw->close();
    }

    public function __debugInfo(): array
    {
        return ['mode' => $this->mode, 'baseUrl' => $this->raw->baseUrl, 'credential' => Defaults::REDACTED];
    }

    private static function baseUrl(#[\SensitiveParameter] ?string $baseUrl): ?string
    {
        return Endpoint::normalise($baseUrl ?? Credentials::fromEnvironment(Protocol::ENV_VARS['BASE_URL']));
    }
}
