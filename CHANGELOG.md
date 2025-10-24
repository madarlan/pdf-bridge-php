# Changelog

All notable changes to `pdf-bridge-php` will be documented in this file.

## [2.0.0] - 2024-10-24

### 🚀 Added

- **New format support**: RTF, ODT, ODS, PPT, PPTX, ODP formats
- **Input validation system**: File size limits, format validation, content verification
- **Comprehensive logging**: PSR-3 compatible logging with detailed operation tracking
- **Converter interfaces**: `ConverterInterface`, `TextConverterInterface`, `FileConverterInterface`
- **Presentation conversion**: New `convertPresentation()` method for PowerPoint files
- **Advanced error handling**: `ValidationException` for input validation errors
- **Performance monitoring**: Conversion duration and file size tracking
- **Modern PHP 8.1+ features**: Match expressions, typed properties, constructor promotion

### 🔧 Enhanced

- **PDFBridge class**: Complete rewrite with validation, logging, and error handling
- **Configuration system**: Extended with validation and logging settings
- **Exception handling**: More granular exception types and better error messages
- **Artisan commands**: Enhanced diagnostics and format detection
- **Converter detection**: Improved LibreOffice path detection for multiple platforms
- **Method consistency**: Fixed naming inconsistencies (convertHtml → convertHTML)

### 🧪 Testing

- **Unit tests**: Comprehensive test suite for core functionality
- **Validation tests**: Tests for input validation and error scenarios
- **PHPUnit configuration**: Modern PHPUnit 10+ configuration
- **Test coverage**: Coverage for all major components

### 📚 Documentation

- **Enhanced README**: Complete rewrite with badges, examples, and troubleshooting
- **API documentation**: Detailed method documentation with examples
- **Configuration guide**: Comprehensive configuration options
- **Troubleshooting section**: Common issues and solutions

### 🔄 Changed

- **Minimum PHP version**: Now requires PHP 8.1+
- **Constructor signature**: Added optional logger parameter
- **Configuration structure**: Reorganized for better clarity
- **Error messages**: More descriptive and actionable error messages

### 🛠️ Technical Improvements

- **Architecture**: Implemented SOLID principles and dependency injection
- **Code quality**: PSR-12 compliant code style
- **Type safety**: Full type declarations throughout codebase
- **Memory efficiency**: Optimized for large file processing

## [1.0.0] - 2024-01-01

### 🎉 Initial Release

- **Basic PDF conversion**: Text, HTML, CSV to PDF
- **Multiple converters**: TCPDF, mPDF, LibreOffice support
- **Laravel integration**: Service provider and facade
- **Artisan commands**: Basic conversion commands
- **Configuration system**: Basic converter configuration
- **Format support**: DOC/DOCX, XLS/XLSX conversion via LibreOffice

### 📋 Features

- Automatic converter selection
- Basic error handling
- LibreOffice diagnostics
- File format detection
- Laravel 8+ compatibility

---

## Upgrade Guide

### From 1.x to 2.0

#### Breaking Changes

1. **PHP Version**: Minimum PHP version increased to 8.1
2. **Constructor**: PDFBridge constructor now accepts optional logger parameter
3. **Method Names**: `convertHtml()` renamed to `convertHTML()`
4. **Configuration**: Some configuration keys have changed

#### Migration Steps

1. **Update PHP version** to 8.1 or higher
2. **Update method calls**:
   ```php
   // Old
   $pdfBridge->convertHtml($html, $output);
   
   // New
   $pdfBridge->convertHTML($html, $output);
   ```

3. **Update configuration** (if using custom config):
   ```php
   // Old
   'preferred_converter' => 'mpdf'
   
   // New
   'default' => 'mpdf'
   ```

4. **Handle new exceptions**:
   ```php
   use MadArlan\PDFBridge\Exceptions\ValidationException;
   
   try {
       $pdfBridge->convertText($text, $output);
   } catch (ValidationException $e) {
       // Handle validation errors
   }
   ```

#### New Features Available

- Input validation with configurable limits
- Comprehensive logging of all operations
- Support for presentations (PPT/PPTX)
- OpenDocument format support (ODT/ODS/ODP)
- Performance monitoring and metrics
- Enhanced error messages and diagnostics

#### Recommended Actions

1. **Enable logging** to monitor conversion operations
2. **Configure validation** limits based on your needs
3. **Update error handling** to catch new exception types
4. **Review configuration** for new options
5. **Run tests** to ensure compatibility

For detailed migration assistance, please see the [Migration Guide](MIGRATION.md) or create an issue on GitHub.
