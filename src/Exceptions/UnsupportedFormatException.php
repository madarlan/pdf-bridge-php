<?php

namespace MadArlan\PDFBridge\Exceptions;

/**
 * Exception thrown when trying to convert unsupported file format
 */
class UnsupportedFormatException extends PDFBridgeException
{
    /**
     * Create a new unsupported format exception instance
     *
     * @param string $format
     * @param array $supportedFormats
     */
    public function __construct(string $format, array $supportedFormats = [])
    {
        $message = "Unsupported format: $format";
        
        if (!empty($supportedFormats)) {
            $message .= '. Supported formats: ' . implode(', ', $supportedFormats);
        }
        
        parent::__construct($message);
    }
}