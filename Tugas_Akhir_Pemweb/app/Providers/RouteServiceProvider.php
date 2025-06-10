<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * Jalur ke rute "home" aplikasi Anda.
     *
     * Biasanya, pengguna akan dialihkan ke sini setelah otentikasi.
     * Kita atur ke '/dashboard' karena di sana aplikasi React akan dimuat.
     *
     * @var string
     */
    public const HOME = '/dashboard'; // <-- PASTIKAN INI ADALAH '/dashboard'

    /**
     * Daftarkan layanan rute aplikasi.
     */
    public function boot(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        $this->routes(function () {
            // Rute API:
            // Dimuat dengan awalan 'api' dan middleware 'api'.
            // Digunakan untuk interaksi antara frontend React dan backend Laravel.
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            // Rute Web:
            // Dimuat dengan middleware 'web'.
            // Digunakan untuk halaman Blade (login, register, dashboard jika sebagian Blade).
            Route::middleware('web')
                ->group(base_path('routes/web.php'));
        });
    }
}
