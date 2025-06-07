<?php

declare(strict_types=1);

namespace App\Providers;

use App\Services\Security\ExceptionSanitizer;
use Illuminate\Support\ServiceProvider;
use Throwable;

final class SecurityServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(ExceptionSanitizer::class, static function (): ExceptionSanitizer {
            /**
             * @var array{
             *     enabled?: bool,
             *     replacement_text?: string,
             *     sensitive_patterns?: list<string>,
             *     safe_exception_types?: list<class-string<Throwable>>
             * } $config */
            $config = config('security.exception_sanitizer');

            return new ExceptionSanitizer(
                sensitivePatterns: $config['sensitive_patterns'] ?? [],
                safeExceptionTypes: $config['safe_exception_types'] ?? [],
                replacementText: $config['replacement_text'] ?? '[REDACTED]',
                enabled: $config['enabled'] ?? true,
            );
        });
    }
}
