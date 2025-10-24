<?php

namespace MadArlan\PDFBridge\Contracts;

/**
 * Interface for PDF converters
 */
interface ConverterInterface
{
    /**
     * Get supported formats
     */
    public function getSupportedFormats(): array;

    /**
     * Check if converter is available
     */
    public function isAvailable(): bool;

    /**
     * Get converter version if available
     */
    public function getVersion(): ?string;
}
