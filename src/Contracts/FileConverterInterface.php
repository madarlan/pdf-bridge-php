<?php

namespace MadArlan\PDFBridge\Contracts;

use MadArlan\PDFBridge\Exceptions\ConversionException;
use MadArlan\PDFBridge\Exceptions\UnsupportedFormatException;

/**
 * Interface for file-based converters
 */
interface FileConverterInterface extends ConverterInterface
{
    /**
     * Convert file to PDF
     *
     * @throws ConversionException
     * @throws UnsupportedFormatException
     */
    public function convert(string $inputPath, ?string $outputPath = null, array $options = []): string;

    /**
     * Convert document to PDF
     *
     * @throws ConversionException
     * @throws UnsupportedFormatException
     */
    public function convertDocument(string $inputPath, ?string $outputPath = null, array $options = []): string;

    /**
     * Convert spreadsheet to PDF
     *
     * @throws ConversionException
     * @throws UnsupportedFormatException
     */
    public function convertSpreadsheet(string $inputPath, ?string $outputPath = null, array $options = []): string;
}
