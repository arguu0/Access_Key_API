<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;

Route::get('/', function () {
    return view('welcome');
});

Route::post('/create', function (Request $request) {
    $data = $request->input('name');
    dump($data);
})
->withoutMiddleware(PreventRequestForgery::class);