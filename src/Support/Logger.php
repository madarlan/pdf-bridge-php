<?php

namespace MadArlan\PDFBridge\Support;

use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

/**
 * Simple logger wrapper for PDF Bridge
 */
class Logger
{
    protected LoggerInterface $logger;

    protected bool $enabled;

    public function __construct(?LoggerInterface $logger = null, bool $enabled = true)
    {
        $this->logger = $logger ?? new NullLogger();
        $this->enabled = $enabled;
    }

    /**
     * Log conversion start
     */
    public function logConversionStart(string $type, string $input, string $converter): void
    {
        if (! $this->enabled) {
            return;
        }

        $this->logger->info('PDF conversion started', [
            'type' => $type,
            'input' => $this->sanitizeInput($input),
            'converter' => $converter,
            'timestamp' => date('c'),
        ]);
    }

    /**
     * Log conversion success
     */
    public function logConversionSuccess(string $type, string $output, float $duration, int $fileSize = 0): void
    {
        if (! $this->enabled) {
            return;
        }

        $this->logger->info('PDF conversion completed successfully', [
            'type' => $type,
            'output' => $output,
            'duration_seconds' => round($duration, 3),
            'file_size_bytes' => $fileSize,
            'timestamp' => date('c'),
        ]);
    }

    /**
     * Log conversion error
     */
    public function logConversionError(string $type, string $error, ?\Throwable $exception = null): void
    {
        if (! $this->enabled) {
            return;
        }

        $context = [
            'type' => $type,
            'error' => $error,
            'timestamp' => date('c'),
        ];

        if ($exception) {
            $context['exception'] = [
                'class' => get_class($exception),
                'message' => $exception->getMessage(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
                'trace' => $exception->getTraceAsString(),
            ];
        }

        $this->logger->error('PDF conversion failed', $context);
    }

    /**
     * Log converter availability check
     */
    public function logConverterCheck(string $converter, bool $available, ?string $error = null): void
    {
        if (! $this->enabled) {
            return;
        }

        $level = $available ? 'info' : 'warning';
        $message = $available ? 'Converter is available' : 'Converter is not available';

        $context = [
            'converter' => $converter,
            'available' => $available,
            'timestamp' => date('c'),
        ];

        if ($error) {
            $context['error'] = $error;
        }

        $this->logger->log($level, $message, $context);
    }

    /**
     * Log validation error
     */
    public function logValidationError(string $type, string $error): void
    {
        if (! $this->enabled) {
            return;
        }

        $this->logger->warning('Input validation failed', [
            'type' => $type,
            'error' => $error,
            'timestamp' => date('c'),
        ]);
    }

    /**
     * Sanitize input for logging (remove sensitive data, limit length)
     */
    protected function sanitizeInput(string $input): string
    {
        // If it's a file path, just return the filename
        if (file_exists($input)) {
            return basename($input);
        }

        // For text content, limit length and remove sensitive patterns
        $sanitized = substr($input, 0, 100);

        // Remove potential sensitive data patterns
        $sanitized = preg_replace('/\b\d{4}[-\s]?\d{4}[-\s]?\d{4}[-\s]?\d{4}\b/', '[CARD]', $sanitized);
        $sanitized = preg_replace('/\b[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Z|a-z]{2,}\b/', '[EMAIL]', $sanitized);

        return $sanitized.(strlen($input) > 100 ? '...' : '');
    }

    /**
     * Enable/disable logging
     */
    public function setEnabled(bool $enabled): self
    {
        $this->enabled = $enabled;

        return $this;
    }

    /**
     * Check if logging is enabled
     */
    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    /**
     * Set logger instance
     */
    public function setLogger(LoggerInterface $logger): self
    {
        $this->logger = $logger;

        return $this;
    }
}
