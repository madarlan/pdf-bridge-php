<?php

namespace MadArlan\PDFBridge\Converters;

use NcJoes\OfficeConverter\OfficeConverter as LibreOfficeConverter;
use MadArlan\PDFBridge\Exceptions\ConversionException;
use MadArlan\PDFBridge\Exceptions\ConverterNotAvailableException;
use MadArlan\PDFBridge\Exceptions\UnsupportedFormatException;
use MadArlan\PDFBridge\Contracts\FileConverterInterface;

/**
 * LibreOffice converter for DOC/DOCX/XLS/XLSX to PDF conversion
 */
class OfficeConverter implements FileConverterInterface
{
    protected array $config;
    protected ?LibreOfficeConverter $converter = null;

    public function __construct(array $config = [])
    {
        $this->config = $config;
        $this->checkAvailability();
    }

    /**
     * Check if LibreOffice and office-converter are available
     *
     * @throws ConverterNotAvailableException
     */
    protected function checkAvailability(): void
    {
        if (!class_exists(LibreOfficeConverter::class)) {
            throw new ConverterNotAvailableException(
                'LibreOffice', 
                'office-converter class not found. Please install ncjoes/office-converter package.'
            );
        }

        // Check if LibreOffice is installed on the system
        $libreOfficePath = $this->config['libreoffice_path'] ?? null;
        
        if ($libreOfficePath && !file_exists($libreOfficePath)) {
            throw new ConverterNotAvailableException(
                'LibreOffice',
                "LibreOffice not found at specified path: {$libreOfficePath}"
            );
        }

        // Try to detect LibreOffice automatically if path not specified
        if (!$libreOfficePath) {
            $possiblePaths = $this->getLibreOfficePaths();
            $found = false;
            
            foreach ($possiblePaths as $path) {
                if (file_exists($path)) {
                    $this->config['libreoffice_path'] = $path;
                    $found = true;
                    break;
                }
            }
            
            if (!$found) {
                throw new ConverterNotAvailableException(
                    'LibreOffice',
                    'LibreOffice not found. Please install LibreOffice or specify the correct path in configuration.'
                );
            }
        }
    }

    /**
     * Convert DOC/DOCX file to PDF
     *
     * @param string $inputPath
     * @param string|null $outputPath
     * @param array $options
     * @return string
     * @throws ConversionException
     * @throws UnsupportedFormatException
     */
    public function convertDocument(string $inputPath, ?string $outputPath = null, array $options = []): string
    {
        if (!file_exists($inputPath)) {
            throw new ConversionException("Input file not found: {$inputPath}", 'LibreOffice');
        }

        $extension = strtolower(pathinfo($inputPath, PATHINFO_EXTENSION));
        $documentFormats = ['doc', 'docx', 'odt', 'rtf'];
        if (!in_array($extension, $documentFormats)) {
            throw new UnsupportedFormatException($extension, $documentFormats);
        }

        return $this->performConversion($inputPath, $outputPath, $options);
    }

    /**
     * Convert XLS/XLSX file to PDF
     *
     * @param string $inputPath
     * @param string|null $outputPath
     * @param array $options
     * @return string
     * @throws ConversionException
     * @throws UnsupportedFormatException
     */
    public function convertSpreadsheet(string $inputPath, ?string $outputPath = null, array $options = []): string
    {
        if (!file_exists($inputPath)) {
            throw new ConversionException("Input file not found: {$inputPath}", 'LibreOffice');
        }

        $extension = strtolower(pathinfo($inputPath, PATHINFO_EXTENSION));
        $spreadsheetFormats = ['xls', 'xlsx', 'ods', 'csv'];
        if (!in_array($extension, $spreadsheetFormats)) {
            throw new UnsupportedFormatException($extension, $spreadsheetFormats);
        }

        return $this->performConversion($inputPath, $outputPath, $options);
    }

    /**
     * Convert PPT/PPTX file to PDF
     *
     * @param string $inputPath
     * @param string|null $outputPath
     * @param array $options
     * @return string
     * @throws ConversionException
     * @throws UnsupportedFormatException
     */
    public function convertPresentation(string $inputPath, ?string $outputPath = null, array $options = []): string
    {
        if (!file_exists($inputPath)) {
            throw new ConversionException("Input file not found: {$inputPath}", 'LibreOffice');
        }

        $extension = strtolower(pathinfo($inputPath, PATHINFO_EXTENSION));
        $presentationFormats = ['ppt', 'pptx', 'odp'];
        if (!in_array($extension, $presentationFormats)) {
            throw new UnsupportedFormatException($extension, $presentationFormats);
        }

        return $this->performConversion($inputPath, $outputPath, $options);
    }

    /**
     * Convert any supported office file to PDF
     *
     * @param string $inputPath
     * @param string|null $outputPath
     * @param array $options
     * @return string
     * @throws ConversionException
     * @throws UnsupportedFormatException
     */
    public function convert(string $inputPath, ?string $outputPath = null, array $options = []): string
    {
        if (!file_exists($inputPath)) {
            throw new ConversionException("Input file not found: {$inputPath}", 'LibreOffice');
        }

        $extension = strtolower(pathinfo($inputPath, PATHINFO_EXTENSION));
        if (!in_array($extension, $this->getSupportedFormats())) {
            throw new UnsupportedFormatException($extension, $this->getSupportedFormats());
        }

        return $this->performConversion($inputPath, $outputPath, $options);
    }

