<?php

namespace MadArlan\PDFBridge\Exceptions;

/**
 * Exception thrown when input validation fails
 */
class ValidationException extends PDFBridgeException
{
    public function __construct(string $message = '', int $code = 0, ?\Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
