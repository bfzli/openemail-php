<?php

declare(strict_types=1);

namespace OpenEmail\Constants;

final class ProviderImportProviders
{
    public const RESEND = 'resend';
    public const SENDGRID = 'sendgrid';
    public const POSTMARK = 'postmark';
    public const MAILGUN = 'mailgun';
    public const MAILCHIMP = 'mailchimp';

    public static function values(): array
    {
        return [self::RESEND, self::SENDGRID, self::POSTMARK, self::MAILGUN, self::MAILCHIMP];
    }
}
