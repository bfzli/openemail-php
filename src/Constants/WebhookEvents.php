<?php

declare(strict_types=1);

namespace OpenEmail\Constants;

final class WebhookEvents
{
    public const EMAIL_RECEIVED = 'email.received';
    public const EMAIL_REPLIED = 'email.replied';
    public const EMAIL_SENT = 'email.sent';
    public const EMAIL_FAILED = 'email.failed';
    public const EMAIL_CANCELLED = 'email.cancelled';
    public const EMAIL_SCHEDULED = 'email.scheduled';
    public const EMAIL_QUEUED = 'email.queued';
    public const EMAIL_DELIVERED = 'email.delivered';
    public const EMAIL_DELIVERY_DELAYED = 'email.delivery_delayed';
    public const EMAIL_BOUNCED = 'email.bounced';
    public const EMAIL_COMPLAINED = 'email.complained';
    public const EMAIL_SUPPRESSED = 'email.suppressed';
    public const EMAIL_OPENED = 'email.opened';
    public const EMAIL_CLICKED = 'email.clicked';
    public const EMAIL_DOWNLOADED = 'email.downloaded';
    public const DOMAIN_VERIFIED = 'domain.verified';
    public const DOMAIN_SENDING_CHANGED = 'domain.sending_changed';
    public const DOMAIN_DELETED = 'domain.deleted';
    public const SUPPRESSION_ADDED = 'suppression.added';
    public const SUPPRESSION_REMOVED = 'suppression.removed';
    public const FILE_UPLOADED = 'file.uploaded';
    public const FILE_DELETED = 'file.deleted';
    public const FORM_SUBMITTED = 'form.submitted';
    public const FORM_CONFIRMED = 'form.confirmed';

    public static function values(): array
    {
        return [
            self::EMAIL_RECEIVED,
            self::EMAIL_REPLIED,
            self::EMAIL_SENT,
            self::EMAIL_FAILED,
            self::EMAIL_CANCELLED,
            self::EMAIL_SCHEDULED,
            self::EMAIL_QUEUED,
            self::EMAIL_DELIVERED,
            self::EMAIL_DELIVERY_DELAYED,
            self::EMAIL_BOUNCED,
            self::EMAIL_COMPLAINED,
            self::EMAIL_SUPPRESSED,
            self::EMAIL_OPENED,
            self::EMAIL_CLICKED,
            self::EMAIL_DOWNLOADED,
            self::DOMAIN_VERIFIED,
            self::DOMAIN_SENDING_CHANGED,
            self::DOMAIN_DELETED,
            self::SUPPRESSION_ADDED,
            self::SUPPRESSION_REMOVED,
            self::FILE_UPLOADED,
            self::FILE_DELETED,
            self::FORM_SUBMITTED,
            self::FORM_CONFIRMED,
        ];
    }
}
