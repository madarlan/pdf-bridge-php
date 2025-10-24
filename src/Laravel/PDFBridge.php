<?php

namespace MadArlan\PDFBridge\Laravel;

use Illuminate\Support\Facades\Facade;

/**
 * Laravel Facade for PDF Bridge
 *
 * @method static string convertText(string $text, string|null $outputPath = null, array $options = [])
 * @method static string convertHTML(string $html, string|null $outputPath = null, array $options = [])
 * @method static string convertCSV(string $csvContent, string|null $outputPath = null, array $options = [])
 * @method static string convertDocument(string $inputPath, string|null $outputPath = null, array $options = [])
 * @method static string convertSpreadsheet(string $inputPath, string|null $outputPath = null, array $options = [])
 * @method static string convertFile(string $inputPath, string|null $outputPath = null, array $options = [])
 * @method static array getSupportedFormats()
 * @method static array getAvailableConverters()
 * @method static \MadArlan\PDFBridge\PDFBridge setConfig(array $config)
 * @method static array getConfig()
 *
 * @see \MadArlan\PDFBridge\PDFBridge
 */
class PDFBridge extends Facade
{
    /**
     * Get the registered name of the component.
     */
    protected static function getFacadeAccessor(): string
    {
        return 'pdf-bridge';
    }
}
