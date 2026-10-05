<?php

declare(strict_types=1);

namespace OpenEmail\Internal;

use OpenEmail\OpenEmail;

final class Defaults
{
    public const BASE_URL = 'https://api.openemail.uk';
    public const MAX_RETRIES = 2;
    public const TIMEOUT = 30;
    public const UPLOAD_TIMEOUT = 600;
    public const USER_AGENT = 'openemail-php/' . OpenEmail::VERSION;
    public const BODYLESS_WRITES = ['POST', 'PUT', 'PATCH'];
    public const SUPPRESSED_CURL_HEADERS = ['Expect', 'Content-Type'];
    public const REDACTED = '[redacted]';
    public const JSON_DEPTH = 4096;
    public const BODY_DEPTH = 512;
    public const ERROR_MESSAGE_LENGTH = 300;
    public const MILLISECONDS_PER_SECOND = 1000;
    public const MICROSECONDS_PER_SECOND = 1000000;
    public const ISO_INSTANT = 'Y-m-d\TH:i:s.v\Z';
    public const UTC = 'UTC';
    public const PACKAGE = 'openemail/sdk';

    public const LOOPBACK_IPV4_PATTERN = '/^127\.\d{1,3}\.\d{1,3}\.\d{1,3}$/';
    public const ENDPOINT_SCHEME_PATTERN = '/^[a-z][a-z0-9+.-]*:\/\//i';
    public const LANGUAGE_SUBTAG_PATTERN = '/[-_]/';
    public const PATH_PARAM = '/:([A-Za-z0-9_]+)(?:\{[^}]*\})?/';
    public const OPTIONAL_PATH_PARAM = '/\/:([A-Za-z0-9_]+)\?/';
    public const DOT_SEGMENT = '/^\.+$/';
    public const TRAILING_SLASHES = '/\/+$/';
    public const IPV4_DECIMAL_PART = '/^(?:0|[1-9]\d*)$/';
    public const IPV4_OCTAL_PART = '/^0[0-7]+$/';
    public const IPV4_HEX_PART = '/^0x[0-9a-f]*$/i';
    public const HEADER_NAME = '/^[!#$%&\'*+\-.^_`|~0-9A-Za-z]+$/';
    public const HEADER_VALUE_FORBIDDEN = '/[\x00-\x08\x0A-\x1F\x7F]/';
    public const HTTP_WHITESPACE = " \t\r\n";
    public const URL_FORBIDDEN = '/[\x00-\x20\x7F]/';
    public const PATH_FORBIDDEN = '/[\x00-\x1F\x7F]/';
    public const LEADING_WEEKDAY = '/^(?:mon|tue|wed|thu|fri|sat|sun)[a-z]*(?:,\s*|\s+)/i';
    public const URL_USERINFO = '/^([a-z][a-z0-9+.-]*:\/\/)[^\/?#]*@/i';
    public const HOST_CHARACTERS = '/^(?:\[[0-9A-Fa-f:.]+\]|[^\s\/?#@\[\]\\\\]+)$/';
    public const BASE64_TEXT = '/^(?:[A-Za-z0-9+\/_-]{4})*(?:[A-Za-z0-9+\/_-]{2}==|[A-Za-z0-9+\/_-]{3}=)?$/';
    public const BASE64_WHITESPACE = [' ', "\t", "\n", "\f", "\r"];
    public const LONE_SURROGATE_ESCAPE = '/\\\\u(d[89ab][0-9a-f]{2})(\\\\u(d[c-f][0-9a-f]{2}))?|\\\\u(d[c-f][0-9a-f]{2})|\\\\./i';
    public const REPLACEMENT_ESCAPE = '\\ufffd';
    public const BYTE_ORDER_MARK = "\u{FEFF}";
    public const TIMEOUT_MESSAGE = '/timed? ?out|cURL error 28|idle timeout|max duration/i';
    public const INTEGER_TEXT = '/^[+-]?\d+$/';

    public const CONTENT_TYPES_BY_EXTENSION = [
        'png' => 'image/png',
        'jpg' => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'webp' => 'image/webp',
        'gif' => 'image/gif',
        'svg' => 'image/svg+xml',
        'pdf' => 'application/pdf',
        'txt' => 'text/plain',
        'csv' => 'text/csv',
        'html' => 'text/html',
        'json' => 'application/json',
        'ics' => 'text/calendar',
        'eml' => 'message/rfc822',
        'mbox' => 'application/mbox',
        'zip' => 'application/zip',
        'gz' => 'application/gzip',
        'tgz' => 'application/gzip',
    ];

    public const UPDATE_NOTICE = [
        'ENV_DISABLE' => 'OPENEMAIL_DISABLE_UPDATE_NOTICE',
        'TIMEOUT' => 2,
        'REGISTRY_URL' => 'https://repo.packagist.org/p2/openemail/sdk.json',
        'PAGE_URL' => 'https://packagist.org/packages/openemail/sdk',
        'ICON' => 'ℹ',
    ];
}
