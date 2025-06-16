<?php

declare(strict_types=1);

use App\Http\Controllers\V1\Feeds\DeleteController;
use App\Http\Controllers\V1\Feeds\IndexController;
use App\Http\Controllers\V1\Feeds\ShowController;
use App\Http\Controllers\V1\Feeds\StoreController;
use App\Http\Controllers\V1\Feeds\UpdateController;
use Illuminate\Support\Facades\Route;

Route::get('/', IndexController::class)->name('index');
Route::get('{id}', ShowController::class)->name('show');

Route::post('/', StoreController::class)->name('store');
Route::put('{feed}', UpdateController::class)->name('update');
Route::delete('{feed}', DeleteController::class)->name('delete');
