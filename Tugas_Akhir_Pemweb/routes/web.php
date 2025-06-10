    <?php

    use Illuminate\Support\Facades\Route;
    use Illuminate\Support\Facades\Auth;

    use App\Http\Controllers\Auth\LoginController;
    use App\Http\Controllers\Auth\RegisterController;
    use App\Http\Controllers\DashboardController;

    // Rute default '/' akan mengarahkan ke halaman login jika belum login
    Route::get('/', function () {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return redirect()->route('login');
    });

    // RUTE AUTENTIKASI (LOGIN & REGISTER)
    // Middleware 'guest' mencegah user yang sudah login mengakses halaman ini.
    Route::middleware('guest')->group(function () {
        Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
        Route::post('/register', [RegisterController::class, 'register']);

        Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login'); // <-- PASTIKAN INI
        Route::post('/login', [LoginController::class, 'login']);
    });

    // Rute perantara untuk menerima dan menyimpan token JWT
    Route::get('/token-receiver/{token?}', function ($token = null) {
        return view('token_receiver', ['token' => $token]);
    })->name('token.receiver');

    // RUTE YANG DILINDUNGI (Membutuhkan Login)
    Route::middleware('auth')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    });
    