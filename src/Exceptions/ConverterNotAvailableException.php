<?php

namespace MadArlan\PDFBridge\Exceptions;

/**
 * Exception thrown when a PDF converter is not available or properly configured
 */
class ConverterNotAvailableException extends PDFBridgeException
{
    /**
     * Create a new converter not available exception instance
     *
     * @param string $converter
     * @param string|null $reason
     */
    public function __construct(string $converter, ?string $reason = null)
    {
        $message = "Converter '$converter' is not available";
        
        if ($reason) {
            $message .= ": $reason";
        }
        
        parent::__construct($message);
    }
}