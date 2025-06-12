<?php

declare(strict_types=1);

use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;

Route::middleware(['guest'])->group(function (): void {
    Route::post('/login', LoginController::class)->name('login');
    Route::post('/register', RegisterController::class)->name('register');
    Route::post('/forgot-password', ForgotPasswordController::class)->name('password.email');
    Route::post('/reset-password', ResetPasswordController::class)->name('password.store');
});

Route::get('/verify-email/{id}/{hash}', VerifyEmailController::class)
    ->middleware(['signed', 'throttle:6,1'])
    ->name('verification.verify');

Route::post('/email/verification-notification', EmailVerificationNotificationController::class)
    ->middleware(['auth:sanctum', 'throttle:6,1'])
    ->name('verification.send');
