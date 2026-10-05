<?php

declare(strict_types=1);

namespace OpenEmail\Internal;

final class WireShapes
{
    public const OBJECT_KEYS = [
        'copy' => true,
        'document' => true,
        'headers' => true,
        'loginBackground' => true,
        'settings' => true,
        'style' => true,
        'tags' => true,
        'template' => true,
        'tracking' => true,
        'translate' => true,
        'values' => true,
        'webFont' => true,
    ];
    public const OBJECT_PATHS = [
        '.fonts' => true,
        '.options' => true,
        'template.props' => true,
        'template.slots' => true,
        'values.props' => true,
        'values.slots' => true,
    ];
}
