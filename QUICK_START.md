# 🚀 Quick Start Guide

## Installation

```bash
composer require madarlan/pdf-bridge-php
```

## Basic Usage

```php
use MadArlan\PDFBridge\PDFBridge;

$pdfBridge = new PDFBridge();

// Convert text to PDF
$pdfBridge->convertText('Hello World!', 'output.pdf');

// Convert HTML to PDF
$pdfBridge->convertHTML('<h1>Hello</h1><p>World!</p>', 'output.pdf');

// Convert any file to PDF
$pdfBridge->convertFile('document.docx', 'output.pdf');
```

## Laravel Facade

```php
use PDFBridge;

PDFBridge::convertText('Hello World!', 'output.pdf');
PDFBridge::convertHTML('<h1>Hello</h1>', 'output.pdf');
PDFBridge::convertFile('document.docx', 'output.pdf');
```

## Artisan Commands

```bash
# Convert any file
php artisan pdf:convert input.txt output.pdf

# Check available converters
php artisan pdf:convert --check

# Diagnose LibreOffice
php artisan pdf:convert --diagnose
```

## Supported Formats

| Input                  | Output | Converter                |
|------------------------|--------|--------------------------|
| `.txt`                 | PDF    | TCPDF, mPDF              |
| `.html`, `.htm`        | PDF    | mPDF, TCPDF              |
| `.csv`                 | PDF    | TCPDF, mPDF, LibreOffice |
| `.doc`, `.docx`        | PDF    | LibreOffice              |
| `.xls`, `.xlsx`        | PDF    | LibreOffice              |
| `.ppt`, `.pptx`        | PDF    | LibreOffice              |
| `.odt`, `.ods`, `.odp` | PDF    | LibreOffice              |
| `.rtf`                 | PDF    | LibreOffice              |

## Error Handling

```php
use MadArlan\PDFBridge\Exceptions\ValidationException;
use MadArlan\PDFBridge\Exceptions\ConversionException;

try {
    $pdfBridge->convertText('Hello World!', 'output.pdf');
} catch (ValidationException $e) {
    echo "Validation error: " . $e->getMessage();
} catch (ConversionException $e) {
    echo "Conversion error: " . $e->getMessage();
}
```

## Configuration

```bash
# Publish config
php artisan vendor:publish --provider="MadArlan\PDFBridge\Laravel\PDFBridgeServiceProvider"
```

```php
// config/pdf-bridge.php
return [
    'default' => 'mpdf',
    'validation' => [
        'max_file_size' => 50 * 1024 * 1024, // 50MB
    ],
    'logging' => [
        'enabled' => true,
    ],
];
```

## Need LibreOffice?

For DOC/DOCX/XLS/XLSX/PPT/PPTX support:

```bash
# Ubuntu/Debian
sudo apt-get install libreoffice

# Windows
choco install libreoffice

# macOS
brew install --cask libreoffice

# PHP package
composer require ncjoes/office-converter
```

## 📚 Full Documentation

See [README.md](README.md) for complete documentation, advanced usage, and troubleshooting.
