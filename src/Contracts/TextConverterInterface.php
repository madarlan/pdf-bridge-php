<?php

namespace MadArlan\PDFBridge\Contracts;

use MadArlan\PDFBridge\Exceptions\ConversionException;

/**
 * Interface for text-based converters
 */
interface TextConverterInterface extends ConverterInterface
{
    /**
     * Convert text to PDF
     *
     * @throws ConversionException
     */
    public function convertText(string $text, ?string $outputPath = null, array $options = []): string;

    /**
     * Convert HTML to PDF
     *
     * @throws ConversionException
     */
    public function convertHTML(string $html, ?string $outputPath = null, array $options = []): string;

    /**
     * Convert CSV to PDF
     *
     * @throws ConversionException
     */
    public function convertCSV(string $csvContent, ?string $outputPath = null, array $options = []): string;
}
