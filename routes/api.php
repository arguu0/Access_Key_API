<?php

use App\Http\Controllers\KeyVerificationSystem;
use App\Http\Middleware\BearerAuthMiddleware;
use Illuminate\Support\Facades\Route;


Route::get('/generate_key', [KeyVerificationSystem::class, 'generate_key'])->middleware('throttle:api');  // custom ratelimit ip-based

Route::post('/login', [KeyVerificationSystem::class, 'verify_key']);

// Middleware added in this route to check bearer token validity
Route::get('/my_key', [KeyVerificationSystem::class, 'ViewProtectedRoute'])->middleware(BearerAuthMiddleware::class);