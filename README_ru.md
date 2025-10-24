# PHP PDF Bridge

![PDF Bridge Cover](https://i.ibb.co/kVNWgkBx/madarlan-pdf-bridge-php.png)

[![Последняя версия](https://img.shields.io/packagist/v/madarlan/pdf-bridge-php.svg?style=flat-square)](https://packagist.org/packages/madarlan/pdf-bridge-php)
[![Стиль кода](https://img.shields.io/github/actions/workflow/status/madarlan/pdf-bridge-php/fix-php-code-style-issues.yml?branch=main&label=стиль%20кода&style=flat-square)](https://github.com/madarlan/pdf-bridge-php/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![PHP версия](https://img.shields.io/packagist/php-v/madarlan/pdf-bridge.svg?style=flat-square)](https://packagist.org/packages/madarlan/pdf-bridge-php)

Мощный и универсальный PHP/Laravel пакет для конвертации различных форматов документов в PDF с использованием нескольких
конвертеров (TCPDF, mPDF, LibreOffice). Включает надежную валидацию, комплексное логирование и поддержку 15+ форматов
файлов.с поддержкой Laravel 8-12.

## Описание

PHP PDF Bridge предоставляет единый интерфейс для конвертации документов в PDF, используя несколько мощных библиотек:

- **[TCPDF](https://github.com/tecnickcom/TCPDF)** - для конвертации текста, HTML и CSV
- **[mPDF](https://github.com/mpdf/mpdf)** - для расширенной работы с HTML и CSS
- **LibreOffice** (через [ncjoes/office-converter](https://github.com/ncjoes/office-converter)) - для конвертации DOC/DOCX/XLS/XLSX

## Поддерживаемые форматы

### Входные форматы:

- **Текст**: `.txt`, обычный текст
- **HTML**: `.html`, `.htm`, HTML-разметка
- **CSV**: `.csv`, табличные данные
- **Microsoft Word**: `.doc`, `.docx`
- **Microsoft Excel**: `.xls`, `.xlsx`

### Выходной формат:

- **PDF** - все конвертации производятся в PDF

## Установка

### Через Composer

```bash
composer require madarlan/pdf-bridge-php
```

### Системные требования

- PHP 8.0 или выше
- Laravel 8.0 - 12.x (для интеграции с Laravel)

### Дополнительные требования для LibreOffice

Для конвертации DOC/DOCX/XLS/XLSX файлов необходимо установить LibreOffice:

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

Скачайте и установите LibreOffice с [официального сайта](https://www.libreoffice.org/download/download/)

#### macOS:

```bash
brew install --cask libreoffice
```

#### Docker/Dockerfile:

```dockerfile
# Для Ubuntu/Debian базового образа
FROM php:8.2-fpm

# Установка LibreOffice и необходимых зависимостей
RUN apt-get update && apt-get install -y \
    libreoffice \
    libreoffice-writer \
    libreoffice-calc \
    fonts-dejavu-core \
    fonts-liberation \
    && rm -rf /var/lib/apt/lists/*

# Альтернативный вариант с минимальной установкой
# RUN apt-get update && apt-get install -y \
#     libreoffice-core \
#     libreoffice-writer \
#     libreoffice-calc \
#     && rm -rf /var/lib/apt/lists/*

# Для Alpine базового образа
# FROM php:8.2-fpm-alpine
# RUN apk add --no-cache libreoffice
```

**Примечание для Docker**: LibreOffice в контейнере требует X11 сервер для работы. Для headless режима убедитесь, что
используете флаг `--headless` при запуске LibreOffice.

## Интеграция с Laravel

### Автоматическая регистрация (Laravel 5.5+)

Пакет автоматически регистрируется через Package Discovery.

### Ручная регистрация (Laravel 5.4 и ниже)

Добавьте в `config/app.php`:

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

### Публикация конфигурации

```bash
php artisan vendor:publish --provider="MadArlan\PDFBridge\Laravel\PDFBridgeServiceProvider"
```

Или только конфигурацию:

```bash
php artisan vendor:publish --tag="pdf-bridge-config"
```

## ✨ Возможности

- 🔄 **Универсальная конвертация**: 15+ форматов → PDF (Текст, HTML, CSV, DOC/DOCX, XLS/XLSX, PPT/PPTX, ODT, ODS, RTF и
  др.)
- ⚡ **Несколько конвертеров**: TCPDF, mPDF, LibreOffice с интеллектуальным автовыбором
- 🛡️ **Валидация входных данных**: Ограничения размера файлов, проверка форматов, верификация содержимого
- 📊 **Комплексное логирование**: Детальное отслеживание операций с совместимостью PSR-3
- 🔧 **Гибкая конфигурация**: Индивидуальные настройки для каждого конвертера с поддержкой переменных окружения
- 🔍 **Диагностика конвертеров**: Встроенные инструменты для проверки доступности и установки LibreOffice
- 🎯 **Умная обработка ошибок**: Детальная обработка исключений с обратной связью по валидации
- 🚀 **Artisan команды**: Мощные CLI инструменты для конвертации и диагностики
- 🏗️ **Интеграция с Laravel**: Service provider, фасады и готовность к dependency injection
- 🧪 **Полностью протестировано**: Комплексный набор тестов с PHPUnit
- 📐 **Современная архитектура**: Интерфейсы, контракты и принципы SOLID
- 🎨 **Готовность к PHP 8.1+**: Современный синтаксис с match выражениями и типизированными свойствами

## Конфигурация

После публикации отредактируйте `config/pdf-bridge.php`:

```php
<?php

return [
    // Конвертер по умолчанию
    'default_converter' => 'mpdf',

    // Настройки TCPDF
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
    ],

    // Настройки mPDF
    'mpdf' => [
        'mode' => 'utf-8',
        'format' => 'A4',
        'default_font_size' => 12,
        'default_font' => 'dejavusans',
        'margin_left' => 15,
        'margin_right' => 15,
        'margin_top' => 16,
        'margin_bottom' => 16,
        'orientation' => 'P',
    ],

    // Настройки LibreOffice
    'libreoffice' => [
        'libreoffice_path' => null, // Автоопределение
        'temp_dir' => null,
        'timeout' => 120,
    ],

    // Приоритет конвертеров для разных форматов
    'converter_priority' => [
        'text' => ['mpdf', 'tcpdf'],
        'html' => ['mpdf', 'tcpdf'],
        'csv' => ['tcpdf', 'mpdf'],
    ],
];
```

## Использование

### Artisan команда для быстрой конвертации

Пакет включает удобную Artisan команду для быстрой конвертации файлов из командной строки:

```bash
# Основная команда
php artisan pdf:convert {input} [опции]

# Примеры использования:

# Конвертация текстового файла
php artisan pdf:convert document.txt

# Конвертация HTML файла с указанием выходного файла
php artisan pdf:convert index.html --output=result.pdf

# Конвертация Word документа с указанием конвертера
php artisan pdf:convert document.docx --converter=libreoffice

# Конвертация CSV с настройками
php artisan pdf:convert data.csv --config='{"delimiter":";","encoding":"utf-8"}'

# Конвертация текста напрямую (без файла)
php artisan pdf:convert "Привет, мир!" --type=text

# Конвертация HTML кода
php artisan pdf:convert "<h1>Заголовок</h1><p>Текст</p>" --type=html
```

#### Доступные опции команды:

- `--output` - Выходной PDF файл (по умолчанию: input.pdf)
- `--type` - Тип входных данных (auto|text|html|csv|doc|docx|xls|xlsx)
- `--converter` - Предпочитаемый конвертер (tcpdf|mpdf|libreoffice)
- `--config` - JSON конфигурация для конвертера
- `--check` - Проверить доступность конвертеров
- `--diagnose` - Диагностика LibreOffice
- `--list-formats` - Показать поддерживаемые форматы
- `--list-converters` - Показать доступные конвертеры

#### Примеры диагностики и проверки:

```bash
# Проверка доступности всех конвертеров
php artisan pdf:convert --check

# Диагностика LibreOffice
php artisan pdf:convert --diagnose

# Список поддерживаемых форматов
php artisan pdf:convert --list-formats

# Список доступных конвертеров
php artisan pdf:convert --list-converters
```

#### Автоматическое определение типа:

Команда автоматически определяет тип входных данных:

- По расширению файла (.txt, .html, .csv, .doc, .docx, .xls, .xlsx)
- По содержимому (HTML теги, CSV разделители)
- По умолчанию считается текстом

#### Примеры с конфигурацией:

```bash
# TCPDF с настройками
php artisan pdf:convert document.html --converter=tcpdf --config='{"orientation":"L","format":"A4"}'

# mPDF с настройками
php artisan pdf:convert document.html --converter=mpdf --config='{"margin_left":15,"margin_right":15}'

# LibreOffice с таймаутом
php artisan pdf:convert document.docx --converter=libreoffice --config='{"timeout":300}'
```

### В Laravel (через Facade)

```php
<?php

use MadArlan\PDFBridge\Laravel\PDFBridge;

class DocumentController extends Controller
{
    public function convertText()
    {
        $text = "Привет, мир!\nЭто тестовый документ.";
        
        // Конвертация в строку
        $pdfContent = PDFBridge::convertText($text);
        
        // Сохранение в файл
        $filePath = PDFBridge::convertText($text, storage_path('app/document.pdf'));
        
        return response($pdfContent)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'attachment; filename="document.pdf"');
    }
    
    public function convertHTML()
    {
        $html = '<h1>Заголовок</h1><p>Это <strong>HTML</strong> документ.</p>';
        
        $options = [
            'converter' => 'mpdf', // Принудительно использовать mPDF
            'title' => 'Мой документ',
            'author' => 'Автор',
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

### В Laravel (через Dependency Injection)

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

### Standalone использование (без Laravel)

```php
<?php

require_once 'vendor/autoload.php';

use MadArlan\PDFBridge\PDFBridge;

// Создание экземпляра с конфигурацией
$config = [
    'default_converter' => 'mpdf',
    'mpdf' => [
        'format' => 'A4',
        'default_font_size' => 14,
    ],
];

$pdfBridge = new PDFBridge($config);

// Конвертация текста
$text = "Пример текста для конвертации в PDF";
$pdfContent = $pdfBridge->convertText($text);
file_put_contents('output.pdf', $pdfContent);

// Конвертация HTML
$html = '<h1>Заголовок</h1><p>HTML контент</p>';
$pdfBridge->convertHTML($html, 'output.pdf');

// Конвертация файла
$pdfBridge->convertFile('document.docx', 'converted.pdf');

// Получение информации о доступных конвертерах
$converters = $pdfBridge->getAvailableConverters();
print_r($converters);
```

## Расширенные возможности

### Настройка параметров конвертации

```php
$options = [
    'converter' => 'tcpdf',           // Принудительный выбор конвертера
    'format' => 'A3',                // Формат страницы
    'orientation' => 'L',            // Ориентация (P/L)
    'font_size' => 14,               // Размер шрифта
    'font_family' => 'helvetica',    // Семейство шрифтов
    'title' => 'Заголовок документа',
    'author' => 'Автор документа',
    'subject' => 'Тема документа',
    'keywords' => 'ключевые, слова',
    'margins' => [
        'left' => 20,
        'right' => 20,
        'top' => 30,
        'bottom' => 30,
    ],
];

$pdf = PDFBridge::convertHTML($html, null, $options);
```

### Работа с CSV

```php
$csvContent = "Имя,Возраст,Город\nИван,25,Москва\nМария,30,СПб";

$options = [
    'csv_delimiter' => ',',
    'csv_has_header' => true,
    'font_size' => 10,
];

$pdf = PDFBridge::convertCSV($csvContent, 'table.pdf', $options);
```

### Проверка доступности конвертеров

#### Получение информации о всех конвертерах

```php
$converters = PDFBridge::getAvailableConverters();

foreach ($converters as $name => $info) {
    if ($info['available']) {
        echo "✓ {$name}: " . implode(', ', $info['formats']) . "\n";
        if (isset($info['version'])) {
            echo "  Версия: {$info['version']}\n";
        }
    } else {
        echo "✗ {$name}: {$info['error']}\n";
    }
}

// Пример результата:
// ✓ tcpdf: text, html, csv
// ✓ mpdf: text, html, csv
// ✓ libreoffice: doc, docx, xls, xlsx
//   Версия: LibreOffice 7.4.7.2 40(Build:2)
```

#### Проверка конкретного конвертера

```php
// Проверка доступности TCPDF
try {
    $tcpdfConverter = new \MadArlan\PDFBridge\Converters\TCPDFConverter();
    if ($tcpdfConverter->isAvailable()) {
        echo "TCPDF доступен\n";
    }
} catch (\MadArlan\PDFBridge\Exceptions\ConverterNotAvailableException $e) {
    echo "TCPDF недоступен: " . $e->getMessage() . "\n";
}

// Проверка доступности mPDF
try {
    $mpdfConverter = new \MadArlan\PDFBridge\Converters\MPDFConverter();
    if ($mpdfConverter->isAvailable()) {
        echo "mPDF доступен\n";
    }
} catch (\MadArlan\PDFBridge\Exceptions\ConverterNotAvailableException $e) {
    echo "mPDF недоступен: " . $e->getMessage() . "\n";
}
```

#### Специальная проверка LibreOffice

```php
use MadArlan\PDFBridge\Converters\OfficeConverter;
use MadArlan\PDFBridge\Exceptions\ConverterNotAvailableException;

// Проверка с автоматическим поиском LibreOffice
try {
    $officeConverter = new OfficeConverter();
    
    if ($officeConverter->isAvailable()) {
        echo "✓ LibreOffice доступен\n";
        
        // Получение версии LibreOffice
        $version = $officeConverter->getVersion();
        if ($version) {
            echo "  Версия: {$version}\n";
        }
        
        // Поддерживаемые форматы
        $formats = $officeConverter->getSupportedFormats();
        echo "  Форматы: " . implode(', ', $formats) . "\n";
    }
    
} catch (ConverterNotAvailableException $e) {
    echo "✗ LibreOffice недоступен: " . $e->getMessage() . "\n";
    
    // Возможные причины:
    // - LibreOffice не установлен
    // - Неправильный путь к LibreOffice
    // - Отсутствует пакет ncjoes/office-converter
}

// Проверка с указанием пути к LibreOffice
$config = [
    'libreoffice_path' => '/usr/bin/libreoffice', // Укажите правильный путь
    'temp_dir' => '/tmp',
    'timeout' => 120
];

try {
    $officeConverter = new OfficeConverter($config);
    echo "LibreOffice найден по указанному пути\n";
} catch (ConverterNotAvailableException $e) {
    echo "LibreOffice не найден: " . $e->getMessage() . "\n";
}

// Проверка через основной класс PDFBridge
$pdfBridge = new \MadArlan\PDFBridge\PDFBridge();
$converters = $pdfBridge->getAvailableConverters();

if ($converters['libreoffice']['available']) {
    echo "LibreOffice готов к работе\n";
    echo "Версия: " . ($converters['libreoffice']['version'] ?? 'неизвестна') . "\n";
} else {
    echo "Проблема с LibreOffice: " . $converters['libreoffice']['error'] . "\n";
}
```

#### Диагностика проблем с LibreOffice

```php
// Функция для диагностики LibreOffice
function diagnoseLibreOffice(): array
{
    $diagnosis = [
        'package_installed' => class_exists('NcJoes\OfficeConverter\OfficeConverter'),
        'libreoffice_paths' => [],
        'found_path' => null,
        'version' => null,
        'errors' => []
    ];
    
    // Проверка установки пакета
    if (!$diagnosis['package_installed']) {
        $diagnosis['errors'][] = 'Пакет ncjoes/office-converter не установлен. Выполните: composer require ncjoes/office-converter';
        return $diagnosis;
    }
    
    // Поиск LibreOffice в стандартных местах
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
                
                // Попытка получить версию
                $command = escapeshellarg($path) . ' --version 2>&1';
                $output = shell_exec($command);
                $diagnosis['version'] = $output ? trim($output) : null;
            }
        }
    }
    
    if (empty($diagnosis['libreoffice_paths'])) {
        $diagnosis['errors'][] = 'LibreOffice не найден в стандартных местах установки';
        $diagnosis['errors'][] = 'Установите LibreOffice или укажите путь в конфигурации';
    }
    
    return $diagnosis;
}

// Использование диагностики
$diagnosis = diagnoseLibreOffice();

echo "=== Диагностика LibreOffice ===\n";
echo "Пакет office-converter: " . ($diagnosis['package_installed'] ? '✓' : '✗') . "\n";
echo "Найденные пути LibreOffice:\n";

if (!empty($diagnosis['libreoffice_paths'])) {
    foreach ($diagnosis['libreoffice_paths'] as $path) {
        echo "  - {$path}\n";
    }
    echo "Используемый путь: " . $diagnosis['found_path'] . "\n";
    echo "Версия: " . ($diagnosis['version'] ?? 'не определена') . "\n";
} else {
    echo "  Не найдено\n";
}

if (!empty($diagnosis['errors'])) {
    echo "Ошибки:\n";
    foreach ($diagnosis['errors'] as $error) {
        echo "  - {$error}\n";
    }
}
```

## 📚 Подробные примеры использования

### Базовые конверсии

#### Текст в PDF

```php
use MadArlan\PDFBridge\PDFBridge;

$pdfBridge = new PDFBridge();

// Простая конверсия текста
$text = "Привет, мир!\nЭто многострочный текстовый документ.";
$pdfPath = $pdfBridge->convertText($text, 'hello.pdf');

// Возврат PDF как строки (для скачивания)
$pdfContent = $pdfBridge->convertText($text);
return response($pdfContent, 200, [
    'Content-Type' => 'application/pdf',
    'Content-Disposition' => 'attachment; filename="document.pdf"'
]);
```

#### HTML в PDF с продвинутой стилизацией

```php
// Сложный HTML с CSS
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
        <h1>Отчет компании</h1>
    </div>
    <div class="content">
        <table>
            <tr><th>Товар</th><th>Цена</th></tr>
            <tr><td>Виджет А</td><td>1000 руб.</td></tr>
            <tr><td>Виджет Б</td><td>1500 руб.</td></tr>
        </table>
    </div>
</body>
</html>';

$pdfBridge->convertHTML($html, 'report.pdf');
```

#### CSV в PDF с настройками

```php
// CSV данные
$csvData = "Имя,Возраст,Город,Зарплата\n";
$csvData .= "Иван Иванов,30,Москва,50000\n";
$csvData .= "Мария Петрова,25,СПб,45000\n";
$csvData .= "Петр Сидоров,35,Казань,55000\n";

// Конверсия с настройками
$options = [
    'csv_delimiter' => ',',
    'csv_has_header' => true,
    'font_size' => 10
];

$pdfBridge->convertCSV($csvData, 'employees.pdf', $options);
```

#### Office документы в PDF

```php
// Документы Microsoft Word
$pdfBridge->convertFile('договор.doc', 'договор.pdf');
$pdfBridge->convertFile('отчет.docx', 'отчет.pdf');

// Таблицы Microsoft Excel
$pdfBridge->convertFile('бюджет.xls', 'бюджет.pdf');
$pdfBridge->convertFile('данные.xlsx', 'данные.pdf');

// Презентации Microsoft PowerPoint
$pdfBridge->convertFile('презентация.ppt', 'презентация.pdf');
$pdfBridge->convertFile('слайды.pptx', 'слайды.pdf');

// Документы OpenOffice/LibreOffice
$pdfBridge->convertFile('документ.odt', 'документ.pdf');     // Writer
$pdfBridge->convertFile('таблица.ods', 'таблица.pdf');       // Calc
$pdfBridge->convertFile('презентация.odp', 'презентация.pdf'); // Impress

// Rich Text Format
$pdfBridge->convertFile('документ.rtf', 'документ.pdf');

// С настройками для Office документов
$options = [
    'converter' => 'libreoffice',
    'timeout' => 300,           // 5 минут для больших документов
    'temp_dir' => '/tmp/pdf',   // Пользовательская временная папка
    'format' => 'A4',
    'orientation' => 'P'
];

$pdfBridge->convertFile('большой-документ.docx', 'результат.pdf', $options);
```

#### Массовая обработка Office документов

```php
// Обработка нескольких Office документов
$officeFiles = [
    'документы/договор.docx',
    'документы/бюджет.xlsx', 
    'документы/презентация.pptx',
    'документы/отчет.odt',
    'документы/данные.ods'
];

foreach ($officeFiles as $file) {
    try {
        $outputFile = pathinfo($file, PATHINFO_FILENAME) . '.pdf';
        $outputPath = 'конвертированные/' . $outputFile;
        
        echo "Конвертация {$file}...\n";
        $pdfBridge->convertFile($file, $outputPath);
        echo "✓ Конвертировано в {$outputPath}\n";
        
    } catch (\Exception $e) {
        echo "✗ Не удалось конвертировать {$file}: " . $e->getMessage() . "\n";
    }
}
```

#### Продвинутая конвертация Office документов

```php
// Конвертация с специальными настройками LibreOffice
$config = [
    'default' => 'libreoffice',
    'libreoffice' => [
        'libreoffice_path' => '/usr/bin/libreoffice',
        'temp_dir' => storage_path('app/temp'),
        'timeout' => 600,  // 10 минут для очень больших файлов
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

// Конвертация сложных документов с сохранением форматирования
$complexDocs = [
    'финансовый-отчет.docx' => ['format' => 'A4', 'orientation' => 'P'],
    'широкая-таблица.xlsx' => ['format' => 'A3', 'orientation' => 'L'],
    'презентация.pptx' => ['format' => 'A4', 'orientation' => 'L']
];

foreach ($complexDocs as $file => $settings) {
    $pdfBridge->convertFile($file, str_replace(pathinfo($file, PATHINFO_EXTENSION), 'pdf', $file), $settings);
}
```

#### Обработка ошибок для Office документов

```php
use MadArlan\PDFBridge\Exceptions\ConverterNotAvailableException;
use MadArlan\PDFBridge\Exceptions\ConversionException;

try {
    $pdfBridge->convertFile('документ.docx', 'результат.pdf');
    
} catch (ConverterNotAvailableException $e) {
    // LibreOffice не установлен или не найден
    echo "LibreOffice необходим для конвертации Office документов.\n";
    echo "Ошибка: " . $e->getMessage() . "\n";
    echo "Пожалуйста, установите LibreOffice или проверьте путь установки.\n";
    
} catch (ConversionException $e) {
    // Конвертация не удалась (поврежденный файл, неподдерживаемые функции и т.д.)
    echo "Конвертация документа не удалась: " . $e->getMessage() . "\n";
    
    // Проверка существования и доступности файла
    if (!file_exists('документ.docx')) {
        echo "Файл не существует.\n";
    } elseif (!is_readable('документ.docx')) {
        echo "Файл недоступен для чтения.\n";
    } else {
        echo "Файл может быть поврежден или содержать неподдерживаемые функции.\n";
    }
}
```

### Продвинутая конфигурация

#### Настройки конвертеров

```php
$config = [
    'default' => 'mpdf',
    'mpdf' => [
        'format' => 'A4',
        'orientation' => 'L', // Альбомная ориентация
        'margin_left' => 20,
        'margin_right' => 20,
        'margin_top' => 25,
        'margin_bottom' => 25,
        'default_font' => 'Arial',
        'default_font_size' => 12
    ],
    'tcpdf' => [
        'format' => 'A3',
        'orientation' => 'P', // Книжная ориентация
        'font' => [
            'family' => 'helvetica',
            'size' => 14
        ]
    ]
];

$pdfBridge = new PDFBridge($config);
```

#### С пользовательским логгером

```php
use Psr\Log\LoggerInterface;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

// Создание пользовательского логгера
$logger = new Logger('pdf-bridge');
$logger->pushHandler(new StreamHandler('pdf-conversions.log', Logger::INFO));

$pdfBridge = new PDFBridge($config, $logger);

// Все операции будут логироваться в pdf-conversions.log
$pdfBridge->convertText('Привет, мир!', 'output.pdf');
```

### Реальные примеры для Laravel

#### Генерация счетов для интернет-магазина

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
            Log::error('Ошибка генерации счета', [
                'order_id' => $order->id,
                'error' => $e->getMessage()
            ]);
            
            throw new \Exception('Не удалось создать PDF счет');
        }
    }
}
```

#### Очереди для больших документов

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
        
        // Уведомить пользователя или выполнить дополнительные действия
    }
}

// Запуск задачи
GeneratePDFJob::dispatch($htmlContent, $outputPath);
```

#### Массовая обработка документов

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

## Обработка ошибок

```php
use MadArlan\PDFBridge\Exceptions\ConversionException;
use MadArlan\PDFBridge\Exceptions\UnsupportedFormatException;
use MadArlan\PDFBridge\Exceptions\ConverterNotAvailableException;

try {
    $pdf = PDFBridge::convertFile('document.unknown');
} catch (UnsupportedFormatException $e) {
    echo "Неподдерживаемый формат: " . $e->getMessage();
    echo "Поддерживаемые форматы: " . implode(', ', $e->getSupportedFormats());
} catch (ConverterNotAvailableException $e) {
    echo "Конвертер недоступен: " . $e->getMessage();
} catch (ConversionException $e) {
    echo "Ошибка конвертации: " . $e->getMessage();
    echo "Конвертер: " . $e->getConverterName();
}
```

## Используемые библиотеки

### TCPDF

- **Версия**: ^6.6
- **Назначение**: Генерация PDF из текста, HTML, CSV
- **Особенности**: Легковесная, хорошая поддержка Unicode
- **Сайт**: https://tcpdf.org/

### mPDF

- **Версия**: ^8.2
- **Назначение**: Расширенная работа с HTML и CSS
- **Особенности**: Лучшая поддержка CSS, шрифтов, изображений
- **Сайт**: https://mpdf.github.io/

### ncjoes/office-converter

- **Версия**: ^1.0
- **Назначение**: Конвертация офисных документов через LibreOffice
- **Требования**: LibreOffice должен быть установлен на сервере
- **GitHub**: https://github.com/ncjoes/office-converter

## Производительность и рекомендации

### Выбор конвертера

- **TCPDF**: Лучше для простых документов, быстрее работает
- **mPDF**: Лучше для сложного HTML с CSS, изображениями
- **LibreOffice**: Единственный вариант для DOC/DOCX/XLS/XLSX

### Оптимизация

```php
// Переиспользование экземпляра
$pdfBridge = app('pdf-bridge');

// Настройка временной директории для больших файлов
$config = [
    'libreoffice' => [
        'temp_dir' => '/tmp/pdf-bridge',
        'timeout' => 300, // Увеличить для больших файлов
    ],
];

$pdfBridge->setConfig($config);
```

## Тестирование

```bash
# Установка зависимостей для разработки
composer install --dev

# Запуск тестов
vendor/bin/phpunit
```

## Лицензия

MIT License. Подробности в файле [LICENSE](LICENSE).

## Автор

- **GitHub**: [madarlan](https://github.com/madarlan)
- **Email**: madinovarlan@gmail.com

## Поддержка

Если у вас возникли проблемы или вопросы:

1. Проверьте [Issues](https://github.com/madarlan/pdf-bridge-php/issues)
2. Создайте новый Issue с подробным описанием проблемы
3. Укажите версии PHP, Laravel и используемых библиотек

## Changelog

### v1.0.0

- Первый релиз
- Поддержка TCPDF, mPDF, LibreOffice
- Интеграция с Laravel 8-12
- Конвертация text, HTML, CSV, DOC/DOCX, XLS/XLSX в PDF
