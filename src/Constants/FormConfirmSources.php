<?php

declare(strict_types=1);

namespace OpenEmail\Constants;

final class FormConfirmSources
{
    public const LINK = 'link';
    public const APPROVAL = 'approval';

    public static function values(): array
    {
        return [self::LINK, self::APPROVAL];
    }
}
