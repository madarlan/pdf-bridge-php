# 📚 Usage Examples

This document provides comprehensive examples of using PDF Bridge in various scenarios.

## Table of Contents

- [Basic Conversions](#basic-conversions)
- [Advanced Configuration](#advanced-configuration)
- [Error Handling](#error-handling)
- [Laravel Integration](#laravel-integration)
- [Artisan Commands](#artisan-commands)
- [Real-World Scenarios](#real-world-scenarios)

## Basic Conversions

### Text to PDF

```php
use MadArlan\PDFBridge\PDFBridge;

$pdfBridge = new PDFBridge();

// Simple text conversion
$text = "Hello World!\nThis is a multi-line text document.";
$pdfPath = $pdfBridge->convertText($text, 'hello.pdf');

// Return PDF as string (for download)
$pdfContent = $pdfBridge->convertText($text);
return response($pdfContent, 200, [
    'Content-Type' => 'application/pdf',
    'Content-Disposition' => 'attachment; filename="document.pdf"'
]);
```

### HTML to PDF

```php
// Simple HTML
$html = '<h1>Invoice #12345</h1><p>Amount: $100.00</p>';
$pdfBridge->convertHTML($html, 'invoice.pdf');

// Complex HTML with CSS
$html = '
<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; }
        .header { background-color: #f0f0f0; padding: 20px; }
        .content { margin: 20px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Company Report</h1>
    </div>
    <div class="content">
        <table>
            <tr><th>Product</th><th>Price</th></tr>
            <tr><td>Widget A</td><td>$10.00</td></tr>
            <tr><td>Widget B</td><td>$15.00</td></tr>
        </table>
    </div>
</body>
</html>';

$pdfBridge->convertHTML($html, 'report.pdf');
```

### CSV to PDF

```php
// CSV data
$csvData = "Name,Age,City,Salary\n";
$csvData .= "John Doe,30,New York,$50000\n";
$csvData .= "Jane Smith,25,Los Angeles,$45000\n";
$csvData .= "Bob Johnson,35,Chicago,$55000\n";

// Convert with custom options
$options = [
    'csv_delimiter' => ',',
    'csv_has_header' => true,
    'font_size' => 10
];

$pdfBridge->convertCSV($csvData, 'employees.pdf', $options);
```

### Office Documents

```php
// Word documents
$pdfBridge->convertDocument('contract.docx', 'contract.pdf');
$pdfBridge->convertDocument('report.odt', 'report.pdf');

// Excel spreadsheets
$pdfBridge->convertSpreadsheet('data.xlsx', 'data.pdf');
$pdfBridge->convertSpreadsheet('budget.ods', 'budget.pdf');

// PowerPoint presentations
$pdfBridge->convertPresentation('slides.pptx', 'slides.pdf');
$pdfBridge->convertPresentation('presentation.odp', 'presentation.pdf');
```

## Advanced Configuration

### Custom Converter Settings

```php
$config = [
    'default' => 'mpdf',
    'mpdf' => [
        'format' => 'A4',
        'orientation' => 'L', // Landscape
        'margin_left' => 20,
        'margin_right' => 20,
        'margin_top' => 25,
        'margin_bottom' => 25,
        'default_font' => 'Arial',
        'default_font_size' => 12
    ],
    'tcpdf' => [
        'format' => 'A3',
        'orientation' => 'P', // Portrait
        'font' => [
            'family' => 'helvetica',
            'size' => 14
        ]
    ]
];

$pdfBridge = new PDFBridge($config);
```

### With Custom Logger

```php
use Psr\Log\LoggerInterface;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

// Create custom logger
$logger = new Logger('pdf-bridge');
$logger->pushHandler(new StreamHandler('pdf-conversions.log', Logger::INFO));

$pdfBridge = new PDFBridge($config, $logger);

// All operations will be logged to pdf-conversions.log
$pdfBridge->convertText('Hello World!', 'output.pdf');
```

### Runtime Configuration Changes

```php
$pdfBridge = new PDFBridge();

// Change converter for specific operation
$pdfBridge->setConfig(['default' => 'tcpdf']);
$pdfBridge->convertText('Using TCPDF', 'tcpdf-output.pdf');

// Change back to mPDF
$pdfBridge->setConfig(['default' => 'mpdf']);
$pdfBridge->convertHTML('<h1>Using mPDF</h1>', 'mpdf-output.pdf');
```

## Error Handling

### Comprehensive Error Handling

```php
use MadArlan\PDFBridge\Exceptions\ValidationException;
use MadArlan\PDFBridge\Exceptions\ConversionException;
use MadArlan\PDFBridge\Exceptions\ConverterNotAvailableException;
use MadArlan\PDFBridge\Exceptions\UnsupportedFormatException;

try {
    $pdfBridge->convertFile('large-file.docx', 'output.pdf');
    
} catch (ValidationException $e) {
    // Handle validation errors (file too large, invalid format, etc.)
    return response()->json([
        'error' => 'Validation failed',
        'message' => $e->getMessage()
    ], 400);
    
} catch (UnsupportedFormatException $e) {
    // Handle unsupported file formats
    return response()->json([
        'error' => 'Unsupported format',
        'message' => $e->getMessage(),
        'supported_formats' => $e->getSupportedFormats()
    ], 400);
    
} catch (ConverterNotAvailableException $e) {
    // Handle missing converters (e.g., LibreOffice not installed)
    return response()->json([
        'error' => 'Converter unavailable',
        'message' => $e->getMessage()
    ], 503);
    
} catch (ConversionException $e) {
    // Handle conversion failures
    return response()->json([
        'error' => 'Conversion failed',
        'message' => $e->getMessage()
    ], 500);
    
} catch (\Exception $e) {
    // Handle unexpected errors
    return response()->json([
        'error' => 'Unexpected error',
        'message' => $e->getMessage()
    ], 500);
}
```

### Validation Configuration

```php
$config = [
    'validation' => [
        'max_file_size' => 10 * 1024 * 1024, // 10MB
        'max_text_length' => 500000, // 500KB
        'allowed_extensions' => ['txt', 'html', 'doc', 'docx', 'pdf']
    ]
];

$pdfBridge = new PDFBridge($config);

try {
    $pdfBridge->convertFile('huge-file.docx', 'output.pdf');
} catch (ValidationException $e) {
    echo "File validation failed: " . $e->getMessage();
}
```

## Laravel Integration

### Service Provider Registration

```php
// config/app.php
'providers' => [
    // ...
    MadArlan\PDFBridge\Laravel\PDFBridgeServiceProvider::class,
],

'aliases' => [
    // ...
    'PDFBridge' => MadArlan\PDFBridge\Laravel\PDFBridge::class,
],
```

### Dependency Injection

```php
use MadArlan\PDFBridge\PDFBridge;

class DocumentController extends Controller
{
    public function __construct(private PDFBridge $pdfBridge)
    {
    }
    
    public function generateInvoice(Request $request)
    {
        $html = view('invoices.template', $request->all())->render();
        
        $filename = 'invoice-' . time() . '.pdf';
        $path = storage_path('app/invoices/' . $filename);
        
        $this->pdfBridge->convertHTML($html, $path);
        
        return response()->download($path);
    }
}
```

### Using Facade

```php
use PDFBridge;

class ReportController extends Controller
{
    public function exportReport(Request $request)
    {
        $data = $this->getReportData($request);
        $html = view('reports.pdf', compact('data'))->render();
        
        $pdfContent = PDFBridge::convertHTML($html);
        
        return response($pdfContent, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="report.pdf"'
        ]);
    }
}
```

### Queue Jobs

```php
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use MadArlan\PDFBridge\PDFBridge;

class GeneratePDFJob implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;
    
    public function __construct(
        private string $htmlContent,
        private string $outputPath
    ) {}
    
    public function handle(PDFBridge $pdfBridge): void
    {
        $pdfBridge->convertHTML($this->htmlContent, $this->outputPath);
        
        // Notify user or perform additional actions
    }
}

// Dispatch the job
GeneratePDFJob::dispatch($htmlContent, $outputPath);
```

## Artisan Commands

### Basic Usage

```bash
# Convert text file
php artisan pdf:convert document.txt

# Convert with specific output
php artisan pdf:convert document.txt --output=converted.pdf

# Convert HTML file
php artisan pdf:convert webpage.html

# Convert Office documents
php artisan pdf:convert presentation.pptx
php artisan pdf:convert spreadsheet.xlsx
php artisan pdf:convert document.docx
```

### Advanced Options

```bash
# Use specific converter
php artisan pdf:convert document.html --converter=tcpdf

# Custom configuration
php artisan pdf:convert document.html --config='{"mpdf":{"format":"A3","orientation":"L"}}'

# Convert CSV with custom delimiter
php artisan pdf:convert data.csv --type=csv --config='{"csv_delimiter":";"}'
```

### Diagnostic Commands

```bash
# Check all converters
php artisan pdf:convert --check

# Diagnose LibreOffice installation
php artisan pdf:convert --diagnose

# List supported formats
php artisan pdf:convert --formats

# List available converters
php artisan pdf:convert --list
```

## Real-World Scenarios

### E-commerce Invoice Generation

```php
class InvoiceService
{
    public function __construct(private PDFBridge $pdfBridge)
    {
    }
    
    public function generateInvoice(Order $order): string
    {
        $html = view('invoices.template', [
            'order' => $order,
            'customer' => $order->customer,
            'items' => $order->items,
            'total' => $order->total
        ])->render();
        
        $filename = "invoice-{$order->id}.pdf";
        $path = storage_path("app/invoices/{$filename}");
        
        try {
            $this->pdfBridge->convertHTML($html, $path, [
                'format' => 'A4',
                'orientation' => 'P',
                'margin_left' => 15,
                'margin_right' => 15
            ]);
            
            return $path;
            
        } catch (ConversionException $e) {
            Log::error('Invoice generation failed', [
                'order_id' => $order->id,
                'error' => $e->getMessage()
            ]);
            
            throw new \Exception('Failed to generate invoice PDF');
        }
    }
}
```

### Bulk Document Processing

```php
class DocumentProcessor
{
    public function __construct(private PDFBridge $pdfBridge)
    {
    }
    
    public function processBatch(array $files): array
    {
        $results = [];
        
        foreach ($files as $file) {
            try {
                $outputPath = $this->getOutputPath($file);
                $this->pdfBridge->convertFile($file, $outputPath);
                
                $results[] = [
                    'file' => $file,
                    'status' => 'success',
                    'output' => $outputPath
                ];
                
            } catch (\Exception $e) {
                $results[] = [
                    'file' => $file,
                    'status' => 'error',
                    'error' => $e->getMessage()
                ];
            }
        }
        
        return $results;
    }
    
    private function getOutputPath(string $inputFile): string
    {
        $pathInfo = pathinfo($inputFile);
        return $pathInfo['dirname'] . '/' . $pathInfo['filename'] . '.pdf';
    }
}
```

### Report Generation with Charts

```php
class ReportGenerator
{
    public function __construct(private PDFBridge $pdfBridge)
    {
    }
    
    public function generateSalesReport(array $data): string
    {
        // Generate chart image (using a charting library)
        $chartPath = $this->generateChart($data);
        
        $html = view('reports.sales', [
            'data' => $data,
            'chart_path' => $chartPath,
            'generated_at' => now()
        ])->render();
        
        $outputPath = storage_path('app/reports/sales-' . date('Y-m-d') . '.pdf');
        
        $this->pdfBridge->convertHTML($html, $outputPath, [
            'format' => 'A4',
            'orientation' => 'L', // Landscape for better chart visibility
            'margin_left' => 10,
            'margin_right' => 10
        ]);
        
        // Clean up temporary chart file
        if (file_exists($chartPath)) {
            unlink($chartPath);
        }
        
        return $outputPath;
    }
}
```

### Multi-language Document Processing

```php
class MultiLanguageConverter
{
    public function __construct(private PDFBridge $pdfBridge)
    {
    }
    
    public function convertWithLanguage(string $content, string $language): string
    {
        $fontMap = [
            'en' => 'dejavusans',
            'ru' => 'dejavusans',
            'zh' => 'cid0cs',
            'ar' => 'aealarabiya',
            'ja' => 'cid0jp'
        ];
        
        $font = $fontMap[$language] ?? 'dejavusans';
        
        $options = [
            'font_family' => $font,
            'font_size' => 12
        ];
        
        return $this->pdfBridge->convertText($content, null, $options);
    }
}
```

These examples demonstrate the flexibility and power of PDF Bridge in various real-world scenarios. For more specific use cases or advanced configurations, refer to the main [README.md](README.md) documentation.
