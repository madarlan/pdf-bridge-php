<?php

namespace MadArlan\PDFBridge\Exceptions;

/**
 * Exception thrown when PDF conversion fails
 */
class ConversionException extends PDFBridgeException
{
    /**
     * Create a new conversion exception instance
     */
    public function __construct(string $message = '', ?string $converter = null, ?\Throwable $previous = null)
    {
        if ($converter) {
            $message = "[$converter] $message";
        }

        parent::__construct($message, 0, $previous);
    }
}
