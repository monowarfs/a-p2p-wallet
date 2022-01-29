<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

Route::get('login', function () {
    return response()->json([
        'code' => 404,
        'messages' => ['Invalid Endpoint'],
        'data' => null,
    ], 200);
})->name('login');

Route::fallback(function () {
    return response()->json([
        'code' => 404,
        'messages' => ['Invalid Endpoint'],
        'data' => null,
    ], 200);
});

Route::get('/', function () {
    return view('welcome');
});
