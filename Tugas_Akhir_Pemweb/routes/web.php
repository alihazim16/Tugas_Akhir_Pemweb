<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;

// Halaman utama (opsional)
Route::get('/', function () {
    return view('welcome');
});

// Route untuk login Blade
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout']);

// Route lain untuk web (Blade) bisa ditambah di sini