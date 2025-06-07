<?php

declare(strict_types=1);

namespace App\Services\Security;

use InvalidArgumentException;
use RuntimeException;
use Throwable;

final class ExceptionSanitizer
{
    /**
     * @param  list<string>  $sensitivePatterns  An array of regular expressions to search.
     * @param  list<class-string<Throwable>>  $safeExceptionTypes  An array of “safe” exception classes.
     */
    public function __construct(
        private array $sensitivePatterns = [],
        private array $safeExceptionTypes = [],
        private readonly string $replacementText = '[REDACTED]',
        private readonly bool $enabled = true,
    ) {}

    /**
     * Sanitizes an exception message to prevent sensitive data leakage.
     * If the exception type is marked as "safe", its message is returned as is.
     *
     * @throws RuntimeException if the pattern is not a valid regex.
     */
    public function sanitizeMessage(Throwable $exception): string
    {
        if (! $this->enabled) {
            return $exception->getMessage();
        }

        if ($this->isSafeException($exception)) {
            return $exception->getMessage();
        }

        return $this->sanitizeSensitiveInfo($exception->getMessage());
    }

    /**
     * Replaces all occurrences of sensitive patterns in a text with a replacement string.
     *
     * @throws RuntimeException if the pattern is not a valid regex.
     */
    public function sanitizeSensitiveInfo(string $text): string
    {
        if (empty($this->sensitivePatterns)) {
            return $text;
        }

        $text = preg_replace($this->sensitivePatterns, $this->replacementText, $text);

        if ($text === null) {
            throw new RuntimeException(
                'Failed to sanitize text due to a preg_replace error. Check sensitive patterns. Error: '.preg_last_error_msg()
            );
        }

        return $text;
    }

    /**
     * Returns a safe array representation of the exception for logging or API responses.
     *
     * @return array{
     *      type: class-string<Throwable>,
     *      message: string,
     *      code: int,
     *      file: string,
     *      line: int,
     *      previous: ?class-string<Throwable>
     *  }
     */
    public function getSafeExceptionData(Throwable $exception): array
    {
        return [
            'type' => get_class($exception),
            'message' => $this->sanitizeMessage($exception),
            'code' => $exception->getCode(),
            'file' => basename($exception->getFile()),
            'line' => $exception->getLine(),
            'previous' => $exception->getPrevious() ? get_class($exception->getPrevious()) : null,
        ];
    }

    /**
     * Adds a sensitive regex pattern.
     *
     * @throws InvalidArgumentException if the pattern is not a valid regex.
     */
    public function addSensitivePattern(string $pattern): void
    {
        if (@preg_match($pattern, '') === false) {
            throw new InvalidArgumentException(
                message: "Invalid regex pattern provided: {$pattern}. Error: ".preg_last_error_msg()
            );
        }

        if (! in_array($pattern, $this->sensitivePatterns, true)) {
            $this->sensitivePatterns[] = $pattern;
        }
    }

    /**
     * Adds a "safe" exception class name. Messages from these exceptions won't be sanitized.
     *
     * @throws InvalidArgumentException if the class does not exist or is not a Throwable.
     */
    public function addSafeExceptionType(string $exceptionClass): void
    {
        if (! class_exists($exceptionClass) || ! is_a($exceptionClass, Throwable::class, true)) {
            throw new InvalidArgumentException("Class {$exceptionClass} does not exist or is not a Throwable.");
        }

        if (! in_array($exceptionClass, $this->safeExceptionTypes, true)) {
            $this->safeExceptionTypes[] = $exceptionClass;
        }
    }

    /**
     * Checks if the exception is of a type that is considered safe.
     */
    private function isSafeException(Throwable $exception): bool
    {
        return array_any($this->safeExceptionTypes, static fn ($type): bool => $exception instanceof $type);
    }
}
