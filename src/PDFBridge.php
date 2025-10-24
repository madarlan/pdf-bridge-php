<?php

namespace MadArlan\PDFBridge;

use MadArlan\PDFBridge\Converters\MPDFConverter;
use MadArlan\PDFBridge\Converters\OfficeConverter;
use MadArlan\PDFBridge\Converters\TCPDFConverter;
use MadArlan\PDFBridge\Exceptions\ConversionException;
use MadArlan\PDFBridge\Exceptions\ConverterNotAvailableException;
use MadArlan\PDFBridge\Exceptions\UnsupportedFormatException;
use MadArlan\PDFBridge\Exceptions\ValidationException;
use MadArlan\PDFBridge\Support\Logger;
use MadArlan\PDFBridge\Validation\InputValidator;
use Psr\Log\LoggerInterface;

/**
 * Main PDFBridge class for unified PDF conversion
 */
class PDFBridge
{
    protected array $config;

    protected ?TCPDFConverter $tcpdfConverter = null;

    protected ?MPDFConverter $mpdfConverter = null;

    protected ?OfficeConverter $officeConverter = null;

    protected InputValidator $validator;

    protected Logger $logger;

    public function __construct(array $config = [], ?LoggerInterface $logger = null)
    {
        $this->config = $config;
        $this->validator = new InputValidator($config['validation'] ?? []);
        $this->logger = new Logger($logger, $config['logging']['enabled'] ?? true);
    }

    /**
     * Convert text to PDF
     *
     * @throws ConversionException
     */
    public function convertText(string $text, ?string $outputPath = null, array $options = []): string
    {
        $startTime = microtime(true);

        try {
            // Validate input
            $this->validator->validateText($text);
            if ($outputPath) {
                $this->validator->validateOutputPath($outputPath);
            }

            $converter = $this->getPreferredConverter('text', $options);
            $this->logger->logConversionStart('text', $text, $converter);

            $result = match ($converter) {
                'tcpdf' => $this->getTCPDFConverter()->convertText($text, $outputPath, $options),
                'mpdf' => $this->getMPDFConverter()->convertText($text, $outputPath, $options),
                default => throw new ConversionException('No suitable converter found for text conversion')
            };

            $duration = microtime(true) - $startTime;
            $fileSize = $outputPath && file_exists($outputPath) ? filesize($outputPath) : 0;
            $this->logger->logConversionSuccess('text', $result, $duration, $fileSize);

            return $result;
        } catch (ValidationException $e) {
            $this->logger->logValidationError('text', $e->getMessage());
            throw $e;
        } catch (\Exception $e) {
            $this->logger->logConversionError('text', $e->getMessage(), $e);
            throw $e;
        }
    }

    /**
     * Convert HTML to PDF
     *
     * @throws ConversionException
     */
    public function convertHTML(string $html, ?string $outputPath = null, array $options = []): string
    {
        $startTime = microtime(true);

        try {
            // Validate input
            $this->validator->validateText($html);
            if ($outputPath) {
                $this->validator->validateOutputPath($outputPath);
            }

            $converter = $this->getPreferredConverter('html', $options);
            $this->logger->logConversionStart('html', $html, $converter);

            $result = match ($converter) {
                'mpdf' => $this->getMPDFConverter()->convertHTML($html, $outputPath, $options),
                'tcpdf' => $this->getTCPDFConverter()->convertHTML($html, $outputPath, $options),
                default => throw new ConversionException('No suitable converter found for HTML conversion')
            };

            $duration = microtime(true) - $startTime;
            $fileSize = $outputPath && file_exists($outputPath) ? filesize($outputPath) : 0;
            $this->logger->logConversionSuccess('html', $result, $duration, $fileSize);

            return $result;
        } catch (ValidationException $e) {
            $this->logger->logValidationError('html', $e->getMessage());
            throw $e;
        } catch (\Exception $e) {
            $this->logger->logConversionError('html', $e->getMessage(), $e);
            throw $e;
        }
    }

    /**
     * Convert CSV to PDF
     *
     * @throws ConversionException
     */
    public function convertCSV(string $csvContent, ?string $outputPath = null, array $options = []): string
    {
        $startTime = microtime(true);

        try {
            // Validate input
            $this->validator->validateCSV($csvContent, $options);
            if ($outputPath) {
                $this->validator->validateOutputPath($outputPath);
            }

            $converter = $this->getPreferredConverter('csv', $options);
            $this->logger->logConversionStart('csv', $csvContent, $converter);

            $result = match ($converter) {
                'tcpdf' => $this->getTCPDFConverter()->convertCSV($csvContent, $outputPath, $options),
                'mpdf' => $this->getMPDFConverter()->convertCSV($csvContent, $outputPath, $options),
                default => throw new ConversionException('No suitable converter found for CSV conversion')
            };

            $duration = microtime(true) - $startTime;
            $fileSize = $outputPath && file_exists($outputPath) ? filesize($outputPath) : 0;
            $this->logger->logConversionSuccess('csv', $result, $duration, $fileSize);

            return $result;
        } catch (ValidationException $e) {
            $this->logger->logValidationError('csv', $e->getMessage());
            throw $e;
        } catch (\Exception $e) {
            $this->logger->logConversionError('csv', $e->getMessage(), $e);
            throw $e;
        }
    }

