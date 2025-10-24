<?php

namespace MadArlan\PDFBridge\Tests\Unit;

use MadArlan\PDFBridge\Exceptions\ValidationException;
use MadArlan\PDFBridge\Validation\InputValidator;
use PHPUnit\Framework\TestCase;

class ValidationTest extends TestCase
{
    protected InputValidator $validator;

    protected function setUp(): void
    {
        $this->validator = new InputValidator([
            'max_file_size' => 1024 * 1024, // 1MB for testing
            'max_text_length' => 1000,
            'allowed_extensions' => ['txt', 'html', 'csv', 'doc', 'docx'],
        ]);
    }

    public function test_validate_text_success(): void
    {
        $this->validator->validateText('Valid text content');
        $this->assertTrue(true); // No exception thrown
    }

    public function test_validate_text_empty(): void
    {
        $this->expectException(ValidationException::class);
        $this->validator->validateText('');
    }

    public function test_validate_text_too_long(): void
    {
        $this->expectException(ValidationException::class);
        $longText = str_repeat('a', 1001);
        $this->validator->validateText($longText);
    }

    public function test_validate_file_not_exists(): void
    {
        $this->expectException(ValidationException::class);
        $this->validator->validateFile('/non/existent/file.txt');
    }

    public function test_validate_file_success(): void
    {
        $tempFile = tempnam(sys_get_temp_dir(), 'test').'.txt';
        file_put_contents($tempFile, 'test content');

        try {
            $this->validator->validateFile($tempFile);
            $this->assertTrue(true); // No exception thrown
        } finally {
            unlink($tempFile);
        }
    }

    public function test_validate_file_unsupported_extension(): void
    {
        $this->expectException(ValidationException::class);

        $tempFile = tempnam(sys_get_temp_dir(), 'test').'.xyz';
        file_put_contents($tempFile, 'test content');

        try {
            $this->validator->validateFile($tempFile);
        } finally {
            unlink($tempFile);
        }
    }

    public function test_validate_csv_success(): void
    {
        $csv = "Name,Age\nJohn,25\nJane,30";
        $this->validator->validateCSV($csv);
        $this->assertTrue(true); // No exception thrown
    }

    public function test_validate_csv_inconsistent_columns(): void
    {
        $this->expectException(ValidationException::class);

        $csv = "Name,Age\nJohn,25,Extra\nJane,30";
        $this->validator->validateCSV($csv);
    }

    public function test_validate_csv_empty(): void
    {
        $this->expectException(ValidationException::class);
        $this->validator->validateCSV('');
    }

    public function test_validate_output_path_success(): void
    {
        $tempDir = sys_get_temp_dir();
        $outputPath = $tempDir.DIRECTORY_SEPARATOR.'test_output.pdf';

        $this->validator->validateOutputPath($outputPath);
        $this->assertTrue(true); // No exception thrown
    }

    public function test_set_and_get_config(): void
    {
        $newConfig = [
            'max_file_size' => 2048,
            'max_text_length' => 2000,
        ];

        $this->validator->setConfig($newConfig);
        $config = $this->validator->getConfig();

        $this->assertEquals(2048, $config['max_file_size']);
        $this->assertEquals(2000, $config['max_text_length']);
    }
}
