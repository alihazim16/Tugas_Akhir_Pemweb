<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log; // Pastikan ini ada
use Illuminate\Validation\ValidationException; // Pastikan ini ada

class RegisterController extends Controller
{
    /**
     * Konstruktor: Middleware 'guest' agar user yang sudah login tidak bisa mengakses halaman ini.
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Tampilkan halaman form register.
     *
     * @return \Illuminate\View\View
     */
    public function showRegistrationForm()
    {
        return view('auth.register'); // Pastikan view ini ada di resources/views/auth/register.blade.php
    }

    /**
     * Proses registrasi user baru.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function register(Request $request)
    {
        try {
            // Validasi input
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|string|min:6|confirmed',
            ]);

            // Buat user baru
            $user = User::create([
                'name'     => $request->name,
                'email'    => $request->email,
                'password' => Hash::make($request->password),
            ]);

            // Opsional: Tetapkan role default jika menggunakan Spatie
            // Pastikan model User menggunakan HasRoles trait dan role 'user' sudah ada di database
            // $user->assignRole('user'); // Uncomment jika ingin auto-assign role 'user'

            // Log untuk debugging
            Log::info('Registrasi berhasil untuk user: ' . $user->email . '. Mencoba login otomatis.');

            // Login otomatis setelah register
            Auth::login($user);

            // Cek apakah user berhasil login setelah Auth::login()
            if (Auth::check()) {
                Log::info('Login otomatis BERHASIL untuk user: ' . Auth::user()->email);
                return redirect()->intended('/dashboard')->with('status', 'Registrasi berhasil dan Anda telah login!');
            } else {
                // Ini seharusnya TIDAK terjadi jika Auth::login($user) berhasil
                Log::error('Login otomatis GAGAL setelah registrasi untuk user: ' . $user->email);
                return redirect('/login')->with('error', 'Registrasi berhasil, tapi gagal login otomatis. Silakan login secara manual.');
            }
        } catch (ValidationException $e) {
            Log::warning('Validasi registrasi gagal: ' . json_encode($e->errors()));
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error('Terjadi error tak terduga saat registrasi: ' . $e->getMessage() . ' pada file ' . $e->getFile() . ' baris ' . $e->getLine());
            return redirect()->back()->with('error', 'Terjadi error tak terduga. Silakan coba lagi.');
        }
    }
}
