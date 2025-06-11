<?php

declare(strict_types=1);

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

require base_path('routes/api/auth.php');

Route::get('/user', fn (Request $request) => $request->user())->middleware('auth:sanctum');
