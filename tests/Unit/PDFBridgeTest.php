<?php

namespace MadArlan\PDFBridge\Tests\Unit;

use PHPUnit\Framework\TestCase;
use MadArlan\PDFBridge\PDFBridge;
use MadArlan\PDFBridge\Exceptions\ValidationException;
use MadArlan\PDFBridge\Exceptions\UnsupportedFormatException;

class PDFBridgeTest extends TestCase
{
    protected PDFBridge $pdfBridge;

    protected function setUp(): void
    {
        $this->pdfBridge = new PDFBridge([
            'logging' => ['enabled' => false] // Disable logging for tests
        ]);
    }

    public function testGetSupportedFormats(): void
    {
        $formats = $this->pdfBridge->getSupportedFormats();
        
        $this->assertIsArray($formats);
        $this->assertContains('text', $formats);
        $this->assertContains('html', $formats);
        $this->assertContains('csv', $formats);
    }

    public function testGetAvailableConverters(): void
    {
        $converters = $this->pdfBridge->getAvailableConverters();
        
        $this->assertIsArray($converters);
        $this->assertArrayHasKey('tcpdf', $converters);
        $this->assertArrayHasKey('mpdf', $converters);
        $this->assertArrayHasKey('libreoffice', $converters);
        
        foreach ($converters as $name => $info) {
            $this->assertArrayHasKey('available', $info);
            $this->assertIsBool($info['available']);
            
            if ($info['available']) {
                $this->assertArrayHasKey('formats', $info);
                $this->assertIsArray($info['formats']);
            } else {
                $this->assertArrayHasKey('error', $info);
                $this->assertIsString($info['error']);
            }
        }
    }

    public function testConvertTextWithEmptyInput(): void
    {
        $this->expectException(ValidationException::class);
        $this->pdfBridge->convertText('');
    }

    public function testConvertTextWithValidInput(): void
    {
        $text = 'Hello, World! This is a test PDF conversion.';
        
        // Test string return (no output path)
        $result = $this->pdfBridge->convertText($text);
        $this->assertIsString($result);
        $this->assertNotEmpty($result);
        
        // Verify it's PDF content
        $this->assertStringStartsWith('%PDF', $result);
    }

    public function testConvertHTMLWithValidInput(): void
    {
        $html = '<h1>Test HTML</h1><p>This is a <strong>test</strong> HTML to PDF conversion.</p>';
        
        $result = $this->pdfBridge->convertHTML($html);
        $this->assertIsString($result);
        $this->assertNotEmpty($result);
        $this->assertStringStartsWith('%PDF', $result);
    }

    public function testConvertCSVWithValidInput(): void
    {
        $csv = "Name,Age,City\nJohn,25,New York\nJane,30,Los Angeles\nBob,35,Chicago";
        
        $result = $this->pdfBridge->convertCSV($csv);
        $this->assertIsString($result);
        $this->assertNotEmpty($result);
        $this->assertStringStartsWith('%PDF', $result);
    }

    public function testConvertCSVWithInvalidInput(): void
    {
        $this->expectException(ValidationException::class);
        
        // CSV with inconsistent columns
        $csv = "Name,Age\nJohn,25,Extra\nJane,30";
        $this->pdfBridge->convertCSV($csv);
    }

    public function testUnsupportedFileExtension(): void
    {
        $this->expectException(UnsupportedFormatException::class);
        
        // Create a temporary file with unsupported extension
        $tempFile = tempnam(sys_get_temp_dir(), 'test') . '.xyz';
        file_put_contents($tempFile, 'test content');
        
        try {
            $this->pdfBridge->convertFile($tempFile);
        } finally {
            unlink($tempFile);
        }
    }

    public function testSetAndGetConfig(): void
    {
        $config = [
            'default_converter' => 'tcpdf',
            'tcpdf' => [
                'format' => 'A3'
            ]
        ];
        
        $this->pdfBridge->setConfig($config);
        $retrievedConfig = $this->pdfBridge->getConfig();
        
        $this->assertEquals('tcpdf', $retrievedConfig['default_converter']);
        $this->assertEquals('A3', $retrievedConfig['tcpdf']['format']);
    }

    public function testConvertFileWithTextFile(): void
    {
        $content = 'This is a test text file content for PDF conversion.';
        $tempFile = tempnam(sys_get_temp_dir(), 'test') . '.txt';
        file_put_contents($tempFile, $content);
        
        try {
            $result = $this->pdfBridge->convertFile($tempFile);
            $this->assertIsString($result);
            $this->assertStringStartsWith('%PDF', $result);
        } finally {
            unlink($tempFile);
        }
    }

    public function testConvertFileWithHTMLFile(): void
    {
        $content = '<html><body><h1>Test HTML File</h1><p>Content</p></body></html>';
        $tempFile = tempnam(sys_get_temp_dir(), 'test') . '.html';
        file_put_contents($tempFile, $content);
        
        try {
            $result = $this->pdfBridge->convertFile($tempFile);
            $this->assertIsString($result);
            $this->assertStringStartsWith('%PDF', $result);
        } finally {
            unlink($tempFile);
        }
    }
}
