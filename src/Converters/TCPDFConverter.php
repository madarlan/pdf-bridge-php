<?php

namespace MadArlan\PDFBridge\Converters;

use MadArlan\PDFBridge\Contracts\TextConverterInterface;
use MadArlan\PDFBridge\Exceptions\ConversionException;
use MadArlan\PDFBridge\Exceptions\ConverterNotAvailableException;
use TCPDF;

/**
 * TCPDF converter for text and HTML to PDF conversion
 */
class TCPDFConverter implements TextConverterInterface
{
    protected array $config;

    public function __construct(array $config = [])
    {
        $this->config = $config;
        $this->checkAvailability();
    }

    /**
     * Check if TCPDF is available
     *
     * @throws ConverterNotAvailableException
     */
    protected function checkAvailability(): void
    {
        if (! class_exists(TCPDF::class)) {
            throw new ConverterNotAvailableException('TCPDF', 'TCPDF class not found. Please install tecnickcom/tcpdf package.');
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
            $pdf = $this->createPDF($options);
            $pdf->AddPage();

            // Set font
            $fontFamily = $options['font_family'] ?? $this->config['font']['family'] ?? 'helvetica';
            $fontSize = $options['font_size'] ?? $this->config['font']['size'] ?? 12;
            $fontStyle = $options['font_style'] ?? $this->config['font']['style'] ?? '';

            $pdf->SetFont($fontFamily, $fontStyle, $fontSize);

            // Add text
            $pdf->MultiCell(0, 10, $text, 0, 'L');

            return $this->outputPDF($pdf, $outputPath);
        } catch (\Exception $e) {
            throw new ConversionException('Failed to convert text to PDF: '.$e->getMessage(), 'TCPDF', $e);
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
            $pdf = $this->createPDF($options);
            $pdf->AddPage();

            // Set font
            $fontFamily = $options['font_family'] ?? $this->config['font']['family'] ?? 'helvetica';
            $fontSize = $options['font_size'] ?? $this->config['font']['size'] ?? 12;

            $pdf->SetFont($fontFamily, '', $fontSize);

            // Convert HTML to PDF
            $pdf->writeHTML($html, true, false, true, false, '');

            return $this->outputPDF($pdf, $outputPath);
        } catch (\Exception $e) {
            throw new ConversionException('Failed to convert HTML to PDF: '.$e->getMessage(), 'TCPDF', $e);
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
            $pdf = $this->createPDF($options);
            $pdf->AddPage();

            // Set font
            $fontFamily = $options['font_family'] ?? $this->config['font']['family'] ?? 'helvetica';
            $fontSize = $options['font_size'] ?? $this->config['font']['size'] ?? 10;

            $pdf->SetFont($fontFamily, '', $fontSize);

            // Parse CSV
            $lines = str_getcsv($csvContent, "\n");
            $delimiter = $options['csv_delimiter'] ?? ',';

            // Create table
            foreach ($lines as $line) {
                $fields = str_getcsv($line, $delimiter);
                $cellWidth = 180 / count($fields); // Distribute width evenly

                foreach ($fields as $field) {
                    $pdf->Cell($cellWidth, 8, $field, 1, 0, 'C');
                }
                $pdf->Ln();
            }

            return $this->outputPDF($pdf, $outputPath);
        } catch (\Exception $e) {
            throw new ConversionException('Failed to convert CSV to PDF: '.$e->getMessage(), 'TCPDF', $e);
        }
    }

    /**
     * Create TCPDF instance with configuration
     */
    protected function createPDF(array $options = []): TCPDF
    {
        $orientation = $options['orientation'] ?? $this->config['orientation'] ?? 'P';
        $unit = $options['unit'] ?? $this->config['unit'] ?? 'mm';
        $format = $options['format'] ?? $this->config['format'] ?? 'A4';
        $unicode = $options['unicode'] ?? $this->config['unicode'] ?? true;
        $encoding = $options['encoding'] ?? $this->config['encoding'] ?? 'UTF-8';
        $diskcache = $options['diskcache'] ?? $this->config['diskcache'] ?? false;
        $pdfa = $options['pdfa'] ?? $this->config['pdfa'] ?? false;

        $pdf = new TCPDF($orientation, $unit, $format, $unicode, $encoding, $diskcache, $pdfa);

        // Set document information
        $pdf->SetCreator('PDF Bridge');
        $pdf->SetAuthor($options['author'] ?? 'PDF Bridge');
        $pdf->SetTitle($options['title'] ?? 'Generated PDF');
        $pdf->SetSubject($options['subject'] ?? '');
        $pdf->SetKeywords($options['keywords'] ?? '');

        // Set margins
        $margins = $this->config['margins'] ?? [];
        $pdf->SetMargins(
            $options['margin_left'] ?? $margins['left'] ?? 15,
            $options['margin_top'] ?? $margins['top'] ?? 27,
            $options['margin_right'] ?? $margins['right'] ?? 15
        );

        $pdf->SetHeaderMargin($options['margin_header'] ?? $margins['header'] ?? 5);
        $pdf->SetFooterMargin($options['margin_footer'] ?? $margins['footer'] ?? 10);

        // Set auto page breaks
        $pdf->SetAutoPageBreak(true, $options['margin_bottom'] ?? $margins['bottom'] ?? 25);

        // Set header and footer
        if ($options['header_enabled'] ?? $this->config['header']['enabled'] ?? false) {
            $pdf->SetHeaderData(
                '', 0,
                $options['header_title'] ?? $this->config['header']['title'] ?? '',
                $options['header_string'] ?? $this->config['header']['string'] ?? ''
            );
        } else {
            $pdf->setPrintHeader(false);
        }

        if (! ($options['footer_enabled'] ?? $this->config['footer']['enabled'] ?? false)) {
            $pdf->setPrintFooter(false);
        }

        return $pdf;
    }

    /**
     * Output PDF to file or return as string
     */
    protected function outputPDF(TCPDF $pdf, ?string $outputPath = null): string
    {
        if ($outputPath) {
            // Ensure directory exists
            $directory = dirname($outputPath);
            if (! is_dir($directory)) {
                mkdir($directory, 0755, true);
            }

            $pdf->Output($outputPath, 'F');

            return $outputPath;
        }

        return $pdf->Output('', 'S');
    }

    /**
     * Get supported formats
     */
    public function getSupportedFormats(): array
    {
        return ['text', 'html', 'csv'];
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
            if (class_exists(TCPDF::class)) {
                return defined('TCPDF_VERSION') ? TCPDF_VERSION : 'Unknown';
            }
        } catch (\Exception $e) {
            // Ignore
        }

        return null;
    }
}
