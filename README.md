# PDF Bridge

[![Latest Version on Packagist](https://img.shields.io/packagist/v/madarlan/pdf-bridge.svg?style=flat-square)](https://packagist.org/packages/madarlan/pdf-bridge)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/madarlan/pdf-bridge/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/madarlan/pdf-bridge/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/madarlan/pdf-bridge/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/madarlan/pdf-bridge/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/madarlan/pdf-bridge.svg?style=flat-square)](https://packagist.org/packages/madarlan/pdf-bridge)
[![License](https://img.shields.io/packagist/l/madarlan/pdf-bridge.svg?style=flat-square)](https://packagist.org/packages/madarlan/pdf-bridge)
[![PHP Version Require](https://img.shields.io/packagist/php-v/madarlan/pdf-bridge.svg?style=flat-square)](https://packagist.org/packages/madarlan/pdf-bridge)

A powerful and universal Laravel package for converting various document formats to PDF using multiple converters (TCPDF, mPDF, LibreOffice). Features robust validation, comprehensive logging, and support for 15+ file formats.

> 🚀 **Quick Start**: New to PDF Bridge? Check out our [Quick Start Guide](QUICK_START.md) for a rapid introduction!

## ✨ Features

- 🔄 **Universal conversion**: 15+ formats → PDF (Text, HTML, CSV, DOC/DOCX, XLS/XLSX, PPT/PPTX, ODT, ODS, RTF, etc.)
- ⚡ **Multiple converters**: TCPDF, mPDF, LibreOffice with intelligent auto-selection
- 🛡️ **Input validation**: File size limits, format validation, content verification
- 📊 **Comprehensive logging**: Detailed operation tracking with PSR-3 compatibility
- 🔧 **Flexible configuration**: Individual settings for each converter with environment support
- 🔍 **Converter diagnostics**: Built-in tools for checking converter availability and LibreOffice installation
- 🎯 **Smart error handling**: Detailed exception handling with validation feedback
- 🚀 **Artisan commands**: Powerful CLI tools for conversion and diagnostics
- 🏗️ **Laravel integration**: Service provider, facades, and dependency injection ready
- 🧪 **Fully tested**: Comprehensive test suite with PHPUnit
- 📐 **Modern architecture**: Interfaces, contracts, and SOLID principles
- 🎨 **PHP 8.1+ ready**: Modern syntax with match expressions and typed properties

## Installation

### 1. Install the package

```bash
composer require madarlan/pdf-bridge
```

### 2. Publish configuration (optional)

```bash
php artisan vendor:publish --provider="MadArlan\PDFBridge\Laravel\PDFBridgeServiceProvider"
```

### 3. Install converters

#### TCPDF (installed automatically)
```bash
# Already included in the package
```

#### mPDF
```bash
composer require mpdf/mpdf
```

#### LibreOffice (for DOC/DOCX/XLS/XLSX)
```bash
# Install LibreOffice
# Ubuntu/Debian:
sudo apt-get install libreoffice

# CentOS/RHEL:
sudo yum install libreoffice

# Windows: Download from https://www.libreoffice.org/
# macOS: brew install --cask libreoffice

# Install PHP package
composer require ncjoes/office-converter
```

## ⚙️ Configuration

### Basic configuration

```php
// config/pdf-bridge.php
return [
    'default' => env('PDF_BRIDGE_DEFAULT_CONVERTER', 'mpdf'),
    
    // Converter configurations
    'tcpdf' => [
        'format' => 'A4',
        'orientation' => 'P',
        'font' => [
            'family' => 'helvetica',
            'size' => 12,
        ],
        'margins' => [
            'left' => 15,
            'top' => 27,
            'right' => 15,
            'bottom' => 25,
        ],
    ],
    
    'mpdf' => [
        'format' => 'A4',
        'default_font' => 'dejavusans',
        'default_font_size' => 12,
        'margin_left' => 15,
        'margin_right' => 15,
        'margin_top' => 16,
        'margin_bottom' => 16,
    ],
    
    'libreoffice' => [
        'bin' => env('PDF_BRIDGE_LIBREOFFICE_BIN', '/usr/bin/libreoffice'),
        'temp_dir' => env('PDF_BRIDGE_LIBREOFFICE_TEMP_DIR', sys_get_temp_dir()),
        'timeout' => env('PDF_BRIDGE_LIBREOFFICE_TIMEOUT', 120),
    ],
    
    // Validation settings
    'validation' => [
        'max_file_size' => env('PDF_BRIDGE_MAX_FILE_SIZE', 50 * 1024 * 1024), // 50MB
        'max_text_length' => env('PDF_BRIDGE_MAX_TEXT_LENGTH', 1024 * 1024), // 1MB
    ],
    
    // Logging configuration
    'logging' => [
        'enabled' => env('PDF_BRIDGE_LOGGING_ENABLED', true),
        'channel' => env('PDF_BRIDGE_LOG_CHANNEL', 'default'),
    ],
];
```

## 🚀 Usage

### Basic usage

```php
use MadArlan\PDFBridge\PDFBridge;

$pdfBridge = new PDFBridge();

// Convert text to PDF
$pdfBridge->convertText('Hello World!', 'output.pdf');

// Convert HTML to PDF
$html = '<h1>Title</h1><p>Content</p>';
$pdfBridge->convertHTML($html, 'output.pdf');

// Convert CSV to PDF
$csvContent = "Name,Age\nJohn,25\nJane,30";
$pdfBridge->convertCSV($csvContent, 'output.pdf');

// Convert documents (requires LibreOffice)
$pdfBridge->convertDocument('document.docx', 'output.pdf');
$pdfBridge->convertSpreadsheet('spreadsheet.xlsx', 'output.pdf');
$pdfBridge->convertPresentation('presentation.pptx', 'output.pdf');

// Auto-detect format and convert
$pdfBridge->convertFile('any-supported-file.odt', 'output.pdf');
```

### Using Laravel Facade

```php
use PDFBridge;

// Convert text
PDFBridge::convertText('Hello World!', 'output.pdf');

// Convert HTML
PDFBridge::convertHTML('<h1>Title</h1>', 'output.pdf');

// Convert presentations
PDFBridge::convertPresentation('slides.pptx', 'output.pdf');

// Auto-detect and convert
PDFBridge::convertFile('document.odt', 'output.pdf');
```

### Configuration management

```php
// Set configuration
$pdfBridge->setConfig([
    'default' => 'mpdf',
    'mpdf' => [
        'format' => 'A3',
        'orientation' => 'L',
    ],
    'validation' => [
        'max_file_size' => 10 * 1024 * 1024, // 10MB
    ]
]);

// Get current configuration
$config = $pdfBridge->getConfig();
```

### 🔍 Checking converter availability

```php
// Check all converters
$converters = $pdfBridge->getAvailableConverters();

foreach ($converters as $name => $info) {
    if ($info['available']) {
        echo "✓ {$name}: " . implode(', ', $info['formats']) . "\n";
        if (isset($info['version'])) {
            echo "  Version: {$info['version']}\n";
        }
    } else {
        echo "✗ {$name}: {$info['error']}\n";
    }
}

// Check specific converter
if ($pdfBridge->isConverterAvailable('tcpdf')) {
    echo "TCPDF is available\n";
}

if ($pdfBridge->isConverterAvailable('mpdf')) {
    echo "mPDF is available\n";
}

// Check LibreOffice
if ($pdfBridge->isConverterAvailable('libreoffice')) {
    echo "LibreOffice is available\n";
    
    // Get LibreOffice version
    $converters = $pdfBridge->getAvailableConverters();
    if (isset($converters['libreoffice']['version'])) {
        echo "LibreOffice version: " . $converters['libreoffice']['version'] . "\n";
    }
} else {
    echo "LibreOffice is not available\n";
    
    // Get error details
    $converters = $pdfBridge->getAvailableConverters();
    echo "Error: " . $converters['libreoffice']['error'] . "\n";
}
```

### 🛠️ LibreOffice troubleshooting & Validation

#### Input validation

```php
use MadArlan\PDFBridge\Exceptions\ValidationException;

try {
    $pdfBridge->convertText('', 'output.pdf'); // Will throw ValidationException
} catch (ValidationException $e) {
    echo "Validation error: " . $e->getMessage();
}

// Configure validation limits
$pdfBridge->setConfig([
    'validation' => [
        'max_file_size' => 10 * 1024 * 1024, // 10MB
        'max_text_length' => 500000, // 500KB
        'allowed_extensions' => ['txt', 'html', 'doc', 'docx']
    ]
]);
```

#### LibreOffice diagnostics

```php
/**
 * LibreOffice diagnostic function
 */
function diagnoseLibreOffice() {
    $diagnosis = [
        'package_installed' => class_exists('NcJoes\OfficeConverter\OfficeConverter'),
        'libreoffice_paths' => [],
        'found_path' => null,
        'version' => null,
        'errors' => []
    ];
    
    if (!$diagnosis['package_installed']) {
        $diagnosis['errors'][] = 'Package ncjoes/office-converter is not installed';
        return $diagnosis;
    }
    
    // Search for LibreOffice in standard locations
    $possiblePaths = [
        '/usr/bin/libreoffice',
        '/usr/bin/soffice',
        '/opt/libreoffice/program/soffice',
        'C:\Program Files\LibreOffice\program\soffice.exe',
        'C:\Program Files (x86)\LibreOffice\program\soffice.exe',
        '/Applications/LibreOffice.app/Contents/MacOS/soffice'
    ];
    
    foreach ($possiblePaths as $path) {
        if (file_exists($path)) {
            $diagnosis['libreoffice_paths'][] = $path;
            if (!$diagnosis['found_path']) {
                $diagnosis['found_path'] = $path;
                
                // Get version
                $output = shell_exec(escapeshellarg($path) . ' --version 2>&1');
                $diagnosis['version'] = $output ? trim($output) : null;
            }
        }
    }
    
    if (empty($diagnosis['libreoffice_paths'])) {
        $diagnosis['errors'][] = 'LibreOffice not found in standard locations';
    }
    
    return $diagnosis;
}

// Usage
$diagnosis = diagnoseLibreOffice();

echo "Package installed: " . ($diagnosis['package_installed'] ? 'Yes' : 'No') . "\n";

if (!empty($diagnosis['libreoffice_paths'])) {
    echo "Found LibreOffice paths:\n";
    foreach ($diagnosis['libreoffice_paths'] as $path) {
        echo "  - {$path}\n";
    }
    echo "Used path: " . $diagnosis['found_path'] . "\n";
    echo "Version: " . ($diagnosis['version'] ?? 'not determined') . "\n";
}

if (!empty($diagnosis['errors'])) {
    echo "Errors:\n";
    foreach ($diagnosis['errors'] as $error) {
        echo "  - {$error}\n";
    }
}
```

### 🎯 New format support

```php
// Document formats
$pdfBridge->convertFile('document.odt', 'output.pdf');  // OpenDocument Text
$pdfBridge->convertFile('document.rtf', 'output.pdf');  // Rich Text Format

// Spreadsheet formats  
$pdfBridge->convertFile('spreadsheet.ods', 'output.pdf'); // OpenDocument Spreadsheet

// Presentation formats
$pdfBridge->convertFile('presentation.odp', 'output.pdf'); // OpenDocument Presentation
$pdfBridge->convertPresentation('slides.ppt', 'output.pdf'); // PowerPoint
```

### 📊 Logging and monitoring

```php
use Psr\Log\LoggerInterface;

// With custom logger
$logger = app(LoggerInterface::class);
$pdfBridge = new PDFBridge($config, $logger);

// All operations are automatically logged:
// - Conversion start/success/failure
// - Validation errors
// - Converter availability checks
// - Performance metrics (duration, file size)
```

### 🎨 Advanced usage

```php
// Convert with options
$options = [
    'converter' => 'mpdf',
    'font_family' => 'Arial',
    'font_size' => 14,
    'orientation' => 'L', // Landscape
    'format' => 'A3'
];

$pdfBridge->convertHTML($html, 'output.pdf', $options);

// CSV with custom delimiter
$csvOptions = [
    'csv_delimiter' => ';',
    'csv_has_header' => true
];

$pdfBridge->convertCSV($csvContent, 'output.pdf', $csvOptions);
```

### 🎯 Artisan commands

The package includes an Artisan command for quick PDF conversion:

```bash
# Basic conversion
php artisan pdf:convert input.txt output.pdf

# Auto-detect input type
php artisan pdf:convert input.html

# Specify input type
php artisan pdf:convert "Hello World" --type=text --output=hello.pdf

# Use specific converter
php artisan pdf:convert input.html --converter=mpdf

# With JSON configuration
php artisan pdf:convert input.html --config='{"mpdf":{"format":"A3","orientation":"L"}}'

# Convert CSV
php artisan pdf:convert data.csv --type=csv

# Convert documents (requires LibreOffice)
php artisan pdf:convert document.docx
php artisan pdf:convert spreadsheet.xlsx
php artisan pdf:convert presentation.pptx

# Convert OpenDocument formats
php artisan pdf:convert document.odt
php artisan pdf:convert spreadsheet.ods
php artisan pdf:convert presentation.odp

# Check available converters
php artisan pdf:convert --check

# Diagnose LibreOffice
php artisan pdf:convert --diagnose

# List supported formats
php artisan pdf:convert --formats

# List available converters
php artisan pdf:convert --list
```

#### Command options:

- `--type` - Input type (text, html, csv, doc, docx, xls, xlsx)
- `--output` - Output file path
- `--converter` - Preferred converter (tcpdf, mpdf, libreoffice)
- `--config` - JSON configuration
- `--check` - Check converter availability
- `--diagnose` - Diagnose LibreOffice installation
- `--formats` - List supported formats
- `--list` - List available converters

#### Examples:

```bash
# Convert text file to PDF
php artisan pdf:convert readme.txt

# Convert HTML with mPDF in landscape A3
php artisan pdf:convert index.html --converter=mpdf --config='{"mpdf":{"format":"A3","orientation":"L"}}'

# Convert CSV with custom output
php artisan pdf:convert data.csv --output=/path/to/report.pdf

# Convert Word document
php artisan pdf:convert document.docx --output=document.pdf

# Diagnostic commands
php artisan pdf:convert --check
php artisan pdf:convert --diagnose
php artisan pdf:convert --formats
```

The command automatically detects input type based on file extension or content analysis, supports all converter types, and provides detailed error messages and diagnostics.

## 🛡️ Error handling & Validation

```php
use MadArlan\PDFBridge\Exceptions\UnsupportedFormatException;
use MadArlan\PDFBridge\Exceptions\ConverterNotAvailableException;
use MadArlan\PDFBridge\Exceptions\ValidationException;
use MadArlan\PDFBridge\Exceptions\ConversionException;

try {
    $pdfBridge->convertText('Hello World!', 'output.pdf');
} catch (ValidationException $e) {
    echo "Validation failed: " . $e->getMessage();
} catch (UnsupportedFormatException $e) {
    echo "Unsupported format: " . $e->getMessage();
    echo "Supported formats: " . implode(', ', $e->getSupportedFormats());
} catch (ConverterNotAvailableException $e) {
    echo "Converter not available: " . $e->getMessage();
} catch (ConversionException $e) {
    echo "Conversion failed: " . $e->getMessage();
} catch (\Exception $e) {
    echo "Unexpected error: " . $e->getMessage();
}
```

## 📋 Requirements

- **PHP**: 8.1 or higher
- **Laravel**: 8.0+ (supports Laravel 8, 9, 10, 11, 12)
- **TCPDF**: Included automatically
- **mPDF**: `composer require mpdf/mpdf` (recommended)
- **LibreOffice**: For office document support (DOC/DOCX/XLS/XLSX/PPT/PPTX/ODT/ODS/ODP)
  - Install: `sudo apt-get install libreoffice` (Ubuntu/Debian)
  - Package: `composer require ncjoes/office-converter`

## 📊 Supported Formats

| Format | Extension | Converter | Description |
|--------|-----------|-----------|-------------|
| Text | `.txt` | TCPDF, mPDF | Plain text files |
| HTML | `.html`, `.htm` | mPDF, TCPDF | Web pages and HTML content |
| CSV | `.csv` | TCPDF, mPDF, LibreOffice | Comma-separated values |
| Word | `.doc`, `.docx` | LibreOffice | Microsoft Word documents |
| Excel | `.xls`, `.xlsx` | LibreOffice | Microsoft Excel spreadsheets |
| PowerPoint | `.ppt`, `.pptx` | LibreOffice | Microsoft PowerPoint presentations |
| OpenDocument | `.odt`, `.ods`, `.odp` | LibreOffice | OpenDocument formats |
| Rich Text | `.rtf` | LibreOffice | Rich Text Format |

## 🧪 Testing

```bash
# Run tests
composer test

# Run tests with coverage
composer test-coverage

# Run static analysis
composer analyse
```

## 🤝 Contributing

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/amazing-feature`)
3. Make your changes
4. Add tests for your changes
5. Ensure tests pass (`composer test`)
6. Commit your changes (`git commit -m 'Add amazing feature'`)
7. Push to the branch (`git push origin feature/amazing-feature`)
8. Open a Pull Request

## 📝 Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## 🔒 Security

If you discover any security-related issues, please email madinovarlan@gmail.com instead of using the issue tracker.

## 📄 License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## 🆘 Support & Troubleshooting

If you encounter issues:

1. **Check converter availability**: `php artisan pdf:convert --check`
2. **Diagnose LibreOffice**: `php artisan pdf:convert --diagnose`
3. **List supported formats**: `php artisan pdf:convert --formats`
4. **Check logs**: Review Laravel logs for detailed error information
5. **Validate input**: Ensure files meet size and format requirements
6. **Create an issue**: [GitHub Issues](https://github.com/madarlan/pdf-bridge/issues) with:
   - PHP version
   - Laravel version
   - Error messages
   - Sample code
   - Input file details

### Common Issues

- **LibreOffice not found**: Install LibreOffice and ensure it's in PATH
- **File too large**: Check `validation.max_file_size` setting
- **Permission denied**: Ensure output directory is writable
- **Memory limit**: Increase PHP memory limit for large files

## 🏆 Credits

- **Author**: [MadArlan](https://github.com/madarlan)
- **Contributors**: [All Contributors](https://github.com/madarlan/pdf-bridge/contributors)
- **Powered by**: [TCPDF](https://tcpdf.org/), [mPDF](https://mpdf.github.io/), [LibreOffice](https://www.libreoffice.org/)