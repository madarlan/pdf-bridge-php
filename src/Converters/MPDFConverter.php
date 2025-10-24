<?php

namespace MadArlan\PDFBridge\Converters;

use MadArlan\PDFBridge\Contracts\TextConverterInterface;
use MadArlan\PDFBridge\Exceptions\ConversionException;
use MadArlan\PDFBridge\Exceptions\ConverterNotAvailableException;
use Mpdf\Mpdf;
use Mpdf\MpdfException;

/**
 * mPDF converter for HTML and text to PDF conversion
 */
class MPDFConverter implements TextConverterInterface
{
    protected array $config;

    public function __construct(array $config = [])
    {
        $this->config = $config;
        $this->checkAvailability();
    }

    /**
     * Check if mPDF is available
     *
     * @throws ConverterNotAvailableException
     */
    protected function checkAvailability(): void
    {
        if (! class_exists(Mpdf::class)) {
            throw new ConverterNotAvailableException('mPDF', 'mPDF class not found. Please install mpdf/mpdf package.');
        }
    }

    /**
     * Convert HTML to PDF
     *
     * @throws ConversionException
     */
    public function convertHTML(string $html, ?string $outputPath = null, array $options = []): string
    {
        try {
            $mpdf = $this->createMPDF($options);

            // Set CSS if provided
            if (! empty($options['css'])) {
                $mpdf->WriteHTML($options['css'], \Mpdf\HTMLParserMode::HEADER_CSS);
            }

            // Write HTML content
            $mpdf->WriteHTML($html, \Mpdf\HTMLParserMode::HTML_BODY);

            return $this->outputPDF($mpdf, $outputPath);
        } catch (MpdfException $e) {
            throw new ConversionException('Failed to convert HTML to PDF: '.$e->getMessage(), 'mPDF', $e);
        } catch (\Exception $e) {
            throw new ConversionException('Failed to convert HTML to PDF: '.$e->getMessage(), 'mPDF', $e);
        }
    }

    /**
     * Convert text to PDF
     *
     * @throws ConversionException
     */
    public function convertText(string $text, ?string $outputPath = null, array $options = []): string
    {
        try {
            // Convert text to HTML with basic formatting
            $html = $this->textToHTML($text, $options);

            return $this->convertHTML($html, $outputPath, $options);
        } catch (\Exception $e) {
            throw new ConversionException('Failed to convert text to PDF: '.$e->getMessage(), 'mPDF', $e);
        }
    }

    /**
     * Convert CSV to PDF
     *
     * @throws ConversionException
     */
    public function convertCSV(string $csvContent, ?string $outputPath = null, array $options = []): string
    {
        try {
            $html = $this->csvToHTML($csvContent, $options);

            return $this->convertHTML($html, $outputPath, $options);
        } catch (\Exception $e) {
            throw new ConversionException('Failed to convert CSV to PDF: '.$e->getMessage(), 'mPDF', $e);
        }
    }

    /**
     * Create mPDF instance with configuration
     *
     * @throws MpdfException
     */
    protected function createMPDF(array $options = []): Mpdf
    {
        $config = [
            'mode' => $options['mode'] ?? $this->config['mode'] ?? 'utf-8',
            'format' => $options['format'] ?? $this->config['format'] ?? 'A4',
            'default_font_size' => $options['default_font_size'] ?? $this->config['default_font_size'] ?? 12,
            'default_font' => $options['default_font'] ?? $this->config['default_font'] ?? 'dejavusans',
            'margin_left' => $options['margin_left'] ?? $this->config['margin_left'] ?? 15,
            'margin_right' => $options['margin_right'] ?? $this->config['margin_right'] ?? 15,
            'margin_top' => $options['margin_top'] ?? $this->config['margin_top'] ?? 16,
            'margin_bottom' => $options['margin_bottom'] ?? $this->config['margin_bottom'] ?? 16,
            'margin_header' => $options['margin_header'] ?? $this->config['margin_header'] ?? 9,
            'margin_footer' => $options['margin_footer'] ?? $this->config['margin_footer'] ?? 9,
            'orientation' => $options['orientation'] ?? $this->config['orientation'] ?? 'P',
        ];

        // Add advanced options if available
        if (! empty($this->config['tempDir'])) {
            $config['tempDir'] = $this->config['tempDir'];
        }

        if (! empty($this->config['fontDir'])) {
            $config['fontDir'] = $this->config['fontDir'];
        }

        $mpdf = new Mpdf($config);

        // Set document properties
        $mpdf->SetTitle($options['title'] ?? 'Generated PDF');
        $mpdf->SetAuthor($options['author'] ?? 'PDF Bridge');
        $mpdf->SetCreator('PDF Bridge');
        $mpdf->SetSubject($options['subject'] ?? '');
        $mpdf->SetKeywords($options['keywords'] ?? '');

        // Set header and footer if provided
        if (! empty($options['header'])) {
            $mpdf->SetHTMLHeader($options['header']);
        }

        if (! empty($options['footer'])) {
            $mpdf->SetHTMLFooter($options['footer']);
        }

        return $mpdf;
    }

