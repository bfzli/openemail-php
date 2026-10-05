<?php

declare(strict_types=1);

namespace OpenEmail\Constants;

final class FileKinds
{
    public const IMAGE = 'image';
    public const PDF = 'pdf';
    public const AUDIO = 'audio';
    public const VIDEO = 'video';
    public const TEXT = 'text';

    public static function values(): array
    {
        return [self::IMAGE, self::PDF, self::AUDIO, self::VIDEO, self::TEXT];
    }
}