    /**
     * Convert DOC/DOCX file to PDF
     *
     * @throws ConversionException
     * @throws UnsupportedFormatException
     */
    public function convertDocument(string $inputPath, ?string $outputPath = null, array $options = []): string
    {
        if (! file_exists($inputPath)) {
            throw new ConversionException("Input file not found: {$inputPath}");
        }

        $extension = strtolower(pathinfo($inputPath, PATHINFO_EXTENSION));
        if (! in_array($extension, ['doc', 'docx'])) {
            throw new UnsupportedFormatException($extension, ['doc', 'docx']);
        }

        return $this->getOfficeConverter()->convertDocument($inputPath, $outputPath, $options);
    }

    /**
     * Convert XLS/XLSX file to PDF
     *
     * @throws ConversionException
     * @throws UnsupportedFormatException
     */
    public function convertSpreadsheet(string $inputPath, ?string $outputPath = null, array $options = []): string
    {
        if (! file_exists($inputPath)) {
            throw new ConversionException("Input file not found: {$inputPath}");
        }

        $extension = strtolower(pathinfo($inputPath, PATHINFO_EXTENSION));
        if (! in_array($extension, ['xls', 'xlsx'])) {
            throw new UnsupportedFormatException($extension, ['xls', 'xlsx']);
        }

        return $this->getOfficeConverter()->convertSpreadsheet($inputPath, $outputPath, $options);
    }

    /**
     * Convert PPT/PPTX file to PDF
     *
     * @throws ConversionException
     * @throws UnsupportedFormatException
     */
    public function convertPresentation(string $inputPath, ?string $outputPath = null, array $options = []): string
    {
        $startTime = microtime(true);

        try {
            $this->validator->validateFile($inputPath);
            if ($outputPath) {
                $this->validator->validateOutputPath($outputPath);
            }

            $this->logger->logConversionStart('presentation', $inputPath, 'libreoffice');

            $result = $this->getOfficeConverter()->convertPresentation($inputPath, $outputPath, $options);

            $duration = microtime(true) - $startTime;
            $fileSize = file_exists($result) ? filesize($result) : 0;
            $this->logger->logConversionSuccess('presentation', $result, $duration, $fileSize);

            return $result;
        } catch (ValidationException $e) {
            $this->logger->logValidationError('presentation', $e->getMessage());
            throw $e;
        } catch (\Exception $e) {
            $this->logger->logConversionError('presentation', $e->getMessage(), $e);
            throw $e;
        }
    }

    /**
     * Convert any supported file to PDF (auto-detect format)
     *
     * @throws ConversionException
     * @throws UnsupportedFormatException
     */
    public function convertFile(string $inputPath, ?string $outputPath = null, array $options = []): string
    {
        if (! file_exists($inputPath)) {
            throw new ConversionException("Input file not found: {$inputPath}");
        }

        $extension = strtolower(pathinfo($inputPath, PATHINFO_EXTENSION));

        return match ($extension) {
            'txt' => $this->convertText(file_get_contents($inputPath), $outputPath, $options),
            'html', 'htm' => $this->convertHTML(file_get_contents($inputPath), $outputPath, $options),
            'csv' => $this->convertCSV(file_get_contents($inputPath), $outputPath, $options),
            'doc', 'docx', 'odt', 'rtf' => $this->convertDocument($inputPath, $outputPath, $options),
            'xls', 'xlsx', 'ods' => $this->convertSpreadsheet($inputPath, $outputPath, $options),
            'ppt', 'pptx', 'odp' => $this->convertPresentation($inputPath, $outputPath, $options),
            default => throw new UnsupportedFormatException($extension, $this->getSupportedFormats())
        };
    }

    /**
     * Get preferred converter for a specific format
     *
     * @throws ConversionException
     */
    protected function getPreferredConverter(string $format, array $options = []): string
    {
        // Check if converter is explicitly specified in options
        if (! empty($options['converter'])) {
            $requestedConverter = strtolower($options['converter']);
            if ($this->isConverterAvailable($requestedConverter, $format)) {
                return $requestedConverter;
            }
            throw new ConversionException("Requested converter '{$requestedConverter}' is not available for {$format} conversion");
        }

        // Use default converter from config
        $defaultConverter = $this->config['default_converter'] ?? 'mpdf';
        if ($this->isConverterAvailable($defaultConverter, $format)) {
            return $defaultConverter;
        }

        // Try converter priority for the format
        $priorities = $this->config['converter_priority'][$format] ?? ['mpdf', 'tcpdf'];
        foreach ($priorities as $converter) {
            if ($this->isConverterAvailable($converter, $format)) {
                return $converter;
            }
        }

        throw new ConversionException("No available converter found for {$format} conversion");
    }

