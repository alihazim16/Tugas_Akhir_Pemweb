<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\AuthWebController;
use App\Http\Controllers\Web\DashboardWebController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| These routes are for web (Blade-based) views. If you're building
| an API, use routes/api.php. These are intended for form pages,
| frontend rendering with Blade.
|
*/

// Halaman utama
Route::get('/', function () {
    return view('welcome');
});

// Halaman Register
Route::get('/register', function () {
    return view('register');
});

// Halaman Login
Route::get('/login', function () {
    return view('login');
});

// Dashboard (jika sudah login)
Route::get('/dashboard', [DashboardWebController::class, 'index'])->middleware('auth');

// Fallback jika route tidak ditemukan
Route::fallback(function () {
    return response()->view('errors.404', [], 404);
});
