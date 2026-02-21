<?php

namespace Anpr\LaravelSdk\Exceptions;

use Exception;

class AnprException extends Exception
{
    public function __construct(
        string $message = '',
        int $code = 0,
        ?\Throwable $previous = null,
        public readonly ?array $context = null,
    ) {
        parent::__construct($message, $code, $previous);
    }
}
