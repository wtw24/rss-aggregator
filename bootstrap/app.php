<?php

declare(strict_types=1);

use App\Factories\ErrorFactory;
use App\Http\Middleware\CertificateTransparencyPolicy;
use App\Http\Middleware\ContentTypeOptions;
use App\Http\Middleware\PermissionsPolicy;
use App\Http\Middleware\RemoveHeaders;
use App\Http\Middleware\SetReferrerPolicy;
use App\Http\Middleware\StrictTransportSecurity;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web/routes.php',
        api: __DIR__.'/../routes/api/routes.php',
        commands: __DIR__.'/../routes/console/routes.php',
        health: '/up',
        apiPrefix: '',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->api(prepend: [
            EnsureFrontendRequestsAreStateful::class,
            RemoveHeaders::class,
            SetReferrerPolicy::class,
            StrictTransportSecurity::class,
            PermissionsPolicy::class,
            ContentTypeOptions::class,
            CertificateTransparencyPolicy::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(static fn (Throwable $exception, Request $request) => ErrorFactory::create(
            exception: $exception,
            request: $request,
        ));
    })->create();
