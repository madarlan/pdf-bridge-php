<?php

namespace MadArlan\PDFBridge\Console;

use Illuminate\Console\Command;
use MadArlan\PDFBridge\PDFBridge;
use MadArlan\PDFBridge\Exceptions\UnsupportedFormatException;
use MadArlan\PDFBridge\Exceptions\ConverterNotAvailableException;

class PDFConvertCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pdf:convert 
                            {input : Input file or text for conversion}
                            {--output= : Output PDF file (default: input.pdf)}
                            {--type= : Input data type (auto|text|html|csv|doc|docx|xls|xlsx)}
                            {--converter= : Preferred converter (tcpdf|mpdf|libreoffice)}
                            {--config= : JSON configuration for converter}
                            {--check : Check converter availability}
                            {--diagnose : LibreOffice diagnostics}
                            {--list-formats : Show supported formats}
                            {--list-converters : Show available converters}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Quick conversion of files and text to PDF';

    /**
     * PDF Bridge instance
     *
     * @var PDFBridge
     */
    protected $pdfBridge;

    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        parent::__construct();
        $this->pdfBridge = new PDFBridge();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        // Check converter availability
        if ($this->option('check')) {
            return $this->checkConverters();
        }

        // LibreOffice diagnostics
        if ($this->option('diagnose')) {
            return $this->diagnoseLibreOffice();
        }

        // List supported formats
        if ($this->option('list-formats')) {
            return $this->listFormats();
        }

        // List available converters
        if ($this->option('list-converters')) {
            return $this->listConverters();
        }

        // Main conversion
        return $this->performConversion();
    }

    /**
     * Perform the main conversion
     *
     * @return int
     */
    protected function performConversion(): int
    {
        $input = $this->argument('input');
        $output = $this->option('output');
        $type = $this->option('type');
        $converter = $this->option('converter');
        $config = $this->option('config');

        try {
            // Parse configuration
            if ($config) {
                $configArray = json_decode($config, true);
                if (json_last_error() !== JSON_ERROR_NONE) {
                    $this->error('Invalid JSON configuration format: ' . json_last_error_msg());
                    return 1;
                }
                $this->pdfBridge->setConfig($configArray);
            }

            // Set preferred converter
            if ($converter) {
                $this->pdfBridge->setConfig(['preferred_converter' => $converter]);
            }

            // Detect input data type
            if (!$type) {
                $type = $this->detectInputType($input);
                $this->info("Auto-detected type: {$type}");
            }

            // Determine output file
            if (!$output) {
                $output = $this->generateOutputPath($input, $type);
            }

            $this->info("Converting: {$input} -> {$output}");
            $this->info("Type: {$type}");

            // Perform conversion
            $result = $this->convertByType($input, $output, $type);

            if ($result) {
                $this->info("✓ Conversion completed successfully!");
                $this->info("Created file: {$output}");
                
                // Show file size
                if (file_exists($output)) {
                    $size = $this->formatBytes(filesize($output));
                    $this->info("File size: {$size}");
                }
                
                return 0;
            } else {
                $this->error("✗ Conversion error");
                return 1;
            }

        } catch (UnsupportedFormatException $e) {
            $this->error("✗ Unsupported format: " . $e->getMessage());
            $this->info("Supported formats: " . implode(', ', $e->getSupportedFormats()));
            return 1;
        } catch (ConverterNotAvailableException $e) {
            $this->error("✗ Converter not available: " . $e->getMessage());
            $this->info("Use --check to verify available converters");
            return 1;
        } catch (\Exception $e) {
            $this->error("✗ Error: " . $e->getMessage());
            return 1;
        }
    }

    /**
     * Convert by detected type
     *
     * @param string $input
     * @param string $output
     * @param string $type
     * @return bool
     */
    protected function convertByType(string $input, string $output, string $type): bool
    {
        switch ($type) {
            case 'text':
                return $this->convertText($input, $output);
            case 'html':
                return $this->convertHtml($input, $output);
            case 'csv':
                return $this->convertCsv($input, $output);
            case 'doc':
            case 'docx':
                return $this->convertDocument($input, $output);
            case 'xls':
            case 'xlsx':
                return $this->convertSpreadsheet($input, $output);
            default:
                throw new UnsupportedFormatException("Unsupported type: {$type}");
        }
    }

    /**
     * Convert text to PDF
     */
    protected function convertText(string $input, string $output): bool
    {
        $content = $this->getInputContent($input);
        return $this->pdfBridge->convertText($content, $output);
    }

    /**
     * Convert HTML to PDF
     */
    protected function convertHtml(string $input, string $output): bool
    {
        $content = $this->getInputContent($input);
        return $this->pdfBridge->convertHtml($content, $output);
    }

    /**
     * Convert CSV to PDF
     */
    protected function convertCsv(string $input, string $output): bool
    {
        if (file_exists($input)) {
            return $this->pdfBridge->convertCsv($input, $output);
        } else {
            // If this is a CSV string
            $tempFile = tempnam(sys_get_temp_dir(), 'csv_');
            file_put_contents($tempFile, $input);
            $result = $this->pdfBridge->convertCsv($tempFile, $output);
            unlink($tempFile);
            return $result;
        }
    }

    /**
     * Convert document to PDF
     */
    protected function convertDocument(string $input, string $output): bool
    {
        if (!file_exists($input)) {
            throw new \InvalidArgumentException("File not found: {$input}");
        }
        return $this->pdfBridge->convertDocument($input, $output);
    }

    /**
     * Convert spreadsheet to PDF
     */
    protected function convertSpreadsheet(string $input, string $output): bool
    {
        if (!file_exists($input)) {
            throw new \InvalidArgumentException("File not found: {$input}");
        }
        return $this->pdfBridge->convertSpreadsheet($input, $output);
    }

    /**
     * Get input content (from file or direct text)
     */
    protected function getInputContent(string $input): string
    {
        if (file_exists($input)) {
            return file_get_contents($input);
        }
        return $input;
    }

    /**
     * Detect input type automatically
     */
    protected function detectInputType(string $input): string
    {
        // If this is a file
        if (file_exists($input)) {
            $extension = strtolower(pathinfo($input, PATHINFO_EXTENSION));
            
            switch ($extension) {
                case 'txt':
                    return 'text';
                case 'html':
                case 'htm':
                    return 'html';
                case 'csv':
                    return 'csv';
                case 'doc':
                    return 'doc';
                case 'docx':
                    return 'docx';
                case 'xls':
                    return 'xls';
                case 'xlsx':
                    return 'xlsx';
                default:
                    // Try to determine by content
                    $content = file_get_contents($input);
                    return $this->detectContentType($content);
            }
        }
        
        // If this is a string, determine by content
        return $this->detectContentType($input);
    }

    /**
     * Detect content type by analyzing content
     */
    protected function detectContentType(string $content): string
    {
        // HTML detection
        if (preg_match('/<\s*html\s*>/i', $content) || 
            preg_match('/<\s*body\s*>/i', $content) ||
            preg_match('/<\s*div\s*>/i', $content) ||
            preg_match('/<\s*p\s*>/i', $content)) {
            return 'html';
        }

        // CSV detection (simple check)
        $lines = explode("\n", trim($content));
        if (count($lines) > 1) {
            $firstLine = $lines[0];
            $secondLine = $lines[1];
            
            // Check for separators
            $separators = [',', ';', '\t'];
            foreach ($separators as $sep) {
                if (substr_count($firstLine, $sep) > 0 && 
                    substr_count($firstLine, $sep) === substr_count($secondLine, $sep)) {
                    return 'csv';
                }
            }
        }

        // Default - text
        return 'text';
    }

    /**
     * Generate output path
     */
    protected function generateOutputPath(string $input, string $type): string
    {
        if (file_exists($input)) {
            $pathInfo = pathinfo($input);
            return $pathInfo['dirname'] . DIRECTORY_SEPARATOR . $pathInfo['filename'] . '.pdf';
        }
        
        return 'output.pdf';
    }

    /**
     * Check available converters
     */
    protected function checkConverters(): int
    {
        $this->info("=== Checking converter availability ===");
        
        $converters = $this->pdfBridge->getAvailableConverters();
        
        foreach ($converters as $name => $info) {
            if ($info['available']) {
                $this->info("✓ {$name}: " . implode(', ', $info['formats']));
                if (isset($info['version'])) {
                    $this->line("  Version: {$info['version']}");
                }
            } else {
                $this->error("✗ {$name}: {$info['error']}");
            }
        }
        
        return 0;
    }

    /**
     * Diagnose LibreOffice installation
     */
    protected function diagnoseLibreOffice(): int
    {
        $this->info("=== LibreOffice Diagnostics ===");
        
        $diagnosis = $this->performLibreOfficeDiagnosis();
        
        $this->info("office-converter package: " . ($diagnosis['package_installed'] ? '✓' : '✗'));
        
        if (!empty($diagnosis['libreoffice_paths'])) {
            $this->info("Found LibreOffice paths:");
            foreach ($diagnosis['libreoffice_paths'] as $path) {
                $this->line("  - {$path}");
            }
            $this->info("Used path: " . $diagnosis['found_path']);
            $this->info("Version: " . ($diagnosis['version'] ?? 'not determined'));
        } else {
            $this->warn("LibreOffice not found");
        }
        
        if (!empty($diagnosis['errors'])) {
            $this->error("Errors:");
            foreach ($diagnosis['errors'] as $error) {
                $this->line("  - {$error}");
            }
        }
        
        return 0;
    }

    /**
     * Perform LibreOffice diagnosis
     */
    protected function performLibreOfficeDiagnosis(): array
    {
        $diagnosis = [
            'package_installed' => class_exists('NcJoes\OfficeConverter\OfficeConverter'),
            'libreoffice_paths' => [],
            'found_path' => null,
            'version' => null,
            'errors' => []
        ];
        
        if (!$diagnosis['package_installed']) {
            $diagnosis['errors'][] = 'Package ncjoes/office-converter is not installed. Run: composer require ncjoes/office-converter';
            return $diagnosis;
        }
        
        // Search for LibreOffice in standard locations
        $possiblePaths = [
            // Linux
            '/usr/bin/libreoffice',
            '/usr/bin/soffice',
            '/opt/libreoffice/program/soffice',
            '/snap/bin/libreoffice',
            // Windows
            'C:\Program Files\LibreOffice\program\soffice.exe',
            'C:\Program Files (x86)\LibreOffice\program\soffice.exe',
            // macOS
            '/Applications/LibreOffice.app/Contents/MacOS/soffice'
        ];
        
        foreach ($possiblePaths as $path) {
            if (file_exists($path)) {
                $diagnosis['libreoffice_paths'][] = $path;
                if (!$diagnosis['found_path']) {
                    $diagnosis['found_path'] = $path;
                    
                    // Attempt to get version
                    $command = escapeshellarg($path) . ' --version 2>&1';
                    $output = shell_exec($command);
                    $diagnosis['version'] = $output ? trim($output) : null;
                }
            }
        }
        
        if (empty($diagnosis['libreoffice_paths'])) {
            $diagnosis['errors'][] = 'LibreOffice not found in standard installation locations';
            $diagnosis['errors'][] = 'Install LibreOffice or specify path in configuration';
        }
        
        return $diagnosis;
    }

    /**
     * List supported formats
     */
    protected function listFormats(): int
    {
        $this->info("=== Supported Formats ===");
        
        $formats = $this->pdfBridge->getSupportedFormats();
        
        foreach ($formats as $format) {
            $this->line("• {$format}");
        }
        
        return 0;
    }

    /**
     * List available converters
     */
    protected function listConverters(): int
    {
        $this->info("=== Available Converters ===");
        
        $converters = $this->pdfBridge->getAvailableConverters();
        
        foreach ($converters as $name => $info) {
            if ($info['available']) {
                $this->info("✓ {$name}");
                $this->line("  Formats: " . implode(', ', $info['formats']));
                if (isset($info['version'])) {
                    $this->line("  Version: {$info['version']}");
                }
            } else {
                $this->warn("✗ {$name} (unavailable)");
                $this->line("  Reason: {$info['error']}");
            }
        }
        
        return 0;
    }

    /**
     * Format bytes to human readable format
     */
    protected function formatBytes(int $bytes, int $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, $precision) . ' ' . $units[$i];
    }
}