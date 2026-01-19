<?php

declare(strict_types=1);

namespace RelayWarden\Exceptions;

/**
 * Exception thrown when request validation fails.
 */
class ValidationException extends ApiException
{
    public function __construct(
        string $message,
        array $errorDetails = [],
        string $requestId = '',
        int $code = 422
    ) {
        parent::__construct($message, $code, null, $requestId, 'validation_error', $errorDetails);
    }
}
