<?php

use App\Http\Controllers\KeyVerificationSystem;
use App\Http\Middleware\BearerAuthMiddleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/generate_key', [KeyVerificationSystem::class, 'generate_key']);

Route::post('/login', [KeyVerificationSystem::class, 'verify_key']);

// Middleware added in this route to check bearer token validity
Route::get('/protected', [KeyVerificationSystem::class, 'ViewProtectedRoute'])->middleware(BearerAuthMiddleware::class);