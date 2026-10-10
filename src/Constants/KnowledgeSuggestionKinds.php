<?php

declare(strict_types=1);

namespace OpenEmail\Constants;

final class KnowledgeSuggestionKinds
{
    public const LEARNED = 'learned';
    public const QUESTION = 'question';

    public static function values(): array
    {
        return [self::LEARNED, self::QUESTION];
    }
}
