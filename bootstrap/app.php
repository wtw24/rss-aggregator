<?php

declare(strict_types=1);

use App\Factories\ErrorFactory;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web/routes.php',
        api: __DIR__.'/../routes/api/routes.php',
        commands: __DIR__.'/../routes/console/routes.php',
        health: '/up',
        apiPrefix: '',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(static fn (Throwable $exception, Request $request) => ErrorFactory::create(
            exception: $exception,
            request: $request,
        ));
    })->create();