    /**
     * Check if a converter is available for a specific format
     */
    protected function isConverterAvailable(string $converter, string $format): bool
    {
        try {
            switch ($converter) {
                case 'tcpdf':
                    $conv = $this->getTCPDFConverter();

                    return in_array($format, $conv->getSupportedFormats());

                case 'mpdf':
                    $conv = $this->getMPDFConverter();

                    return in_array($format, $conv->getSupportedFormats());

                case 'libreoffice':
                    $conv = $this->getOfficeConverter();

                    return in_array($format, $conv->getSupportedFormats());

                default:
                    return false;
            }
        } catch (ConverterNotAvailableException $e) {
            return false;
        }
    }

    /**
     * Get TCPDF converter instance
     *
     * @throws ConverterNotAvailableException
     */
    protected function getTCPDFConverter(): TCPDFConverter
    {
        if ($this->tcpdfConverter === null) {
            $config = $this->config['tcpdf'] ?? [];
            $this->tcpdfConverter = new TCPDFConverter($config);
        }

        return $this->tcpdfConverter;
    }

    /**
     * Get mPDF converter instance
     *
     * @throws ConverterNotAvailableException
     */
    protected function getMPDFConverter(): MPDFConverter
    {
        if ($this->mpdfConverter === null) {
            $config = $this->config['mpdf'] ?? [];
            $this->mpdfConverter = new MPDFConverter($config);
        }

        return $this->mpdfConverter;
    }

    /**
     * Get Office converter instance
     *
     * @throws ConverterNotAvailableException
     */
    protected function getOfficeConverter(): OfficeConverter
    {
        if ($this->officeConverter === null) {
            $config = $this->config['libreoffice'] ?? [];
            $this->officeConverter = new OfficeConverter($config);
        }

        return $this->officeConverter;
    }

    /**
     * Get all supported file formats
     */
    public function getSupportedFormats(): array
    {
        $formats = [];

        try {
            $formats = array_merge($formats, $this->getTCPDFConverter()->getSupportedFormats());
        } catch (ConverterNotAvailableException $e) {
            // Ignore if not available
        }

        try {
            $formats = array_merge($formats, $this->getMPDFConverter()->getSupportedFormats());
        } catch (ConverterNotAvailableException $e) {
            // Ignore if not available
        }

        try {
            $formats = array_merge($formats, $this->getOfficeConverter()->getSupportedFormats());
        } catch (ConverterNotAvailableException $e) {
            // Ignore if not available
        }

        // Add file formats
        $formats = array_merge($formats, ['txt', 'htm']);

        return array_unique($formats);
    }

    /**
     * Get available converters with their status
     */
    public function getAvailableConverters(): array
    {
        $converters = [];

        try {
            $this->getTCPDFConverter();
            $converters['tcpdf'] = [
                'available' => true,
                'formats' => $this->getTCPDFConverter()->getSupportedFormats(),
            ];
        } catch (ConverterNotAvailableException $e) {
            $converters['tcpdf'] = [
                'available' => false,
                'error' => $e->getMessage(),
            ];
        }

        try {
            $this->getMPDFConverter();
            $converters['mpdf'] = [
                'available' => true,
                'formats' => $this->getMPDFConverter()->getSupportedFormats(),
            ];
        } catch (ConverterNotAvailableException $e) {
            $converters['mpdf'] = [
                'available' => false,
                'error' => $e->getMessage(),
            ];
        }

        try {
            $this->getOfficeConverter();
            $converters['libreoffice'] = [
                'available' => true,
                'formats' => $this->getOfficeConverter()->getSupportedFormats(),
                'version' => $this->getOfficeConverter()->getVersion(),
            ];
        } catch (ConverterNotAvailableException $e) {
            $converters['libreoffice'] = [
                'available' => false,
                'error' => $e->getMessage(),
            ];
        }

        return $converters;
    }

    /**
     * Set configuration
     */
    public function setConfig(array $config): self
    {
        $this->config = array_merge($this->config, $config);

        // Reset converter instances to apply new config
        $this->tcpdfConverter = null;
        $this->mpdfConverter = null;
        $this->officeConverter = null;

        return $this;
    }

    /**
     * Get current configuration
     */
    public function getConfig(): array
    {
        return $this->config;
    }
}
