<?php

declare(strict_types=1);

namespace RelayWarden\Exceptions;

/**
 * Exception thrown when rate limit is exceeded.
 */
class RateLimitException extends ApiException
{
    protected int $retryAfter;

    public function __construct(
        string $message,
        int $code = 429,
        ?Exception $previous = null,
        string $requestId = '',
        int $retryAfter = 60
    ) {
        parent::__construct($message, $code, $previous, $requestId, 'rate_limit_exceeded');
        $this->retryAfter = $retryAfter;
    }

    public function getRetryAfter(): int
    {
        return $this->retryAfter;
    }
}
