<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Default PDF Converter
    |--------------------------------------------------------------------------
    |
    | This option controls the default PDF converter that will be used
    | when no specific converter is specified. Available options:
    | 'tcpdf', 'mpdf', 'libreoffice'
    |
    */
    'default' => env('PDF_BRIDGE_DEFAULT_CONVERTER', 'mpdf'),

    /*
    |--------------------------------------------------------------------------
    | TCPDF Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration options for TCPDF library
    |
    */
    'tcpdf' => [
        'orientation' => env('PDF_BRIDGE_TCPDF_ORIENTATION', 'P'), // P=Portrait, L=Landscape
        'unit' => env('PDF_BRIDGE_TCPDF_UNIT', 'mm'), // pt, mm, cm, in
        'format' => env('PDF_BRIDGE_TCPDF_FORMAT', 'A4'), // A4, A3, Letter, etc.
        'unicode' => env('PDF_BRIDGE_TCPDF_UNICODE', true),
        'encoding' => env('PDF_BRIDGE_TCPDF_ENCODING', 'UTF-8'),
        'diskcache' => env('PDF_BRIDGE_TCPDF_DISKCACHE', false),
        'pdfa' => env('PDF_BRIDGE_TCPDF_PDFA', false),

        // Font settings
        'font' => [
            'family' => env('PDF_BRIDGE_TCPDF_FONT_FAMILY', 'helvetica'),
            'size' => env('PDF_BRIDGE_TCPDF_FONT_SIZE', 12),
            'style' => env('PDF_BRIDGE_TCPDF_FONT_STYLE', ''),
        ],

        // Margins
        'margins' => [
            'left' => env('PDF_BRIDGE_TCPDF_MARGIN_LEFT', 15),
            'top' => env('PDF_BRIDGE_TCPDF_MARGIN_TOP', 27),
            'right' => env('PDF_BRIDGE_TCPDF_MARGIN_RIGHT', 15),
            'bottom' => env('PDF_BRIDGE_TCPDF_MARGIN_BOTTOM', 25),
            'header' => env('PDF_BRIDGE_TCPDF_MARGIN_HEADER', 5),
            'footer' => env('PDF_BRIDGE_TCPDF_MARGIN_FOOTER', 10),
        ],

        // Header and Footer
        'header' => [
            'enabled' => env('PDF_BRIDGE_TCPDF_HEADER_ENABLED', false),
            'title' => env('PDF_BRIDGE_TCPDF_HEADER_TITLE', ''),
            'string' => env('PDF_BRIDGE_TCPDF_HEADER_STRING', ''),
        ],

        'footer' => [
            'enabled' => env('PDF_BRIDGE_TCPDF_FOOTER_ENABLED', false),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | mPDF Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration options for mPDF library
    |
    */
    'mpdf' => [
        'mode' => env('PDF_BRIDGE_MPDF_MODE', 'utf-8'),
        'format' => env('PDF_BRIDGE_MPDF_FORMAT', 'A4'),
        'default_font_size' => env('PDF_BRIDGE_MPDF_FONT_SIZE', 12),
        'default_font' => env('PDF_BRIDGE_MPDF_FONT', 'dejavusans'),
        'margin_left' => env('PDF_BRIDGE_MPDF_MARGIN_LEFT', 15),
        'margin_right' => env('PDF_BRIDGE_MPDF_MARGIN_RIGHT', 15),
        'margin_top' => env('PDF_BRIDGE_MPDF_MARGIN_TOP', 16),
        'margin_bottom' => env('PDF_BRIDGE_MPDF_MARGIN_BOTTOM', 16),
        'margin_header' => env('PDF_BRIDGE_MPDF_MARGIN_HEADER', 9),
        'margin_footer' => env('PDF_BRIDGE_MPDF_MARGIN_FOOTER', 9),
        'orientation' => env('PDF_BRIDGE_MPDF_ORIENTATION', 'P'), // P=Portrait, L=Landscape

        // Advanced options
        'tempDir' => env('PDF_BRIDGE_MPDF_TEMP_DIR', sys_get_temp_dir()),
        'fontDir' => env('PDF_BRIDGE_MPDF_FONT_DIR', null),
        'fontdata' => [],
        'default_font_size' => env('PDF_BRIDGE_MPDF_DEFAULT_FONT_SIZE', 12),
        'allow_charset_conversion' => env('PDF_BRIDGE_MPDF_ALLOW_CHARSET_CONVERSION', true),
        'charset_in' => env('PDF_BRIDGE_MPDF_CHARSET_IN', 'UTF-8'),
        'format' => env('PDF_BRIDGE_MPDF_FORMAT', 'A4'),
        'autoScriptToLang' => env('PDF_BRIDGE_MPDF_AUTO_SCRIPT_TO_LANG', true),
        'autoLangToFont' => env('PDF_BRIDGE_MPDF_AUTO_LANG_TO_FONT', true),
    ],

    /*
    |--------------------------------------------------------------------------
    | LibreOffice Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration options for LibreOffice converter
    |
    */
    'libreoffice' => [
        'bin' => env('PDF_BRIDGE_LIBREOFFICE_BIN', '/usr/bin/libreoffice'),
        'temp_dir' => env('PDF_BRIDGE_LIBREOFFICE_TEMP_DIR', sys_get_temp_dir()),
        'timeout' => env('PDF_BRIDGE_LIBREOFFICE_TIMEOUT', 120), // seconds

        // Supported input formats
        'supported_formats' => [
            'doc', 'docx', 'odt', 'rtf',
            'xls', 'xlsx', 'ods', 'csv',
            'ppt', 'pptx', 'odp',
            'html', 'htm',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | General Settings
    |--------------------------------------------------------------------------
    |
    | General configuration options
    |
    */
    'general' => [
        'output_dir' => env('PDF_BRIDGE_OUTPUT_DIR', storage_path('app/pdf')),
        'temp_dir' => env('PDF_BRIDGE_TEMP_DIR', sys_get_temp_dir()),
        'cleanup_temp' => env('PDF_BRIDGE_CLEANUP_TEMP', true),
        'max_file_size' => env('PDF_BRIDGE_MAX_FILE_SIZE', 50 * 1024 * 1024), // 50MB in bytes
        'allowed_extensions' => [
            'txt', 'html', 'htm',
            'doc', 'docx', 'odt', 'rtf',
            'xls', 'xlsx', 'ods', 'csv',
            'ppt', 'pptx', 'odp',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Validation Settings
    |--------------------------------------------------------------------------
    |
    | Input validation configuration
    |
    */
    'validation' => [
        'max_file_size' => env('PDF_BRIDGE_MAX_FILE_SIZE', 50 * 1024 * 1024), // 50MB
        'max_text_length' => env('PDF_BRIDGE_MAX_TEXT_LENGTH', 1024 * 1024), // 1MB
        'allowed_extensions' => [
            'txt', 'html', 'htm',
            'doc', 'docx', 'odt', 'rtf',
            'xls', 'xlsx', 'ods', 'csv',
            'ppt', 'pptx', 'odp',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Logging Settings
    |--------------------------------------------------------------------------
    |
    | Logging configuration for PDF Bridge operations
    |
    */
    'logging' => [
        'enabled' => env('PDF_BRIDGE_LOGGING_ENABLED', true),
        'channel' => env('PDF_BRIDGE_LOG_CHANNEL', 'default'),
        'level' => env('PDF_BRIDGE_LOG_LEVEL', 'info'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Converter Priority
    |--------------------------------------------------------------------------
    |
    | Define which converter to use for specific file types
    | If a converter is not available, it will fallback to the next one
    |
    */
    'converter_priority' => [
        'text' => ['tcpdf', 'mpdf'],
        'html' => ['mpdf', 'tcpdf'],
        'doc' => ['libreoffice'],
        'docx' => ['libreoffice'],
        'xls' => ['libreoffice'],
        'xlsx' => ['libreoffice'],
        'csv' => ['libreoffice', 'tcpdf', 'mpdf'],
        'odt' => ['libreoffice'],
        'ods' => ['libreoffice'],
        'ppt' => ['libreoffice'],
        'pptx' => ['libreoffice'],
        'odp' => ['libreoffice'],
        'rtf' => ['libreoffice'],
    ],
];
