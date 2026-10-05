<?php

declare(strict_types=1);

namespace OpenEmail\Constants;

final class ApiScopes
{
    public const EMAILS_SEND = 'emails:send';
    public const EMAILS_READ = 'emails:read';
    public const DRAFTS_READ = 'drafts:read';
    public const DRAFTS_WRITE = 'drafts:write';
    public const THREADS_READ = 'threads:read';
    public const THREADS_WRITE = 'threads:write';
    public const FILES_READ = 'files:read';
    public const FILES_WRITE = 'files:write';
    public const LABELS_READ = 'labels:read';
    public const LABELS_WRITE = 'labels:write';
    public const CONTACTS_READ = 'contacts:read';
    public const CONTACTS_WRITE = 'contacts:write';
    public const AUDIENCES_READ = 'audiences:read';
    public const AUDIENCES_WRITE = 'audiences:write';
    public const CALENDAR_READ = 'calendar:read';
    public const CALENDAR_WRITE = 'calendar:write';
    public const TEMPLATES_READ = 'templates:read';
    public const TEMPLATES_WRITE = 'templates:write';
    public const DOMAINS_READ = 'domains:read';
    public const DOMAINS_WRITE = 'domains:write';
    public const WEBHOOKS_READ = 'webhooks:read';
    public const WEBHOOKS_WRITE = 'webhooks:write';
    public const RULES_READ = 'rules:read';
    public const RULES_WRITE = 'rules:write';
    public const CONNECTIONS_READ = 'connections:read';
    public const MEMBERS_READ = 'members:read';
    public const MEMBERS_WRITE = 'members:write';
    public const ROLES_READ = 'roles:read';
    public const ROLES_WRITE = 'roles:write';
    public const SETTINGS_READ = 'settings:read';
    public const SETTINGS_WRITE = 'settings:write';
    public const KEYS_WRITE = 'keys:write';
    public const KEYS_READ = 'keys:read';
    public const KEYS_MANAGE = 'keys:manage';
    public const FORMS_READ = 'forms:read';
    public const FORMS_WRITE = 'forms:write';
    public const BILLING_READ = 'billing:read';
    public const BILLING_WRITE = 'billing:write';
    public const ACCOUNT_READ = 'account:read';
    public const ACCOUNT_WRITE = 'account:write';

    public static function values(): array
    {
        return [
            self::EMAILS_SEND,
            self::EMAILS_READ,
            self::DRAFTS_READ,
            self::DRAFTS_WRITE,
            self::THREADS_READ,
            self::THREADS_WRITE,
            self::FILES_READ,
            self::FILES_WRITE,
            self::LABELS_READ,
            self::LABELS_WRITE,
            self::CONTACTS_READ,
            self::CONTACTS_WRITE,
            self::AUDIENCES_READ,
            self::AUDIENCES_WRITE,
            self::CALENDAR_READ,
            self::CALENDAR_WRITE,
            self::TEMPLATES_READ,
            self::TEMPLATES_WRITE,
            self::DOMAINS_READ,
            self::DOMAINS_WRITE,
            self::WEBHOOKS_READ,
            self::WEBHOOKS_WRITE,
            self::RULES_READ,
            self::RULES_WRITE,
            self::CONNECTIONS_READ,
            self::MEMBERS_READ,
            self::MEMBERS_WRITE,
            self::ROLES_READ,
            self::ROLES_WRITE,
            self::SETTINGS_READ,
            self::SETTINGS_WRITE,
            self::KEYS_WRITE,
            self::KEYS_READ,
            self::KEYS_MANAGE,
            self::FORMS_READ,
            self::FORMS_WRITE,
            self::BILLING_READ,
            self::BILLING_WRITE,
            self::ACCOUNT_READ,
            self::ACCOUNT_WRITE,
        ];
    }
}
