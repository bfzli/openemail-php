<?php

declare(strict_types=1);

namespace OpenEmail\Constants;

final class TemplateImageTypes
{
    public const PNG = 'image/png';
    public const JPEG = 'image/jpeg';
    public const WEBP = 'image/webp';
    public const GIF = 'image/gif';
    public const SVG = 'image/svg+xml';

    public static function values(): array
    {
        return [self::PNG, self::JPEG, self::WEBP, self::GIF, self::SVG];
    }
}
