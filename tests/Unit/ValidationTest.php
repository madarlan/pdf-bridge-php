<?php

namespace MadArlan\PDFBridge\Tests\Unit;

use PHPUnit\Framework\TestCase;
use MadArlan\PDFBridge\Validation\InputValidator;
use MadArlan\PDFBridge\Exceptions\ValidationException;

class ValidationTest extends TestCase
{
    protected InputValidator $validator;

    protected function setUp(): void
    {
        $this->validator = new InputValidator([
            'max_file_size' => 1024 * 1024, // 1MB for testing
            'max_text_length' => 1000,
            'allowed_extensions' => ['txt', 'html', 'csv', 'doc', 'docx']
        ]);
    }

    public function testValidateTextSuccess(): void
    {
        $this->validator->validateText('Valid text content');
        $this->assertTrue(true); // No exception thrown
    }

    public function testValidateTextEmpty(): void
    {
        $this->expectException(ValidationException::class);
        $this->validator->validateText('');
    }

    public function testValidateTextTooLong(): void
    {
        $this->expectException(ValidationException::class);
        $longText = str_repeat('a', 1001);
        $this->validator->validateText($longText);
    }

    public function testValidateFileNotExists(): void
    {
        $this->expectException(ValidationException::class);
        $this->validator->validateFile('/non/existent/file.txt');
    }

    public function testValidateFileSuccess(): void
    {
        $tempFile = tempnam(sys_get_temp_dir(), 'test') . '.txt';
        file_put_contents($tempFile, 'test content');
        
        try {
            $this->validator->validateFile($tempFile);
            $this->assertTrue(true); // No exception thrown
        } finally {
            unlink($tempFile);
        }
    }

    public function testValidateFileUnsupportedExtension(): void
    {
        $this->expectException(ValidationException::class);
        
        $tempFile = tempnam(sys_get_temp_dir(), 'test') . '.xyz';
        file_put_contents($tempFile, 'test content');
        
        try {
            $this->validator->validateFile($tempFile);
        } finally {
            unlink($tempFile);
        }
    }

    public function testValidateCSVSuccess(): void
    {
        $csv = "Name,Age\nJohn,25\nJane,30";
        $this->validator->validateCSV($csv);
        $this->assertTrue(true); // No exception thrown
    }

    public function testValidateCSVInconsistentColumns(): void
    {
        $this->expectException(ValidationException::class);
        
        $csv = "Name,Age\nJohn,25,Extra\nJane,30";
        $this->validator->validateCSV($csv);
    }

    public function testValidateCSVEmpty(): void
    {
        $this->expectException(ValidationException::class);
        $this->validator->validateCSV('');
    }

    public function testValidateOutputPathSuccess(): void
    {
        $tempDir = sys_get_temp_dir();
        $outputPath = $tempDir . DIRECTORY_SEPARATOR . 'test_output.pdf';
        
        $this->validator->validateOutputPath($outputPath);
        $this->assertTrue(true); // No exception thrown
    }

    public function testSetAndGetConfig(): void
    {
        $newConfig = [
            'max_file_size' => 2048,
            'max_text_length' => 2000
        ];
        
        $this->validator->setConfig($newConfig);
        $config = $this->validator->getConfig();
        
        $this->assertEquals(2048, $config['max_file_size']);
        $this->assertEquals(2000, $config['max_text_length']);
    }
}
