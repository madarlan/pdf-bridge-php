# PDF Bridge

![PDF Bridge Cover](https://i.ibb.co/yFXgf2dG/madarlan-pdf-bridge.png)

Әртүрлі форматтарды PDF-ке түрлендіру үшін бірнеше конвертерді (TCPDF, mPDF, LibreOffice) пайдаланатын әмбебап Laravel пакеті.

## Мүмкіндіктер

- **Әмбебап түрлендіру**: Мәтін, HTML, CSV, DOC/DOCX, XLS/XLSX → PDF
- **Бірнеше конвертер**: TCPDF, mPDF, LibreOffice (автоматты таңдау)
- **Икемді конфигурация**: Әр конвертер үшін жеке параметрлер
- **Конвертер қолжетімділігін тексеру**: Қолжетімді конвертерлерді автоматты анықтау
- **LibreOffice диагностикасы**: LibreOffice орнату мәселелерін диагностикалау үшін кіріктірілген құралдар
- **Artisan командасы**: Командалық жол арқылы жылдам түрлендіру
- **Laravel интеграциясы**: Қызмет провайдері және фасадтар

## Орнату

### 1. Пакетті орнату

```bash
composer require madarlan/pdf-bridge
```

### 2. Конфигурацияны жариялау (міндетті емес)

```bash
php artisan vendor:publish --provider="MadArlan\PDFBridge\PDFBridgeServiceProvider"
```

### 3. Конвертерлерді орнату

#### TCPDF (автоматты орнатылады)
```bash
# Пакетке қосылған
```

#### mPDF
```bash
composer require mpdf/mpdf
```

#### LibreOffice (DOC/DOCX/XLS/XLSX үшін)
```bash
# LibreOffice орнату
# Ubuntu/Debian:
sudo apt-get install libreoffice

# CentOS/RHEL:
sudo yum install libreoffice

# Windows: https://www.libreoffice.org/ сайтынан жүктеп алыңыз
# macOS: brew install --cask libreoffice

# PHP пакетін орнату
composer require ncjoes/office-converter
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

Команда файл кеңейтімі немесе мазмұн талдауы негізінде кіріс түрін автоматты анықтайды, барлық конвертер түрлерін қолдайды және толық қате хабарлары мен диагностиканы ұсынады.

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
- Мәтін, HTML, CSV, DOC/DOCX, XLS/XLSX түрлендіру
- Laravel интеграциясы
- Artisan командасы
- Конвертер қолжетімділігін тексеру
- LibreOffice диагностикасы