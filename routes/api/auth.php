<?php

declare(strict_types=1);

use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Support\Facades\Route;

Route::post('/register', RegisterController::class)
    ->middleware(['guest'])
    ->name('register');
