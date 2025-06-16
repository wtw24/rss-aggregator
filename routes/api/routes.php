<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

require base_path('routes/api/auth.php');

Route::prefix('v1')->as('v1:')->group(static function (): void {
    Route::middleware(['auth:sanctum', 'throttle:api', 'verified'])->group(static function (): void {

        Route::prefix('feeds')->as('feeds:')->group(base_path(
            path: 'routes/api/v1/feeds.php',
        ));

        Route::prefix('articles')->as('articles:')->group(base_path(
            path: 'routes/api/v1/articles.php',
        ));
    });
});
