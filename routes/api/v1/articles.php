<?php

declare(strict_types=1);

use App\Http\Controllers\V1\Articles\IndexController;
use App\Http\Controllers\V1\Articles\ShowController;
use Illuminate\Support\Facades\Route;

Route::get('/', IndexController::class)->name('index');
Route::get('{id}', ShowController::class)->name('show');
