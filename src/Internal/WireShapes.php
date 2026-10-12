<?php

declare(strict_types=1);

namespace OpenEmail\Internal;

final class WireShapes
{
    public const OBJECT_KEYS = [
        'condition' => true,
        'copy' => true,
        'definition' => true,
        'document' => true,
        'headers' => true,
        'loginBackground' => true,
        'nameParts' => true,
        'properties' => true,
        'send' => true,
        'sendWindow' => true,
        'settings' => true,
        'style' => true,
        'tags' => true,
        'timeout' => true,
        'tracking' => true,
        'translate' => true,
        'trigger' => true,
        'values' => true,
        'wait' => true,
        'webFont' => true,
    ];
    public const OBJECT_PATHS = [
        '.data' => true,
        '.fonts' => true,
        '.options' => true,
        'steps.from' => true,
        'steps.props' => true,
        'steps.value' => true,
        'template.props' => true,
        'template.slots' => true,
        'values.props' => true,
        'values.slots' => true,
    ];
}
