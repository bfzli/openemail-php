<?php

declare(strict_types=1);

namespace OpenEmail\Constants;

final class MessageEncryptionFormats
{
    public const PGP_MIME = 'pgp-mime';
    public const PGP_SIGNED = 'pgp-signed';
    public const PGP_INLINE = 'pgp-inline';
    public const SMIME_ENCRYPTED = 'smime-encrypted';
    public const SMIME_SIGNED = 'smime-signed';

    public static function values(): array
    {
        return [self::PGP_MIME, self::PGP_SIGNED, self::PGP_INLINE, self::SMIME_ENCRYPTED, self::SMIME_SIGNED];
    }
}
