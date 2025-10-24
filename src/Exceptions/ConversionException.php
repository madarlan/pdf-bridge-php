<?php

namespace MadArlan\PDFBridge\Exceptions;

/**
 * Exception thrown when PDF conversion fails
 */
class ConversionException extends PDFBridgeException
{
    /**
     * Create a new conversion exception instance
     *
     * @param string $message
     * @param string|null $converter
     * @param \Throwable|null $previous
     */
    public function __construct(string $message = '', ?string $converter = null, ?\Throwable $previous = null)
    {
        if ($converter) {
            $message = "[$converter] $message";
        }
        
        parent::__construct($message, 0, $previous);
    }
}