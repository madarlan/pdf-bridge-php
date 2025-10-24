<?php

namespace MadArlan\PDFBridge\Validation;

use MadArlan\PDFBridge\Exceptions\ValidationException;

/**
 * Input validation class
 */
class InputValidator
{
    protected array $config;

    public function __construct(array $config = [])
    {
        $this->config = array_merge([
            'max_file_size' => 50 * 1024 * 1024, // 50MB
            'allowed_extensions' => [
                'txt', 'html', 'htm', 'csv',
                'doc', 'docx', 'odt', 'rtf',
                'xls', 'xlsx', 'ods',
                'ppt', 'pptx', 'odp',
            ],
            'max_text_length' => 1024 * 1024, // 1MB
        ], $config);
    }

    /**
     * Validate file input
     *
     * @throws ValidationException
     */
    public function validateFile(string $filePath): void
    {
        if (! file_exists($filePath)) {
            throw new ValidationException("File not found: {$filePath}");
        }

        if (! is_readable($filePath)) {
            throw new ValidationException("File is not readable: {$filePath}");
        }

        // Check file size
        $fileSize = filesize($filePath);
        if ($fileSize > $this->config['max_file_size']) {
            $maxSizeMB = round($this->config['max_file_size'] / (1024 * 1024), 2);
            $fileSizeMB = round($fileSize / (1024 * 1024), 2);
            throw new ValidationException(
                "File size ({$fileSizeMB}MB) exceeds maximum allowed size ({$maxSizeMB}MB)"
            );
        }

        // Check file extension
        $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
        if (! in_array($extension, $this->config['allowed_extensions'])) {
            throw new ValidationException(
                "Unsupported file extension: {$extension}. Allowed: ".
                implode(', ', $this->config['allowed_extensions'])
            );
        }
    }

    /**
     * Validate text input
     *
     * @throws ValidationException
     */
    public function validateText(string $text): void
    {
        if (empty(trim($text))) {
            throw new ValidationException('Text content cannot be empty');
        }

        if (strlen($text) > $this->config['max_text_length']) {
            $maxLengthKB = round($this->config['max_text_length'] / 1024, 2);
            $textLengthKB = round(strlen($text) / 1024, 2);
            throw new ValidationException(
                "Text length ({$textLengthKB}KB) exceeds maximum allowed length ({$maxLengthKB}KB)"
            );
        }
    }

    /**
     * Validate output path
     *
     * @throws ValidationException
     */
    public function validateOutputPath(string $outputPath): void
    {
        $directory = dirname($outputPath);

        if (! is_dir($directory) && ! mkdir($directory, 0755, true)) {
            throw new ValidationException("Cannot create output directory: {$directory}");
        }

        if (! is_writable($directory)) {
            throw new ValidationException("Output directory is not writable: {$directory}");
        }

        // Check if file already exists and is writable
        if (file_exists($outputPath) && ! is_writable($outputPath)) {
            throw new ValidationException("Output file exists and is not writable: {$outputPath}");
        }
    }

    /**
     * Validate CSV content
     *
     * @throws ValidationException
     */
    public function validateCSV(string $csvContent, array $options = []): void
    {
        $this->validateText($csvContent);

        $delimiter = $options['csv_delimiter'] ?? ',';
        $lines = explode("\n", trim($csvContent));

        if (count($lines) < 1) {
            throw new ValidationException('CSV must contain at least one line');
        }

        // Check if all lines have consistent number of columns
        $firstLineColumns = count(str_getcsv($lines[0], $delimiter));

        foreach ($lines as $lineNumber => $line) {
            if (empty(trim($line))) {
                continue;
            }

            $columns = count(str_getcsv($line, $delimiter));
            if ($columns !== $firstLineColumns) {
                throw new ValidationException(
                    'CSV line '.($lineNumber + 1)." has {$columns} columns, expected {$firstLineColumns}"
                );
            }
        }
    }

    /**
     * Get configuration
     */
    public function getConfig(): array
    {
        return $this->config;
    }

    /**
     * Set configuration
     */
    public function setConfig(array $config): self
    {
        $this->config = array_merge($this->config, $config);

        return $this;
    }
}
