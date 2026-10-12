<?php

declare(strict_types=1);

namespace OpenEmail\Constants;

final class AddressCheckReasons
{
    public const INVALID_SYNTAX = 'invalid_syntax';
    public const DOMAIN_NOT_FOUND = 'domain_not_found';
    public const NO_MAIL_SERVER = 'no_mail_server';
    public const NULL_MX = 'null_mx';
    public const NO_MX_RECORD = 'no_mx_record';
    public const DNS_UNREACHABLE = 'dns_unreachable';
    public const DISPOSABLE = 'disposable';
    public const ROLE_ADDRESS = 'role_address';
    public const TYPO_SUSPECTED = 'typo_suspected';
    public const SUPPRESSED = 'suppressed';
    public const BOUNCED_BEFORE = 'bounced_before';
    public const MAILBOX_NOT_FOUND = 'mailbox_not_found';
    public const MAILBOX_UNCERTAIN = 'mailbox_uncertain';
    public const RANDOM_INPUT = 'random_input';

    public static function values(): array
    {
        return [
            self::INVALID_SYNTAX,
            self::DOMAIN_NOT_FOUND,
            self::NO_MAIL_SERVER,
            self::NULL_MX,
            self::NO_MX_RECORD,
            self::DNS_UNREACHABLE,
            self::DISPOSABLE,
            self::ROLE_ADDRESS,
            self::TYPO_SUSPECTED,
            self::SUPPRESSED,
            self::BOUNCED_BEFORE,
            self::MAILBOX_NOT_FOUND,
            self::MAILBOX_UNCERTAIN,
            self::RANDOM_INPUT,
        ];
    }
}