    /**
     * Convert plain text to HTML with basic formatting
     */
    protected function textToHTML(string $text, array $options = []): string
    {
        $fontSize = $options['font_size'] ?? $this->config['default_font_size'] ?? 12;
        $fontFamily = $options['font_family'] ?? $this->config['default_font'] ?? 'dejavusans';

        // Escape HTML entities
        $text = htmlspecialchars($text, ENT_QUOTES, 'UTF-8');

        // Convert line breaks to HTML
        $text = nl2br($text);

        // Wrap in basic HTML structure
        $html = "<!DOCTYPE html>
<html>
<head>
    <meta charset='UTF-8'>
    <style>
        body {
            font-family: {$fontFamily};
            font-size: {$fontSize}pt;
            line-height: 1.4;
        }
    </style>
</head>
<body>
    {$text}
</body>
</html>";

        return $html;
    }

    /**
     * Convert CSV to HTML table
     */
    protected function csvToHTML(string $csvContent, array $options = []): string
    {
        $delimiter = $options['csv_delimiter'] ?? ',';
        $hasHeader = $options['csv_has_header'] ?? true;
        $fontSize = $options['font_size'] ?? $this->config['default_font_size'] ?? 12;
        $fontFamily = $options['font_family'] ?? $this->config['default_font'] ?? 'dejavusans';

        $lines = str_getcsv($csvContent, "\n");
        $tableRows = [];
        $isFirstRow = true;

        foreach ($lines as $line) {
            if (empty(trim($line))) {
                continue;
            }

            $fields = str_getcsv($line, $delimiter);
            $cells = [];

            foreach ($fields as $field) {
                $field = htmlspecialchars($field, ENT_QUOTES, 'UTF-8');

                if ($hasHeader && $isFirstRow) {
                    $cells[] = "<th style='background-color: #f0f0f0; font-weight: bold; padding: 8px; border: 1px solid #ccc;'>{$field}</th>";
                } else {
                    $cells[] = "<td style='padding: 6px; border: 1px solid #ccc;'>{$field}</td>";
                }
            }

            $tableRows[] = '<tr>'.implode('', $cells).'</tr>';
            $isFirstRow = false;
        }

        $tableContent = implode("\n", $tableRows);

        $html = "<!DOCTYPE html>
<html>
<head>
    <meta charset='UTF-8'>
    <style>
        body {
            font-family: {$fontFamily};
            font-size: {$fontSize}pt;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0;
        }
        th, td {
            text-align: left;
            vertical-align: top;
        }
    </style>
</head>
<body>
    <table>
        {$tableContent}
    </table>
</body>
</html>";

        return $html;
    }

    /**
     * Output PDF to file or return as string
     *
     * @throws MpdfException
     */
    protected function outputPDF(Mpdf $mpdf, ?string $outputPath = null): string
    {
        if ($outputPath) {
            // Ensure directory exists
            $directory = dirname($outputPath);
            if (! is_dir($directory)) {
                mkdir($directory, 0755, true);
            }

            $mpdf->Output($outputPath, \Mpdf\Output\Destination::FILE);

            return $outputPath;
        }

        return $mpdf->Output('', \Mpdf\Output\Destination::STRING_RETURN);
    }

    /**
     * Get supported formats
     */
    public function getSupportedFormats(): array
    {
        return ['html', 'text', 'csv'];
    }

    /**
     * Check if converter is available
     */
    public function isAvailable(): bool
    {
        try {
            $this->checkAvailability();

            return true;
        } catch (ConverterNotAvailableException $e) {
            return false;
        }
    }

    /**
     * Get converter version
     */
    public function getVersion(): ?string
    {
        try {
            if (class_exists(Mpdf::class)) {
                return defined('Mpdf\\VERSION') ? \Mpdf\VERSION : 'Unknown';
            }
        } catch (\Exception $e) {
            // Ignore
        }

        return null;
    }
}
