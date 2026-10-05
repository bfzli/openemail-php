<?php

declare(strict_types=1);

namespace OpenEmail\Constants;

final class ContactPhotoTypes
{
    public const PNG = 'image/png';
    public const JPEG = 'image/jpeg';
    public const WEBP = 'image/webp';
    public const GIF = 'image/gif';

    public static function values(): array
    {
        return [self::PNG, self::JPEG, self::WEBP, self::GIF];
    }
}
