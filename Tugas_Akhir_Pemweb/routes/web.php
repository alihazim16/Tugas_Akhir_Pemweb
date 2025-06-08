<?php


use Illuminate\Support\Facades\Route;

// Jika ada route backend khusus, tulis di atas sini

// Fallback: arahkan semua route ke index.html React
Route::get('/{any}', function () {
    return file_get_contents(public_path('index.html'));
})->where('any', '.*');