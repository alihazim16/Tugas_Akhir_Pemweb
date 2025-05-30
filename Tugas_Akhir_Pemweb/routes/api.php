<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;

// Group untuk autentikasi JWT
Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login',    [AuthController::class, 'login']);
    Route::post('logout',   [AuthController::class, 'logout'])->middleware('auth:api');
    Route::get('me',        [AuthController::class, 'me'])->middleware('auth:api');
});

// Contoh proteksi route untuk admin menggunakan role middleware
Route::middleware(['auth:api', 'role:admin'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index']);
});

// Bisa tambah grup role lain juga jika dibutuhkan
Route::middleware(['auth:api', 'role:user'])->group(function () {
    Route::get('/profile', function () {
        return response()->json(['message' => 'Ini halaman user']);
    });
});
