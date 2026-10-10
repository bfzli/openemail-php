<?php

declare(strict_types=1);

namespace OpenEmail\Internal;

final class Protocol
{
    public const ACCESS_TOKEN_RULES = ['RESERVED_PREFIX' => 'oe_', 'MAX_LENGTH' => 512];
    public const API_KEY_MODES = ['LIVE' => 'live', 'TEST' => 'test'];
    public const API_KEY_PREFIXES = ['LIVE' => 'oe_live_', 'TEST' => 'oe_test_'];
    public const CONTENT_TYPES = [
        'JSON' => 'application/json',
        'CALENDAR' => 'text/calendar',
        'OCTET_STREAM' => 'application/octet-stream',
    ];
    public const CURSOR_STYLES = [
        'CURSOR' => ['PARAM' => 'cursor', 'FIELD' => 'nextCursor'],
        'PAGE_TOKEN' => ['PARAM' => 'pageToken', 'FIELD' => 'nextPageToken'],
    ];
    public const ENV_VARS = [
        'API_KEY' => 'OPENEMAIL_API_KEY',
        'ACCESS_TOKEN' => 'OPENEMAIL_ACCESS_TOKEN',
        'BASE_URL' => 'OPENEMAIL_BASE_URL',
    ];
    public const ERROR_CODES = [
        'INSUFFICIENT_SCOPE' => 'insufficient_scope',
        'UNRECOGNISED_RESPONSE' => 'unrecognised_response',
    ];
    public const ERROR_TYPE_BY_STATUS = [
        401 => 'authentication_error',
        403 => 'permission_error',
        404 => 'not_found_error',
        409 => 'conflict_error',
        422 => 'validation_error',
        429 => 'rate_limit_error',
    ];
    public const HEADER_KEYS = [
        'AUTHORIZATION' => 'Authorization',
        'ACCEPT' => 'Accept',
        'CONTENT_TYPE' => 'Content-Type',
        'USER_AGENT' => 'User-Agent',
        'IDEMPOTENCY_KEY' => 'Idempotency-Key',
        'RETRY_AFTER' => 'retry-after',
        'REQUEST_ID' => 'x-request-id',
    ];
    public const HTTP_METHODS = [
        'GET' => 'GET',
        'POST' => 'POST',
        'PUT' => 'PUT',
        'PATCH' => 'PATCH',
        'DELETE' => 'DELETE',
    ];
    public const IDEMPOTENCY_KEY_PREFIX = 'oe-';
    public const LANGUAGE_ALIASES = [
        ['zh', 'zh-Hans'],
        ['zh-cn', 'zh-Hans'],
        ['zh-sg', 'zh-Hans'],
        ['cmn', 'zh-Hans'],
        ['zh-tw', 'zh-Hant'],
        ['zh-mo', 'zh-HK'],
        ['iw', 'he'],
        ['in', 'id'],
        ['ji', 'yi'],
        ['mo', 'ro'],
        ['sh', 'sr-Latn'],
        ['sr-latn-rs', 'sr-Latn'],
        ['prs', 'fa-AF'],
        ['fa-af', 'fa-AF'],
        ['kmr', 'ku'],
        ['pt-pt', 'pt'],
        ['es-mx', 'es-419'],
        ['es-ar', 'es-419'],
        ['es-co', 'es-419'],
        ['es-cl', 'es-419'],
    ];
    public const LIST_SEPARATOR = ',';
    public const LOCAL_HOSTS = ['localhost', '::1', '[::1]', '127.0.0.1', '0.0.0.0', '::', '[::]'];
    public const LOOPBACK_HOSTNAMES = ['localhost', '::1', '[::1]'];
    public const LOOPBACK_IPV4_ADDRESS = '127.0.0.1';
    public const LOOPBACK_IPV6_ADDRESS = '[::1]';
    public const QUERY_KEYS = [
        'LIMIT' => 'limit',
        'CURSOR' => 'cursor',
        'STATUS' => 'status',
        'FROM' => 'from',
        'TO' => 'to',
        'TIMEZONE' => 'timezone',
        'ENABLED' => 'enabled',
        'RULE_ID' => 'ruleId',
        'THREAD_ID' => 'threadId',
        'FOLDER' => 'folder',
        'QUERY' => 'query',
        'LABEL_IDS' => 'labelIds',
        'OPENED' => 'opened',
        'CLICKED' => 'clicked',
        'DAYS' => 'days',
        'MINUTES' => 'minutes',
        'GRAIN' => 'grain',
        'OFFSET_MINUTES' => 'offsetMinutes',
        'INCLUDE_MACHINE' => 'includeMachine',
        'REASSIGN_TO' => 'reassignTo',
        'SOURCE' => 'source',
        'ADDRESS' => 'address',
        'DIRECTION' => 'direction',
        'FILTER' => 'filter',
        'SEARCH' => 'search',
        'SORT' => 'sort',
        'PAGE' => 'page',
        'PAGE_SIZE' => 'pageSize',
        'VERSION' => 'version',
        'TRACKED' => 'tracked',
        'Q' => 'q',
        'AUDIENCE_IDS' => 'audienceIds',
        'AUDIENCE_ID' => 'audienceId',
        'BROADCAST_ID' => 'broadcastId',
        'BROADCAST_IDS' => 'broadcastIds',
        'EMAIL' => 'email',
        'WITHOUT_EMAIL' => 'withoutEmail',
        'BLOCKED' => 'blocked',
        'DATE_FROM' => 'dateFrom',
        'DATE_TO' => 'dateTo',
        'FROM_CONTACTS' => 'fromContacts',
        'SEMANTIC' => 'semantic',
        'REASON' => 'reason',
        'KIND' => 'kind',
        'ENDPOINT_IDS' => 'endpointIds',
        'KEY_IDS' => 'keyIds',
        'SINCE' => 'since',
        'UNTIL' => 'until',
        'FAILED_ONLY' => 'failedOnly',
        'FILENAME' => 'filename',
        'DOMAIN' => 'domain',
        'SCHEDULED_FROM' => 'scheduledFrom',
        'SCHEDULED_TO' => 'scheduledTo',
        'ADDRESSES' => 'addresses',
        'OFFSET' => 'offset',
        'REFRESH' => 'refresh',
        'CONFIRM' => 'confirm',
        'USERNAME' => 'username',
        'SCOPE' => 'scope',
        'LEVEL' => 'level',
        'PINNED' => 'pinned',
        'TITLE' => 'title',
        'ITEM_ID' => 'itemId',
        'SENDS' => 'sends',
        'DOMAINS' => 'domains',
        'PEOPLE' => 'people',
        'AI_ACTIONS' => 'aiActions',
        'INTERVAL' => 'interval',
    ];
    public const RETRY = [
        'STATUSES' => [408, 429, 500, 502, 503, 504],
        'METHODS' => ['GET'],
        'BASE_BACKOFF_MS' => 500,
        'MAX_BACKOFF_MS' => 8000,
        'MAX_HONOURED_RETRY_AFTER_MS' => 60000,
        'RATE_LIMIT_STATUS' => 429,
    ];
    public const SEALED_ENCRYPTION_FORMATS = ['pgp-mime', 'pgp-inline', 'smime-encrypted'];
    public const UNSPECIFIED_HOSTNAMES = ['0.0.0.0', '::', '[::]'];
    public const WEBHOOK_SIGNATURE = [
        'VERSION' => 'v1',
        'TIMESTAMP' => 't',
        'PAIR_SEPARATOR' => ',',
        'VALUE_SEPARATOR' => '=',
        'TOLERANCE_SECONDS' => 300,
    ];
}
