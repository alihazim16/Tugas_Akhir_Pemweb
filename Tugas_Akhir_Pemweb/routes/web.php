<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth; // Digunakan untuk Auth::check() dan Auth::logout()

// Import semua Controller yang digunakan di web.php
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DashboardController;
// Jika DashboardController Anda berada di sub-folder 'Web', maka gunakan ini:
// use App\Http\Controllers\Web\DashboardWebController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Di sini Anda dapat mendaftarkan rute web untuk aplikasi Anda. Rute ini
| dimuat oleh RouteServiceProvider dalam grup yang berisi middleware "web".
| Buatlah sesuatu yang hebat!
|
*/

// Rute untuk halaman utama proyek.
// Jika user sudah login, akan diarahkan ke dashboard.
// Jika user belum login, akan diarahkan ke halaman login.
Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
});

// =====================================================================
// RUTE AUTENTIKASI (LOGIN & REGISTER)
// Ini adalah rute yang tidak membutuhkan user untuk login.
// Middleware 'guest' mencegah user yang sudah login mengakses halaman ini.
// =====================================================================

Route::middleware('guest')->group(function () {
    // Rute untuk menampilkan form Register dan memproses data Register
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);

    // Rute untuk menampilkan form Login dan memproses data Login
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});

// =====================================================================
// RUTE PERANTARA UNTUK TOKEN JWT (SETELAH LOGIN UI)
// =====================================================================

// Rute perantara ini menerima token JWT sebagai parameter URL setelah login UI berhasil.
// Ini akan menyimpan token ke localStorage dan kemudian mengarahkan ke dashboard.
Route::get('/token-receiver/{token?}', function ($token = null) {
    return view('token_receiver', ['token' => $token]);
})->name('token.receiver');


// =====================================================================
// RUTE YANG DILINDUNGI (Membutuhkan Login)
// Semua rute di dalam grup ini akan memerlukan autentikasi ('auth' middleware).
// Jika user belum login, mereka akan diarahkan ke halaman login.
// =====================================================================

Route::middleware('auth')->group(function () {
    // Rute Dashboard
    // Menggunakan DashboardController yang kita buat sebelumnya.
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    // Jika DashboardController Anda ada di sub-folder 'Web', maka gunakan ini:
    // Route::get('/dashboard', [DashboardWebController::class, 'index'])->name('dashboard');

    // Rute Logout
    // Menggunakan metode logout dari LoginController untuk konsistensi.
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});

// =====================================================================
// RUTE LAIN-LAIN (Opsional)
// Anda bisa menambahkan rute-rute lain untuk halaman yang dilindungi di sini.
// =====================================================================

// Contoh rute lain yang mungkin Anda butuhkan setelah login
// Route::middleware('auth')->group(function () {
//     Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
//     Route::get('/settings', [SettingsController::class, 'index'])->name('settings');
// });