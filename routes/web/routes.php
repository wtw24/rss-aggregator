<?php

declare(strict_types=1);

use App\Http\Controllers\Auth\VerificationNoticeController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => view('welcome'));

Route::get('/email/verify', VerificationNoticeController::class)
    ->middleware('guest')
    ->name('verification.notice');
