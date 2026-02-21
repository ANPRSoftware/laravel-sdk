<?php

namespace Anpr\LaravelSdk\Exceptions;

class RateLimitException extends AnprException
{
    public function __construct(
        string $message,
        public readonly ?int $retryAfterSeconds = null,
        ?\Throwable $previous = null,
    ) {
        parent::__construct($message, 429, $previous);
    }
}
