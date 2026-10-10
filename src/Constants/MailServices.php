<?php

declare(strict_types=1);

namespace OpenEmail\Constants;

final class MailServices
{
    public const OPENEMAIL = 'openemail';
    public const GMAIL = 'gmail';
    public const OUTLOOK = 'outlook';
    public const ICLOUD = 'icloud';
    public const YAHOO = 'yahoo';
    public const PROTON = 'proton';
    public const ZOHO = 'zoho';
    public const FASTMAIL = 'fastmail';
    public const RESEND = 'resend';
    public const SENDGRID = 'sendgrid';
    public const MAILGUN = 'mailgun';
    public const POSTMARK = 'postmark';
    public const MAILCHIMP = 'mailchimp';
    public const BREVO = 'brevo';
    public const SPARKPOST = 'sparkpost';
    public const HUBSPOT = 'hubspot';
    public const AMAZON_SES = 'amazon-ses';

    public static function values(): array
    {
        return [
            self::OPENEMAIL,
            self::GMAIL,
            self::OUTLOOK,
            self::ICLOUD,
            self::YAHOO,
            self::PROTON,
            self::ZOHO,
            self::FASTMAIL,
            self::RESEND,
            self::SENDGRID,
            self::MAILGUN,
            self::POSTMARK,
            self::MAILCHIMP,
            self::BREVO,
            self::SPARKPOST,
            self::HUBSPOT,
            self::AMAZON_SES,
        ];
    }
}
