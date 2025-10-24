# Security Policy

## Supported Versions

We actively support the following versions of PDF Bridge with security updates:

| Version | Supported          |
| ------- | ------------------ |
| 2.x     | :white_check_mark: |
| 1.x     | :x:                |

## Reporting a Vulnerability

If you discover a security vulnerability within PDF Bridge, please send an email to **madinovarlan@gmail.com**. All security vulnerabilities will be promptly addressed.

**Please do not report security vulnerabilities through public GitHub issues.**

### What to Include

When reporting a vulnerability, please include:

- A description of the vulnerability
- Steps to reproduce the issue
- Potential impact of the vulnerability
- Any suggested fixes (if available)

### Response Timeline

- **Initial Response**: Within 48 hours
- **Status Update**: Within 7 days
- **Fix Release**: Within 30 days (depending on complexity)

## Security Considerations

### File Upload Security

When processing user-uploaded files:

```php
// ✅ Good: Validate file size and type
$config = [
    'validation' => [
        'max_file_size' => 10 * 1024 * 1024, // 10MB
        'allowed_extensions' => ['txt', 'html', 'doc', 'docx']
    ]
];

$pdfBridge = new PDFBridge($config);

try {
    $pdfBridge->convertFile($uploadedFile, $outputPath);
} catch (ValidationException $e) {
    // Handle validation errors safely
}
```

```php
// ❌ Bad: No validation
$pdfBridge->convertFile($_FILES['upload']['tmp_name'], $outputPath);
```

### Output Path Security

Always validate and sanitize output paths:

```php
// ✅ Good: Validate output directory
$safeOutputDir = storage_path('app/pdf/');
$filename = basename($userInput) . '.pdf';
$outputPath = $safeOutputDir . $filename;

// Ensure the path is within allowed directory
if (strpos(realpath($outputPath), realpath($safeOutputDir)) !== 0) {
    throw new \InvalidArgumentException('Invalid output path');
}
```

```php
// ❌ Bad: Direct user input in path
$outputPath = '/var/www/' . $_POST['filename']; // Path traversal risk
```

### HTML Content Security

When converting HTML from user input:

```php
// ✅ Good: Sanitize HTML content
$cleanHtml = strip_tags($userHtml, '<p><br><strong><em><h1><h2><h3>');
$pdfBridge->convertHTML($cleanHtml, $outputPath);
```

```php
// ❌ Bad: Raw user HTML (XSS risk in PDF)
$pdfBridge->convertHTML($_POST['html_content'], $outputPath);
```

### LibreOffice Security

LibreOffice processes can be resource-intensive:

```php
// ✅ Good: Set timeouts and limits
$config = [
    'libreoffice' => [
        'timeout' => 60, // 60 seconds max
        'temp_dir' => '/secure/temp/path'
    ],
    'validation' => [
        'max_file_size' => 50 * 1024 * 1024 // 50MB limit
    ]
];
```

### Logging Security

Avoid logging sensitive information:

```php
// ✅ Good: Log safely
$logger->info('PDF conversion started', [
    'file_type' => $fileExtension,
    'file_size' => $fileSize,
    'user_id' => $userId
]);
```

```php
// ❌ Bad: Log sensitive data
$logger->info('Converting file', [
    'file_content' => $fileContent, // May contain sensitive data
    'user_email' => $userEmail      // PII
]);
```

## Best Practices

1. **Input Validation**: Always validate file sizes, types, and content
2. **Output Sanitization**: Ensure output paths are safe and within allowed directories
3. **Resource Limits**: Set appropriate timeouts and memory limits
4. **Error Handling**: Don't expose internal paths or system information in error messages
5. **Logging**: Log security events but avoid sensitive data
6. **Updates**: Keep PDF Bridge and its dependencies updated
7. **Permissions**: Run with minimal required permissions
8. **Isolation**: Consider running conversions in isolated environments for high-risk scenarios

## Dependencies Security

PDF Bridge relies on several third-party libraries. Regularly update:

- `tecnickcom/tcpdf`
- `mpdf/mpdf`
- `ncjoes/office-converter`
- LibreOffice system installation

Monitor security advisories for these dependencies.

## Disclosure Policy

We follow responsible disclosure practices:

1. Security researchers report vulnerabilities privately
2. We acknowledge receipt within 48 hours
3. We work to understand and reproduce the issue
4. We develop and test a fix
5. We coordinate disclosure timing with the reporter
6. We release the fix and publish security advisory
7. We credit the reporter (if desired)

Thank you for helping keep PDF Bridge secure!
