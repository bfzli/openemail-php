<?php

declare(strict_types=1);

namespace OpenEmail\Constants;

final class RuleFields
{
    public const FROM = 'from';
    public const FROM_DOMAIN = 'from_domain';
    public const ENVELOPE_FROM = 'envelope_from';
    public const TO = 'to';
    public const CC = 'cc';
    public const BCC = 'bcc';
    public const RECIPIENT = 'recipient';
    public const REPLY_TO = 'reply_to';
    public const DELIVERED_TO = 'delivered_to';
    public const SUBJECT = 'subject';
    public const BODY = 'body';
    public const HEADER = 'header';
    public const LIST_ID = 'list_id';
    public const ATTACHMENT_NAME = 'attachment_name';
    public const ATTACHMENT_TYPE = 'attachment_type';
    public const HAS_ATTACHMENT = 'has_attachment';
    public const ATTACHMENT_SIZE = 'attachment_size';
    public const MESSAGE_SIZE = 'message_size';
    public const SPAM = 'spam';
    public const HOUR = 'hour';
    public const WEEKDAY = 'weekday';

    public static function values(): array
    {
        return [
            self::FROM,
            self::FROM_DOMAIN,
            self::ENVELOPE_FROM,
            self::TO,
            self::CC,
            self::BCC,
            self::RECIPIENT,
            self::REPLY_TO,
            self::DELIVERED_TO,
            self::SUBJECT,
            self::BODY,
            self::HEADER,
            self::LIST_ID,
            self::ATTACHMENT_NAME,
            self::ATTACHMENT_TYPE,
            self::HAS_ATTACHMENT,
            self::ATTACHMENT_SIZE,
            self::MESSAGE_SIZE,
            self::SPAM,
            self::HOUR,
            self::WEEKDAY,
        ];
    }
}