    /**
     * Perform the actual conversion using LibreOffice
     *
     * @param string $inputPath
     * @param string|null $outputPath
     * @param array $options
     * @return string
     * @throws ConversionException
     */
    protected function performConversion(string $inputPath, ?string $outputPath = null, array $options = []): string
    {
        try {
            $converter = $this->getConverter();
            
            // Set temporary directory if specified
            if (!empty($this->config['temp_dir'])) {
                $converter->setTempPath($this->config['temp_dir']);
            }
            
            // Set output directory
            $outputDir = $outputPath ? dirname($outputPath) : sys_get_temp_dir();
            if (!is_dir($outputDir)) {
                mkdir($outputDir, 0755, true);
            }
            
            // Perform conversion
            $converter->convertTo($inputPath, $outputDir, 'pdf');
            
            // Determine the output file path
            $baseName = pathinfo($inputPath, PATHINFO_FILENAME);
            $generatedPath = $outputDir . DIRECTORY_SEPARATOR . $baseName . '.pdf';
            
            if (!file_exists($generatedPath)) {
                throw new ConversionException(
                    "Conversion completed but output file not found: {$generatedPath}",
                    'LibreOffice'
                );
            }
            
            // Move to desired output path if specified
            if ($outputPath && $outputPath !== $generatedPath) {
                $outputDirectory = dirname($outputPath);
                if (!is_dir($outputDirectory)) {
                    mkdir($outputDirectory, 0755, true);
                }
                
                if (!rename($generatedPath, $outputPath)) {
                    throw new ConversionException(
                        "Failed to move converted file from {$generatedPath} to {$outputPath}",
                        'LibreOffice'
                    );
                }
                return $outputPath;
            }
            
            return $generatedPath;
            
        } catch (\Exception $e) {
            if ($e instanceof ConversionException) {
                throw $e;
            }
            
            throw new ConversionException(
                "LibreOffice conversion failed: " . $e->getMessage(),
                'LibreOffice',
                $e
            );
        }
    }

    /**
     * Get LibreOffice converter instance
     *
     * @return LibreOfficeConverter
     */
    protected function getConverter(): LibreOfficeConverter
    {
        if ($this->converter === null) {
            $this->converter = new LibreOfficeConverter(
                $this->config['libreoffice_path'],
                $this->config['temp_dir'] ?? null,
                $this->config['timeout'] ?? 120
            );
        }
        
        return $this->converter;
    }

    /**
     * Get possible LibreOffice installation paths
     *
     * @return array
     */
    protected function getLibreOfficePaths(): array
    {
        $paths = [];
        
        // Windows paths
        if (PHP_OS_FAMILY === 'Windows') {
            $paths = [
                'C:\Program Files\LibreOffice\program\soffice.exe',
                'C:\Program Files (x86)\LibreOffice\program\soffice.exe',
                'C:\Program Files\LibreOffice 7\program\soffice.exe',
                'C:\Program Files (x86)\LibreOffice 7\program\soffice.exe',
                'C:\Program Files\LibreOffice 6\program\soffice.exe',
                'C:\Program Files (x86)\LibreOffice 6\program\soffice.exe',
            ];
        }
        // Linux paths
        elseif (PHP_OS_FAMILY === 'Linux') {
            $paths = [
                '/usr/bin/libreoffice',
                '/usr/bin/soffice',
                '/opt/libreoffice/program/soffice',
                '/snap/bin/libreoffice',
                '/usr/local/bin/libreoffice',
                '/usr/local/bin/soffice',
            ];
        }
        // macOS paths
        elseif (PHP_OS_FAMILY === 'Darwin') {
            $paths = [
                '/Applications/LibreOffice.app/Contents/MacOS/soffice',
                '/usr/local/bin/libreoffice',
                '/usr/local/bin/soffice',
            ];
        }
        
        return $paths;
    }

    /**
     * Get supported file formats
     *
     * @return array
     */
    public function getSupportedFormats(): array
    {
        return [
            'doc', 'docx', 'odt', 'rtf',
            'xls', 'xlsx', 'ods', 'csv',
            'ppt', 'pptx', 'odp'
        ];
    }

    /**
     * Check if LibreOffice is properly configured
     *
     * @return bool
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
     * Get LibreOffice version information
     *
     * @return string|null
     */
    public function getVersion(): ?string
    {
        try {
            $libreOfficePath = $this->config['libreoffice_path'] ?? null;
            if (!$libreOfficePath) {
                return null;
            }
            
            $command = escapeshellarg($libreOfficePath) . ' --version';
            $output = shell_exec($command);
            
            return $output ? trim($output) : null;
        } catch (\Exception $e) {
            return null;
        }
    }
}