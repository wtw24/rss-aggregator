<?php

declare(strict_types=1);

use App\Http\Controllers\Auth\LoginViewController;
use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => view('welcome'));

Route::get('/verify-email/{id}/{hash}', VerifyEmailController::class)
    ->middleware(['signed', 'throttle:verify-email'])
    ->name('verification.verify');

Route::get('/login', LoginViewController::class)
    ->middleware('guest')
    ->name('login');
