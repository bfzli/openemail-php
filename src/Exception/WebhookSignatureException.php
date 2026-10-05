<?php

declare(strict_types=1);

namespace OpenEmail\Exception;

final class WebhookSignatureException extends \UnexpectedValueException implements OpenEmailException {}
