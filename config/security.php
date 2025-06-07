<?php

declare(strict_types=1);

return [
    'exception_sanitizer' => [
        'enabled' => env('EXCEPTION_SANITIZER_ENABLED', true),

        'replacement_text' => '[REDACTED]',

        'sensitive_patterns' => [
            // Passwords
            '/password[\'"\s]*[=:]\s*[\'"][^\'"]*[\'"]/i',
            '/password_confirmation[\'"\s]*[=:]\s*[\'"][^\'"]*[\'"]/i',
            '/passwd[\'"\s]*[=:]\s*[\'"][^\'"]*[\'"]/i',
            '/pwd[\'"\s]*[=:]\s*[\'"][^\'"]*[\'"]/i',

            // Tokens and keys
            '/token[\'"\s]*[=:]\s*[\'"][^\'"]*[\'"]/i',
            '/api_token[\'"\s]*[=:]\s*[\'"][^\'"]*[\'"]/i',
            '/access_token[\'"\s]*[=:]\s*[\'"][^\'"]*[\'"]/i',
            '/bearer[\'"\s]*[=:]\s*[\'"][^\'"]*[\'"]/i',
            '/secret[\'"\s]*[=:]\s*[\'"][^\'"]*[\'"]/i',
            '/key[\'"\s]*[=:]\s*[\'"][^\'"]*[\'"]/i',
            '/api_key[\'"\s]*[=:]\s*[\'"][^\'"]*[\'"]/i',

            // Credit card data
            '/card[_\s]*number[\'"\s]*[=:]\s*[\'"][^\'"]*[\'"]/i',
            '/cvv[\'"\s]*[=:]\s*[\'"][^\'"]*[\'"]/i',
            '/cvc[\'"\s]*[=:]\s*[\'"][^\'"]*[\'"]/i',

            // Personal data
            '/ssn[\'"\s]*[=:]\s*[\'"][^\'"]*[\'"]/i',
            '/social[_\s]*security[\'"\s]*[=:]\s*[\'"][^\'"]*[\'"]/i',

            // Database credentials в connection strings
            '/mysql:\/\/[^:]+:[^@]+@/i',
            '/postgres:\/\/[^:]+:[^@]+@/i',
            '/redis:\/\/[^:]+:[^@]+@/i',

            // JWT tokens (starting with eyJ)
            '/eyJ[A-Za-z0-9_-]+\.[A-Za-z0-9_-]+\.[A-Za-z0-9_-]+/',

            // Potential hashes and long strings (more than 32 characters from base64)
            '/[A-Za-z0-9+\/]{32,}={0,2}/',
        ],

        'safe_exception_types' => [
            Illuminate\Auth\AuthenticationException::class,
            Illuminate\Auth\Access\AuthorizationException::class,
            Illuminate\Validation\ValidationException::class,
            Illuminate\Http\Exceptions\HttpResponseException::class,
            Illuminate\Http\Exceptions\PostTooLargeException::class,
            Illuminate\Http\Exceptions\ThrottleRequestsException::class,
            Illuminate\Database\UniqueConstraintViolationException::class,
            Illuminate\Session\TokenMismatchException::class,

            Symfony\Component\HttpKernel\Exception\NotFoundHttpException::class,
            Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException::class,
            Symfony\Component\HttpKernel\Exception\BadRequestHttpException::class,
            Symfony\Component\HttpKernel\Exception\ConflictHttpException::class,
            Symfony\Component\HttpKernel\Exception\GoneHttpException::class,
            Symfony\Component\HttpKernel\Exception\LengthRequiredHttpException::class,
            Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException::class,
            Symfony\Component\HttpKernel\Exception\NotAcceptableHttpException::class,
            Symfony\Component\HttpKernel\Exception\PreconditionFailedHttpException::class,
            Symfony\Component\HttpKernel\Exception\PreconditionRequiredHttpException::class,
            Symfony\Component\HttpKernel\Exception\ServiceUnavailableHttpException::class,
            Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException::class,
            Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException::class,
            Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException::class,
            Symfony\Component\HttpKernel\Exception\UnsupportedMediaTypeHttpException::class,
        ],
    ],
];
