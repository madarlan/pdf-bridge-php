<?php

namespace MadArlan\PDFBridge\Contracts;

/**
 * Interface for PDF converters
 */
interface ConverterInterface
{
    /**
     * Get supported formats
     *
     * @return array
     */
    public function getSupportedFormats(): array;

    /**
     * Check if converter is available
     *
     * @return bool
     */
    public function isAvailable(): bool;

    /**
     * Get converter version if available
     *
     * @return string|null
     */
    public function getVersion(): ?string;
}
