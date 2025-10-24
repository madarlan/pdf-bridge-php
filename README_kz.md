# PHP PDF Bridge

![PDF Bridge Cover](https://i.ibb.co/yFXgf2dG/madarlan-pdf-bridge.png)

Әртүрлі құжат форматтарын PDF-ке түрлендіру үшін бірнеше конвертерді (TCPDF, mPDF, LibreOffice) пайдаланатын қуатты және әмбебап PHP/Laravel пакеті. Сенімді валидация, кешенді логтау және Laravel 8-12 қолдауымен 15+ файл форматын қолдайды.

## Сипаттама

PHP PDF Bridge бірнеше қуатты кітапханаларды пайдаланып құжаттарды PDF-ке түрлендіру үшін біріктірілген интерфейс ұсынады:

- **TCPDF** - мәтін, HTML және CSV түрлендіру үшін
- **mPDF** - кеңейтілген HTML және CSS өңдеу үшін
- **LibreOffice** (ncjoes/office-converter арқылы) - DOC/DOCX/XLS/XLSX түрлендіру үшін

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

**Docker ескертуі**: Контейнердегі LibreOffice жұмыс істеу үшін X11 серверін талап етеді. Headless режимі үшін LibreOffice іске қосқанда `--headless` флагын пайдаланғаныңызға көз жеткізіңіз.

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
- **Қуатталған**: [TCPDF](https://tcpdf.org/), [mPDF](https://mpdf.github.io/), [LibreOffice](https://www.libreoffice.org/)
