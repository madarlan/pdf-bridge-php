# PHP PDF Bridge

![PDF Bridge Cover](https://i.ibb.co/kVNWgkBx/madarlan-pdf-bridge-php.png)

Әртүрлі құжат форматтарын PDF-ке түрлендіру үшін бірнеше конвертерді (TCPDF, mPDF, LibreOffice) пайдаланатын қуатты және
әмбебап PHP/Laravel пакеті. Сенімді валидация, кешенді логтау және Laravel 8-12 қолдауымен 15+ файл форматын қолдайды.

## Сипаттама

PHP PDF Bridge бірнеше қуатты кітапханаларды пайдаланып құжаттарды PDF-ке түрлендіру үшін біріктірілген интерфейс
ұсынады:

- **[TCPDF](https://github.com/tecnickcom/TCPDF)** - мәтін, HTML және CSV түрлендіру үшін
- **[mPDF](https://github.com/mpdf/mpdf)** - кеңейтілген HTML және CSS өңдеу үшін
- **LibreOffice** ([ncjoes/office-converter](https://github.com/ncjoes/office-converter) арқылы) - DOC/DOCX/XLS/XLSX түрлендіру үшін

## Қолдау көрсетілетін форматтар

### Кіріс форматтары:

- **Мәтін**: `.txt`, қарапайым мәтін файлдары
- **HTML**: `.html`, `.htm`, HTML белгілеу
- **CSV**: `.csv`, кестелік деректер
- **Microsoft Word**: `.doc`, `.docx`
- **Microsoft Excel**: `.xls`, `.xlsx`
- **Microsoft PowerPoint**: `.ppt`, `.pptx`
- **OpenDocument**: `.odt`, `.ods`, `.odp`
- **Rich Text**: `.rtf`

### Шығыс форматы:

- **PDF** - барлық түрлендірулер PDF файлдарын шығарады

## ✨ Мүмкіндіктер

- 🔄 **Әмбебап түрлендіру**: 15+ формат → PDF (Мәтін, HTML, CSV, DOC/DOCX, XLS/XLSX, PPT/PPTX, ODT, ODS, RTF және т.б.)
- ⚡ **Бірнеше конвертер**: TCPDF, mPDF, LibreOffice интеллектуалды автотаңдаумен
- 🛡️ **Кіріс деректерін валидациялау**: Файл өлшемінің шектеулері, формат тексеруі, мазмұнды растау
- 📊 **Кешенді логтау**: PSR-3 үйлесімділігімен операцияларды толық бақылау
- 🔧 **Икемді конфигурация**: Әр конвертер үшін жеке параметрлер, орта айнымалыларын қолдау
- 🔍 **Конвертер диагностикасы**: LibreOffice қолжетімділігі мен орнатуын тексеру үшін кіріктірілген құралдары
- 🎯 **Ақылды қате өңдеу**: Валидация кері байланысымен толық ерекше жағдайларды өңдеу
- 🚀 **Artisan командалары**: Түрлендіру және диагностика үшін қуатты CLI құралдары
- 🏗️ **Laravel интеграциясы**: Service provider, фасадтар және dependency injection дайындығы
- 🧪 **Толық тестіленген**: PHPUnit көмегімен кешенді тест жинағы
- 📐 **Заманауи архитектура**: Интерфейстер, контракттар және SOLID принциптері
- 🎨 **PHP 8.1+ дайындығы**: Match өрнектері мен типтелген қасиеттері бар заманауи синтаксис

## Орнату

### 1. Пакетті орнату

```bash
composer require madarlan/pdf-bridge-php
```

### 2. Конфигурацияны жариялау (міндетті емес)

```bash
php artisan vendor:publish --provider="MadArlan\PDFBridge\PDFBridgeServiceProvider"
```

### Жүйелік талаптар

- PHP 8.0 немесе жоғары
- Laravel 8.0 - 12.x (Laravel интеграциясы үшін)

### LibreOffice үшін қосымша талаптар

DOC/DOCX/XLS/XLSX файлдарын түрлендіру үшін LibreOffice орнату қажет:

#### Ubuntu/Debian:

```bash
sudo apt-get update
sudo apt-get install libreoffice
```

#### CentOS/RHEL:

```bash
sudo yum install libreoffice
```

#### Windows:

[Ресми сайттан](https://www.libreoffice.org/download/download/) LibreOffice жүктеп орнатыңыз

#### macOS:

```bash
brew install --cask libreoffice
```

#### Docker/Dockerfile:

```dockerfile
# Ubuntu/Debian базалық образы үшін
FROM php:8.2-fpm

# LibreOffice және қажетті тәуелділіктерді орнату
RUN apt-get update && apt-get install -y \
    libreoffice \
    libreoffice-writer \
    libreoffice-calc \
    fonts-dejavu-core \
    fonts-liberation \
    && rm -rf /var/lib/apt/lists/*

# Минималды орнату нұсқасы
# RUN apt-get update && apt-get install -y \
#     libreoffice-core \
#     libreoffice-writer \
#     libreoffice-calc \
#     && rm -rf /var/lib/apt/lists/*

# Alpine базалық образы үшін
# FROM php:8.2-fpm-alpine
# RUN apk add --no-cache libreoffice
```

**Docker ескертуі**: Контейнердегі LibreOffice жұмыс істеу үшін X11 серверін талап етеді. Headless режимі үшін
LibreOffice іске қосқанда `--headless` флагын пайдаланғаныңызға көз жеткізіңіз.

## Laravel интеграциясы

### Автоматты тіркеу (Laravel 5.5+)

Пакет Package Discovery арқылы автоматты түрде тіркеледі.

### Қолмен тіркеу (Laravel 5.4 және төмен)

`config/app.php` файлына қосыңыз:

```php
'providers' => [
    // ...
    MadArlan\PDFBridge\Laravel\PDFBridgeServiceProvider::class,
],

'aliases' => [
    // ...
    'PDFBridge' => MadArlan\PDFBridge\Laravel\PDFBridge::class,
],
```

### Конфигурацияны жариялау

```bash
php artisan vendor:publish --provider="MadArlan\PDFBridge\Laravel\PDFBridgeServiceProvider"
```

Немесе тек конфигурация:

```bash
php artisan vendor:publish --tag="pdf-bridge-config"
```

## Конфигурация

### Негізгі конфигурация

```php
// config/pdf-bridge.php
return [
    'preferred_converter' => 'auto', // auto, tcpdf, mpdf, libreoffice
    
    'tcpdf' => [
        'format' => 'A4',
        'orientation' => 'P',
        'unit' => 'mm',
        'unicode' => true,
        'encoding' => 'UTF-8',
        'margins' => [
            'left' => 15,
            'top' => 27,
            'right' => 15,
            'bottom' => 25,
        ],
        'font' => [
            'family' => 'dejavusans',
            'size' => 10,
        ],
    ],
    
    'mpdf' => [
        'format' => 'A4',
        'orientation' => 'P',
        'margin_left' => 15,
        'margin_right' => 15,
        'margin_top' => 16,
        'margin_bottom' => 16,
        'default_font' => 'dejavusans',
        'default_font_size' => 10,
    ],
    
    'libreoffice' => [
        'bin_path' => null, // автоматты анықтау
        'temp_dir' => null, // жүйелік temp пайдалану
    ],
];
```

## Пайдалану

### Негізгі пайдалану

```php
use MadArlan\PDFBridge\PDFBridge;

$pdfBridge = new PDFBridge();

// Мәтінді PDF-ке түрлендіру
$pdfBridge->convertText('Сәлем Әлем!', 'output.pdf');

// HTML-ді PDF-ке түрлендіру
$html = '<h1>Тақырып</h1><p>Мазмұн</p>';
$pdfBridge->convertHtml($html, 'output.pdf');

// CSV-ді PDF-ке түрлендіру
$pdfBridge->convertCsv('data.csv', 'output.pdf');

// DOC/DOCX-ті PDF-ке түрлендіру (LibreOffice қажет)
$pdfBridge->convertDocument('document.docx', 'output.pdf');

// XLS/XLSX-ті PDF-ке түрлендіру (LibreOffice қажет)
$pdfBridge->convertSpreadsheet('spreadsheet.xlsx', 'output.pdf');
```

### Laravel Facade пайдалану

```php
use PDFBridge;

// Мәтінді түрлендіру
PDFBridge::convertText('Сәлем Әлем!', 'output.pdf');

// HTML түрлендіру
PDFBridge::convertHtml('<h1>Тақырып</h1>', 'output.pdf');
```

### Конфигурацияны басқару

```php
// Конфигурацияны орнату
$pdfBridge->setConfig([
    'preferred_converter' => 'mpdf',
    'mpdf' => [
        'format' => 'A3',
        'orientation' => 'L',
    ]
]);

// Ағымдағы конфигурацияны алу
$config = $pdfBridge->getConfig();
```

### Конвертер қолжетімділігін тексеру

```php
// Барлық конвертерлерді тексеру
$converters = $pdfBridge->getAvailableConverters();

foreach ($converters as $name => $info) {
    if ($info['available']) {
        echo "✓ {$name}: " . implode(', ', $info['formats']) . "\n";
        if (isset($info['version'])) {
            echo "  Нұсқасы: {$info['version']}\n";
        }
    } else {
        echo "✗ {$name}: {$info['error']}\n";
    }
}

// Нақты конвертерді тексеру
if ($pdfBridge->isConverterAvailable('tcpdf')) {
    echo "TCPDF қолжетімді\n";
}

if ($pdfBridge->isConverterAvailable('mpdf')) {
    echo "mPDF қолжетімді\n";
}

// LibreOffice тексеру
if ($pdfBridge->isConverterAvailable('libreoffice')) {
    echo "LibreOffice қолжетімді\n";
    
    // LibreOffice нұсқасын алу
    $converters = $pdfBridge->getAvailableConverters();
    if (isset($converters['libreoffice']['version'])) {
        echo "LibreOffice нұсқасы: " . $converters['libreoffice']['version'] . "\n";
    }
} else {
    echo "LibreOffice қолжетімді емес\n";
    
    // Қате мәліметтерін алу
    $converters = $pdfBridge->getAvailableConverters();
    echo "Қате: " . $converters['libreoffice']['error'] . "\n";
}
```

### LibreOffice мәселелерін шешу

```php
/**
 * LibreOffice диагностика функциясы
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
        $diagnosis['errors'][] = 'ncjoes/office-converter пакеті орнатылмаған';
        return $diagnosis;
    }
    
    // LibreOffice-ті стандартты орындарда іздеу
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
                
                // Нұсқаны алу
                $output = shell_exec(escapeshellarg($path) . ' --version 2>&1');
                $diagnosis['version'] = $output ? trim($output) : null;
            }
        }
    }
    
    if (empty($diagnosis['libreoffice_paths'])) {
        $diagnosis['errors'][] = 'LibreOffice стандартты орындарда табылмады';
    }
    
    return $diagnosis;
}

// Пайдалану
$diagnosis = diagnoseLibreOffice();

echo "Пакет орнатылған: " . ($diagnosis['package_installed'] ? 'Иә' : 'Жоқ') . "\n";

if (!empty($diagnosis['libreoffice_paths'])) {
    echo "Табылған LibreOffice жолдары:\n";
    foreach ($diagnosis['libreoffice_paths'] as $path) {
        echo "  - {$path}\n";
    }
    echo "Пайдаланылған жол: " . $diagnosis['found_path'] . "\n";
    echo "Нұсқасы: " . ($diagnosis['version'] ?? 'анықталмады') . "\n";
}

if (!empty($diagnosis['errors'])) {
    echo "Қателер:\n";
    foreach ($diagnosis['errors'] as $error) {
        echo "  - {$error}\n";
    }
}
```

### Жылдам түрлендіру үшін Artisan командасы

Пакетте жылдам PDF түрлендіру үшін Artisan командасы бар:

```bash
# Негізгі түрлендіру
php artisan pdf:convert input.txt output.pdf

# Кіріс түрін автоматты анықтау
php artisan pdf:convert input.html

# Кіріс түрін көрсету
php artisan pdf:convert "Сәлем Әлем" --type=text --output=hello.pdf

# Нақты конвертерді пайдалану
php artisan pdf:convert input.html --converter=mpdf

# JSON конфигурациясымен
php artisan pdf:convert input.html --config='{"mpdf":{"format":"A3","orientation":"L"}}'

# CSV түрлендіру
php artisan pdf:convert data.csv --type=csv

# Құжаттарды түрлендіру (LibreOffice қажет)
php artisan pdf:convert document.docx
php artisan pdf:convert spreadsheet.xlsx

# Қолжетімді конвертерлерді тексеру
php artisan pdf:convert --check

# LibreOffice диагностикасы
php artisan pdf:convert --diagnose

# Қолдау көрсетілетін форматтарды тізімдеу
php artisan pdf:convert --formats

# Қолжетімді конвертерлерді тізімдеу
php artisan pdf:convert --list
```

#### Команда опциялары:

- `--type` - Кіріс түрі (text, html, csv, doc, docx, xls, xlsx)
- `--output` - Шығыс файл жолы
- `--converter` - Таңдаулы конвертер (tcpdf, mpdf, libreoffice)
- `--config` - JSON конфигурациясы
- `--check` - Конвертер қолжетімділігін тексеру
- `--diagnose` - LibreOffice орнатуын диагностикалау
- `--formats` - Қолдау көрсетілетін форматтарды тізімдеу
- `--list` - Қолжетімді конвертерлерді тізімдеу

#### Мысалдар:

```bash
# Мәтін файлын PDF-ке түрлендіру
php artisan pdf:convert readme.txt

# HTML-ді mPDF арқылы альбомдық A3 форматында түрлендіру
php artisan pdf:convert index.html --converter=mpdf --config='{"mpdf":{"format":"A3","orientation":"L"}}'

# CSV-ді арнайы шығыспен түрлендіру
php artisan pdf:convert data.csv --output=/path/to/report.pdf

# Word құжатын түрлендіру
php artisan pdf:convert document.docx --output=document.pdf

# Диагностика командалары
php artisan pdf:convert --check
php artisan pdf:convert --diagnose
php artisan pdf:convert --formats
```

Команда файл кеңейтімі немесе мазмұн талдауы негізінде кіріс түрін автоматты анықтайды, барлық конвертер түрлерін
қолдайды және толық қате хабарлары мен диагностиканы ұсынады.

## 📚 Толық пайдалану мысалдары

### Негізгі түрлендірулер

#### Мәтінді PDF-ке түрлендіру

```php
use MadArlan\PDFBridge\PDFBridge;

$pdfBridge = new PDFBridge();

// Қарапайым мәтін түрлендіру
$text = "Сәлем Әлем!\nБұл көп жолды мәтін құжаты.";
$pdfPath = $pdfBridge->convertText($text, 'hello.pdf');

// PDF-ті жол ретінде қайтару (жүктеп алу үшін)
$pdfContent = $pdfBridge->convertText($text);
return response($pdfContent, 200, [
    'Content-Type' => 'application/pdf',
    'Content-Disposition' => 'attachment; filename="document.pdf"'
]);
```

#### HTML-ді кеңейтілген стилизациямен PDF-ке түрлендіру

```php
// CSS бар күрделі HTML
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
        <h1>Компания есебі</h1>
    </div>
    <div class="content">
        <table>
            <tr><th>Өнім</th><th>Баға</th></tr>
            <tr><td>Виджет А</td><td>1000 тг.</td></tr>
            <tr><td>Виджет Б</td><td>1500 тг.</td></tr>
        </table>
    </div>
</body>
</html>';

$pdfBridge->convertHTML($html, 'report.pdf');
```

#### CSV-ді баптаулармен PDF-ке түрлендіру

```php
// CSV деректері
$csvData = "Аты,Жасы,Қала,Жалақы\n";
$csvData .= "Иван Иванов,30,Алматы,500000\n";
$csvData .= "Мария Петрова,25,Астана,450000\n";
$csvData .= "Петр Сидоров,35,Шымкент,550000\n";

// Баптаулармен түрлендіру
$options = [
    'csv_delimiter' => ',',
    'csv_has_header' => true,
    'font_size' => 10
];

$pdfBridge->convertCSV($csvData, 'employees.pdf', $options);
```

#### Office құжаттарын PDF-ке түрлендіру

```php
// Microsoft Word құжаттары
$pdfBridge->convertFile('келісімшарт.doc', 'келісімшарт.pdf');
$pdfBridge->convertFile('есеп.docx', 'есеп.pdf');

// Microsoft Excel кестелері
$pdfBridge->convertFile('бюджет.xls', 'бюджет.pdf');
$pdfBridge->convertFile('деректер.xlsx', 'деректер.pdf');

// Microsoft PowerPoint презентациялары
$pdfBridge->convertFile('презентация.ppt', 'презентация.pdf');
$pdfBridge->convertFile('слайдтар.pptx', 'слайдтар.pdf');

// OpenOffice/LibreOffice құжаттары
$pdfBridge->convertFile('құжат.odt', 'құжат.pdf');         // Writer
$pdfBridge->convertFile('кесте.ods', 'кесте.pdf');         // Calc
$pdfBridge->convertFile('презентация.odp', 'презентация.pdf'); // Impress

// Rich Text Format
$pdfBridge->convertFile('құжат.rtf', 'құжат.pdf');

// Office құжаттары үшін баптаулармен
$options = [
    'converter' => 'libreoffice',
    'timeout' => 300,           // Үлкен құжаттар үшін 5 минут
    'temp_dir' => '/tmp/pdf',   // Пайдаланушы уақытша қалтасы
    'format' => 'A4',
    'orientation' => 'P'
];

$pdfBridge->convertFile('үлкен-құжат.docx', 'нәтиже.pdf', $options);
```

#### Office құжаттарын жаппай өңдеу

```php
// Бірнеше Office құжаттарын өңдеу
$officeFiles = [
    'құжаттар/келісімшарт.docx',
    'құжаттар/бюджет.xlsx', 
    'құжаттар/презентация.pptx',
    'құжаттар/есеп.odt',
    'құжаттар/деректер.ods'
];

foreach ($officeFiles as $file) {
    try {
        $outputFile = pathinfo($file, PATHINFO_FILENAME) . '.pdf';
        $outputPath = 'түрлендірілген/' . $outputFile;
        
        echo "{$file} түрлендіру...\n";
        $pdfBridge->convertFile($file, $outputPath);
        echo "✓ {$outputPath} дейін түрлендірілді\n";
        
    } catch (\Exception $e) {
        echo "✗ {$file} түрлендіру мүмкін болмады: " . $e->getMessage() . "\n";
    }
}
```

#### Office құжаттарын кеңейтілген түрлендіру

```php
// LibreOffice арнайы баптауларымен түрлендіру
$config = [
    'default' => 'libreoffice',
    'libreoffice' => [
        'libreoffice_path' => '/usr/bin/libreoffice',
        'temp_dir' => storage_path('app/temp'),
        'timeout' => 600,  // Өте үлкен файлдар үшін 10 минут
        'format' => 'pdf',
        'options' => [
            '--headless',
            '--invisible',
            '--nodefault',
            '--nolockcheck'
        ]
    ]
];

$pdfBridge = new PDFBridge($config);

// Форматтауды сақтаумен күрделі құжаттарды түрлендіру
$complexDocs = [
    'қаржылық-есеп.docx' => ['format' => 'A4', 'orientation' => 'P'],
    'кең-кесте.xlsx' => ['format' => 'A3', 'orientation' => 'L'],
    'презентация.pptx' => ['format' => 'A4', 'orientation' => 'L']
];

foreach ($complexDocs as $file => $settings) {
    $pdfBridge->convertFile($file, str_replace(pathinfo($file, PATHINFO_EXTENSION), 'pdf', $file), $settings);
}
```

#### Office құжаттары үшін қателерді өңдеу

```php
use MadArlan\PDFBridge\Exceptions\ConverterNotAvailableException;
use MadArlan\PDFBridge\Exceptions\ConversionException;

try {
    $pdfBridge->convertFile('құжат.docx', 'нәтиже.pdf');
    
} catch (ConverterNotAvailableException $e) {
    // LibreOffice орнатылмаған немесе табылмаған
    echo "Office құжаттарын түрлендіру үшін LibreOffice қажет.\n";
    echo "Қате: " . $e->getMessage() . "\n";
    echo "LibreOffice орнатыңыз немесе орнату жолын тексеріңіз.\n";
    
} catch (ConversionException $e) {
    // Түрлендіру сәтсіз аяқталды (бүлінген файл, қолдау көрсетілмейтін функциялар және т.б.)
    echo "Құжатты түрлендіру сәтсіз аяқталды: " . $e->getMessage() . "\n";
    
    // Файлдың болуы мен қолжетімділігін тексеру
    if (!file_exists('құжат.docx')) {
        echo "Файл жоқ.\n";
    } elseif (!is_readable('құжат.docx')) {
        echo "Файлды оқу мүмкін емес.\n";
    } else {
        echo "Файл бүлінген болуы мүмкін немесе қолдау көрсетілмейтін функциялар бар.\n";
    }
}
```

### Кеңейтілген конфигурация

#### Конвертер баптаулары

```php
$config = [
    'default' => 'mpdf',
    'mpdf' => [
        'format' => 'A4',
        'orientation' => 'L', // Альбомдық бағдар
        'margin_left' => 20,
        'margin_right' => 20,
        'margin_top' => 25,
        'margin_bottom' => 25,
        'default_font' => 'Arial',
        'default_font_size' => 12
    ],
    'tcpdf' => [
        'format' => 'A3',
        'orientation' => 'P', // Кітаптық бағдар
        'font' => [
            'family' => 'helvetica',
            'size' => 14
        ]
    ]
];

$pdfBridge = new PDFBridge($config);
```

#### Пайдаланушы логгерімен

```php
use Psr\Log\LoggerInterface;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

// Пайдаланушы логгерін жасау
$logger = new Logger('pdf-bridge');
$logger->pushHandler(new StreamHandler('pdf-conversions.log', Logger::INFO));

$pdfBridge = new PDFBridge($config, $logger);

// Барлық операциялар pdf-conversions.log файлына жазылады
$pdfBridge->convertText('Сәлем Әлем!', 'output.pdf');
```

### Laravel үшін нақты мысалдар

#### Интернет-дүкен үшін шот-фактура генерациясы

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
            Log::error('Шот-фактура генерациясы қатесі', [
                'order_id' => $order->id,
                'error' => $e->getMessage()
            ]);
            
            throw new \Exception('PDF шот-фактурасын жасау мүмкін болмады');
        }
    }
}
```

#### Үлкен құжаттар үшін кезектер

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
        
        // Пайдаланушыны хабарландыру немесе қосымша әрекеттер орындау
    }
}

// Тапсырманы іске қосу
GeneratePDFJob::dispatch($htmlContent, $outputPath);
```

#### Құжаттарды жаппай өңдеу

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

## Қателерді өңдеу

```php
use MadArlan\PDFBridge\Exceptions\UnsupportedFormatException;
use MadArlan\PDFBridge\Exceptions\ConverterNotAvailableException;

try {
    $pdfBridge->convertText('Сәлем Әлем!', 'output.pdf');
} catch (UnsupportedFormatException $e) {
    echo "Қолдау көрсетілмейтін формат: " . $e->getMessage();
    echo "Қолдау көрсетілетін форматтар: " . implode(', ', $e->getSupportedFormats());
} catch (ConverterNotAvailableException $e) {
    echo "Конвертер қолжетімді емес: " . $e->getMessage();
} catch (\Exception $e) {
    echo "Қате: " . $e->getMessage();
}
```

## Талаптар

- PHP 8.0+
- Laravel 9.0+
- TCPDF (қосылған)
- mPDF (міндетті емес): `composer require mpdf/mpdf`
- LibreOffice + ncjoes/office-converter (міндетті емес): DOC/DOCX/XLS/XLSX қолдауы үшін

## Лицензия

MIT лицензиясы. Толық мәліметтер үшін [LICENSE](LICENSE) файлын қараңыз.

## Қолдау

Егер мәселелерге тап болсаңыз:

1. Конвертер қолжетімділігін тексеріңіз: `php artisan pdf:convert --check`
2. LibreOffice диагностикасын жүргізіңіз: `php artisan pdf:convert --diagnose`
3. Қолдау көрсетілетін форматтарды тексеріңіз: `php artisan pdf:convert --formats`
4. Қате хабарлары мен логтарды қарап шығыңыз
5. Толық ақпаратпен GitHub-та мәселе жасаңыз

## Үлес қосу

1. Репозиторийді fork жасаңыз
2. Мүмкіндік филиалын жасаңыз
3. Өзгерістеріңізді жасаңыз
4. Тесттер қосыңыз
5. Pull request жіберіңіз

## Өзгерістер журналы

### v1.0.0

- Алғашқы шығарылым
- TCPDF, mPDF, LibreOffice қолдауы
- Laravel 8-12 интеграциясы
- Мәтін, HTML, CSV, DOC/DOCX, XLS/XLSX түрлендіру

## 🏆 Авторлар

- **Автор**: [MadArlan](https://github.com/madarlan)
- **Үлесшілер**: [Барлық үлесшілер](https://github.com/madarlan/pdf-bridge-php/contributors)
- **Қуатталған
  **: [TCPDF](https://tcpdf.org/), [mPDF](https://mpdf.github.io/), [LibreOffice](https://www.libreoffice.org/)
