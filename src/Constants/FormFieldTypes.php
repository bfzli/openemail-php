<?php

declare(strict_types=1);

namespace OpenEmail\Constants;

final class FormFieldTypes
{
    public const EMAIL = 'email';
    public const TEXT = 'text';
    public const TEXTAREA = 'textarea';
    public const NUMBER = 'number';
    public const PHONE = 'phone';
    public const URL = 'url';
    public const DATE = 'date';
    public const SELECT = 'select';
    public const RADIO = 'radio';
    public const CHECKBOXES = 'checkboxes';
    public const CHECKBOX = 'checkbox';
    public const CONSENT = 'consent';
    public const AUDIENCES = 'audiences';
    public const HIDDEN = 'hidden';
    public const HEADING = 'heading';
    public const PARAGRAPH = 'paragraph';
    public const DIVIDER = 'divider';

    public static function values(): array
    {
        return [
            self::EMAIL,
            self::TEXT,
            self::TEXTAREA,
            self::NUMBER,
            self::PHONE,
            self::URL,
            self::DATE,
            self::SELECT,
            self::RADIO,
            self::CHECKBOXES,
            self::CHECKBOX,
            self::CONSENT,
            self::AUDIENCES,
            self::HIDDEN,
            self::HEADING,
            self::PARAGRAPH,
            self::DIVIDER,
        ];
    }
}
