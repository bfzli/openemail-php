<?php

declare(strict_types=1);

namespace OpenEmail\Constants;

final class MailboxSettingsSources
{
    public const PRESET = 'preset';
    public const MX = 'mx';
    public const AUTOCONFIG = 'autoconfig';
    public const SRV = 'srv';
    public const GIVEN = 'given';

    public static function values(): array
    {
        return [self::PRESET, self::MX, self::AUTOCONFIG, self::SRV, self::GIVEN];
    }
}
