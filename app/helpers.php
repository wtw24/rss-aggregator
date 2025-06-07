<?php

declare(strict_types=1);

use App\Services\Security\ExceptionSanitizer;

if (! function_exists('sanitize_exception_message')) {
    /**
     * Sanitizing the message of an exception.
     *
     * @throws RuntimeException if the pattern is not a valid regex.
     */
    function sanitize_exception_message(Throwable $exception): string
    {
        return app(ExceptionSanitizer::class)->sanitizeMessage($exception);
    }
}

if (! function_exists('sanitize_sensitive_info')) {
    /**
     * Replaces all occurrences of sensitive patterns in a text with a replacement string.
     *
     * @throws RuntimeException if the pattern is not a valid regex.
     */
    function sanitize_sensitive_info(string $text): string
    {
        return app(ExceptionSanitizer::class)->sanitizeSensitiveInfo($text);
    }
}

if (! function_exists('safe_exception_data')) {
    /**
     * Get safe exception data for logging.
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
    function safe_exception_data(Throwable $exception): array
    {
        return app(ExceptionSanitizer::class)->getSafeExceptionData($exception);
    }
}
