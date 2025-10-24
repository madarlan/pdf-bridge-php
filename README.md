# PHP PDF Bridge

![PDF Bridge Cover](https://i.ibb.co/yFXgf2dG/madarlan-pdf-bridge.png)

[![Latest Version on Packagist](https://img.shields.io/packagist/v/madarlan/pdf-bridge-php.svg?style=flat-square)](https://packagist.org/packages/madarlan/pdf-bridge-php)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/madarlan/pdf-bridge-php/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/madarlan/pdf-bridge-php/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![PHP Version Require](https://img.shields.io/packagist/php-v/madarlan/pdf-bridge-php.svg?style=flat-square)](https://packagist.org/packages/madarlan/pdf-bridge-php)

A powerful and universal PHP/Laravel package for converting various document formats to PDF using multiple converters (TCPDF, mPDF, LibreOffice). Features robust validation, comprehensive logging, and support for 15+ file formats with Laravel 8-12 support.

## Description

PHP PDF Bridge provides a unified interface for document conversion to PDF using several powerful libraries:

- **TCPDF** - for text, HTML and CSV conversion
- **mPDF** - for advanced HTML and CSS processing
- **LibreOffice** (via ncjoes/office-converter) - for DOC/DOCX/XLS/XLSX conversion

## Supported Formats

### Input formats:

- **Text**: `.txt`, plain text files
- **HTML**: `.html`, `.htm`, HTML markup
- **CSV**: `.csv`, tabular data
- **Microsoft Word**: `.doc`, `.docx`
- **Microsoft Excel**: `.xls`, `.xlsx`
- **Microsoft PowerPoint**: `.ppt`, `.pptx`
- **OpenDocument**: `.odt`, `.ods`, `.odp`
- **Rich Text**: `.rtf`

### Output format:

- **PDF** - all conversions produce PDF files

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
composer require madarlan/pdf-bridge-php
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

## Advanced Features

### Conversion Parameter Configuration

```php
$options = [
    'converter' => 'tcpdf',           // Force converter selection
    'format' => 'A3',                // Page format
    'orientation' => 'L',            // Orientation (P/L)
    'font_size' => 14,               // Font size
    'font_family' => 'helvetica',    // Font family
    'title' => 'Document Title',
    'author' => 'Document Author',
    'subject' => 'Document Subject',
    'keywords' => 'key, words',
    'margins' => [
        'left' => 20,
        'right' => 20,
        'top' => 30,
        'bottom' => 30,
    ],
];

$pdf = PDFBridge::convertHTML($html, null, $options);
```

### Working with CSV

```php
$csvContent = "Name,Age,City\nJohn,25,New York\nMary,30,Los Angeles";

$options = [
    'csv_delimiter' => ',',
    'csv_has_header' => true,
    'font_size' => 10,
];

$pdf = PDFBridge::convertCSV($csvContent, 'table.pdf', $options);
```

### Checking Converter Availability

#### Getting Information About All Converters

```php
$converters = PDFBridge::getAvailableConverters();

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

// Example output:
// ✓ tcpdf: text, html, csv
// ✓ mpdf: text, html, csv
// ✓ libreoffice: doc, docx, xls, xlsx
//   Version: LibreOffice 7.4.7.2 40(Build:2)
```

#### Checking Specific Converter

```php
// Check TCPDF availability
try {
    $tcpdfConverter = new \MadArlan\PDFBridge\Converters\TCPDFConverter();
    if ($tcpdfConverter->isAvailable()) {
        echo "TCPDF is available\n";
    }
} catch (\MadArlan\PDFBridge\Exceptions\ConverterNotAvailableException $e) {
    echo "TCPDF unavailable: " . $e->getMessage() . "\n";
}

// Check mPDF availability
try {
    $mpdfConverter = new \MadArlan\PDFBridge\Converters\MPDFConverter();
    if ($mpdfConverter->isAvailable()) {
        echo "mPDF is available\n";
    }
} catch (\MadArlan\PDFBridge\Exceptions\ConverterNotAvailableException $e) {
    echo "mPDF unavailable: " . $e->getMessage() . "\n";
}
```

#### Special LibreOffice Check

```php
use MadArlan\PDFBridge\Converters\OfficeConverter;
use MadArlan\PDFBridge\Exceptions\ConverterNotAvailableException;

// Check with automatic LibreOffice detection
try {
    $officeConverter = new OfficeConverter();
    
    if ($officeConverter->isAvailable()) {
        echo "✓ LibreOffice is available\n";
        
        // Get LibreOffice version
        $version = $officeConverter->getVersion();
        if ($version) {
            echo "  Version: {$version}\n";
        }
        
        // Supported formats
        $formats = $officeConverter->getSupportedFormats();
        echo "  Formats: " . implode(', ', $formats) . "\n";
    }
    
} catch (ConverterNotAvailableException $e) {
    echo "✗ LibreOffice unavailable: " . $e->getMessage() . "\n";
    
    // Possible reasons:
    // - LibreOffice not installed
    // - Incorrect LibreOffice path
    // - Missing ncjoes/office-converter package
}

// Check with specified LibreOffice path
$config = [
    'libreoffice_path' => '/usr/bin/libreoffice', // Specify correct path
    'temp_dir' => '/tmp',
    'timeout' => 120
];

try {
    $officeConverter = new OfficeConverter($config);
    echo "LibreOffice found at specified path\n";
} catch (ConverterNotAvailableException $e) {
    echo "LibreOffice not found: " . $e->getMessage() . "\n";
}

// Check via main PDFBridge class
$pdfBridge = new \MadArlan\PDFBridge\PDFBridge();
$converters = $pdfBridge->getAvailableConverters();

if ($converters['libreoffice']['available']) {
    echo "LibreOffice ready to work\n";
    echo "Version: " . ($converters['libreoffice']['version'] ?? 'unknown') . "\n";
} else {
    echo "LibreOffice problem: " . $converters['libreoffice']['error'] . "\n";
}
```

## Usage

### Artisan Command for Quick Conversion

The package includes a convenient Artisan command for quick file conversion from the command line:

```bash
# Basic command
php artisan pdf:convert {input} [options]

# Usage examples:

# Convert text file
php artisan pdf:convert document.txt

# Convert HTML file with output specification
php artisan pdf:convert index.html --output=result.pdf

# Convert Word document with specific converter
php artisan pdf:convert document.docx --converter=libreoffice

# Convert CSV with settings
php artisan pdf:convert data.csv --config='{"delimiter":";""encoding":"utf-8"}'

# Convert text directly (without file)
php artisan pdf:convert "Hello, World!" --type=text

# Convert HTML code
php artisan pdf:convert "<h1>Title</h1><p>Content</p>" --type=html
```

#### Available Command Options:

- `--output` - Output PDF file (default: input.pdf)
- `--type` - Input data type (auto|text|html|csv|doc|docx|xls|xlsx)
- `--converter` - Preferred converter (tcpdf|mpdf|libreoffice)
- `--config` - JSON configuration for converter
- `--check` - Check converter availability
- `--diagnose` - LibreOffice diagnostics
- `--list-formats` - Show supported formats
- `--list-converters` - Show available converters

#### Diagnostics and Checking Examples:

```bash
# Check all converter availability
php artisan pdf:convert --check

# LibreOffice diagnostics
php artisan pdf:convert --diagnose

# List supported formats
php artisan pdf:convert --list-formats

# List available converters
php artisan pdf:convert --list-converters
```

#### Automatic Type Detection:

The command automatically detects input data type:

- By file extension (.txt, .html, .csv, .doc, .docx, .xls, .xlsx)
- By content (HTML tags, CSV delimiters)
- Defaults to text

#### Configuration Examples:

```bash
# TCPDF with settings
php artisan pdf:convert document.html --converter=tcpdf --config='{"orientation":"L","format":"A4"}'

# mPDF with settings
php artisan pdf:convert document.html --converter=mpdf --config='{"margin_left":15,"margin_right":15}'

# LibreOffice with timeout
php artisan pdf:convert document.docx --converter=libreoffice --config='{"timeout":300}'
```

### In Laravel (via Facade)

```php
<?php

use MadArlan\PDFBridge\Laravel\PDFBridge;

class DocumentController extends Controller
{
    public function convertText()
    {
        $text = "Hello, World!\nThis is a test document.";
        
        // Convert to string
        $pdfContent = PDFBridge::convertText($text);
        
        // Save to file
        $filePath = PDFBridge::convertText($text, storage_path('app/document.pdf'));
        
        return response($pdfContent)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'attachment; filename="document.pdf"');
    }
    
    public function convertHTML()
    {
        $html = '<h1>Title</h1><p>This is <strong>HTML</strong> document.</p>';
        
        $options = [
            'converter' => 'mpdf', // Force mPDF usage
            'title' => 'My Document',
            'author' => 'Author',
        ];
        
        return PDFBridge::convertHTML($html, null, $options);
    }
    
    public function convertDocument(Request $request)
    {
        $file = $request->file('document');
        $inputPath = $file->store('temp');
        
        try {
            $pdfPath = PDFBridge::convertFile(storage_path('app/' . $inputPath));
            
            return response()->download($pdfPath)->deleteFileAfterSend();
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
}
```

### In Laravel (via Dependency Injection)

```php
<?php

use MadArlan\PDFBridge\PDFBridge;

class DocumentService
{
    protected PDFBridge $pdfBridge;
    
    public function __construct(PDFBridge $pdfBridge)
    {
        $this->pdfBridge = $pdfBridge;
    }
    
    public function processDocument(string $content, string $type): string
    {
        switch ($type) {
            case 'text':
                return $this->pdfBridge->convertText($content);
            case 'html':
                return $this->pdfBridge->convertHTML($content);
            case 'csv':
                return $this->pdfBridge->convertCSV($content);
            default:
                throw new \InvalidArgumentException("Unsupported type: {$type}");
        }
    }
}
```

### Standalone Usage (without Laravel)

```php
<?php

require_once 'vendor/autoload.php';

use MadArlan\PDFBridge\PDFBridge;

// Create instance with configuration
$config = [
    'default_converter' => 'mpdf',
    'mpdf' => [
        'format' => 'A4',
        'default_font_size' => 14,
    ],
];

$pdfBridge = new PDFBridge($config);

// Convert text
$text = "Sample text for PDF conversion";
$pdfContent = $pdfBridge->convertText($text);
file_put_contents('output.pdf', $pdfContent);

// Convert HTML
$html = '<h1>Title</h1><p>HTML content</p>';
$pdfBridge->convertHTML($html, 'output.pdf');

// Convert file
$pdfBridge->convertFile('document.docx', 'converted.pdf');

// Get available converters info
$converters = $pdfBridge->getAvailableConverters();
print_r($converters);
```

## Error Handling

```php
use MadArlan\PDFBridge\Exceptions\ConversionException;
use MadArlan\PDFBridge\Exceptions\UnsupportedFormatException;
use MadArlan\PDFBridge\Exceptions\ConverterNotAvailableException;

try {
    $pdf = PDFBridge::convertFile('document.unknown');
} catch (UnsupportedFormatException $e) {
    echo "Unsupported format: " . $e->getMessage();
    echo "Supported formats: " . implode(', ', $e->getSupportedFormats());
} catch (ConverterNotAvailableException $e) {
    echo "Converter unavailable: " . $e->getMessage();
} catch (ConversionException $e) {
    echo "Conversion failed: " . $e->getMessage();
    echo "Converter: " . $e->getConverterName();
}
```

## Used Libraries

### TCPDF

- **Version**: ^6.6
- **Purpose**: PDF generation from text, HTML, CSV
- **Features**: Lightweight, good Unicode support
- **Website**: https://tcpdf.org/

### mPDF

- **Version**: ^8.2
- **Purpose**: Advanced HTML and CSS processing
- **Features**: Better CSS support, fonts, images
- **Website**: https://mpdf.github.io/

### ncjoes/office-converter

- **Version**: ^1.0
- **Purpose**: Office document conversion via LibreOffice
- **Requirements**: LibreOffice must be installed on server
- **GitHub**: https://github.com/ncjoes/office-converter

## Performance and Recommendations

### Converter Selection

- **TCPDF**: Better for simple documents, faster performance
- **mPDF**: Better for complex HTML with CSS, images
- **LibreOffice**: Only option for DOC/DOCX/XLS/XLSX

### Optimization

```php
// Reuse instance
$pdfBridge = app('pdf-bridge');

// Configure temp directory for large files
$config = [
    'libreoffice' => [
        'temp_dir' => '/tmp/pdf-bridge',
        'timeout' => 300, // Increase for large files
    ],
];

$pdfBridge->setConfig($config);
```

## Testing

```bash
# Install development dependencies
composer install --dev

# Run tests
vendor/bin/phpunit
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

| Format       | Extension              | Converter                | Description                        |
|--------------|------------------------|--------------------------|------------------------------------|
| Text         | `.txt`                 | TCPDF, mPDF              | Plain text files                   |
| HTML         | `.html`, `.htm`        | mPDF, TCPDF              | Web pages and HTML content         |
| CSV          | `.csv`                 | TCPDF, mPDF, LibreOffice | Comma-separated values             |
| Word         | `.doc`, `.docx`        | LibreOffice              | Microsoft Word documents           |
| Excel        | `.xls`, `.xlsx`        | LibreOffice              | Microsoft Excel spreadsheets       |
| PowerPoint   | `.ppt`, `.pptx`        | LibreOffice              | Microsoft PowerPoint presentations |
| OpenDocument | `.odt`, `.ods`, `.odp` | LibreOffice              | OpenDocument formats               |
| Rich Text    | `.rtf`                 | LibreOffice              | Rich Text Format                   |

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
6. **Create an issue**: [GitHub Issues](https://github.com/madarlan/pdf-bridge-php/issues) with:
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

## License

MIT License. See [LICENSE](LICENSE) file for details.

## Author

- **GitHub**: [madarlan](https://github.com/madarlan)
- **Email**: madinovarlan@gmail.com

## Support

If you encounter problems or have questions:

1. Check [Issues](https://github.com/madarlan/pdf-bridge-php/issues)
2. Create a new Issue with detailed problem description
3. Include PHP, Laravel and library versions

## Changelog

### v1.0.0

- Initial release
- TCPDF, mPDF, LibreOffice support
- Laravel 8-12 integration
- Text, HTML, CSV, DOC/DOCX, XLS/XLSX to PDF conversion

## 🏆 Credits

- **Author**: [MadArlan](https://github.com/madarlan)
- **Contributors**: [All Contributors](https://github.com/madarlan/pdf-bridge-php/contributors)
- **Powered by**: [TCPDF](https://tcpdf.org/), [mPDF](https://mpdf.github.io/), [LibreOffice](https://www.libreoffice.org/)
