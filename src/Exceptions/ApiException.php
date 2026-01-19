<?php

declare(strict_types=1);

namespace RelayWarden\Exceptions;

use Exception;

/**
 * Base exception for all API errors.
 */
class ApiException extends Exception
{
    protected string $requestId;

    protected string $errorCode;

    protected array $errorDetails = [];

    public function __construct(
        string $message,
        int $code = 0,
        ?Exception $previous = null,
        string $requestId = '',
        string $errorCode = '',
        array $errorDetails = []
    ) {
        parent::__construct($message, $code, $previous);
        $this->requestId = $requestId;
        $this->errorCode = $errorCode;
        $this->errorDetails = $errorDetails;
    }

    public function getRequestId(): string
    {
        return $this->requestId;
    }

    public function getErrorCode(): string
    {
        return $this->errorCode;
    }

    public function getErrorDetails(): array
    {
        return $this->errorDetails;
    }
}
