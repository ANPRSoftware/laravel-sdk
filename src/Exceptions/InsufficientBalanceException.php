<?php

namespace Anpr\LaravelSdk\Exceptions;

class InsufficientBalanceException extends AnprException
{
    public function __construct(
        string $message,
        public readonly float $required,
        public readonly float $available,
        ?\Throwable $previous = null,
    ) {
        parent::__construct($message, 402, $previous);
    }
}
