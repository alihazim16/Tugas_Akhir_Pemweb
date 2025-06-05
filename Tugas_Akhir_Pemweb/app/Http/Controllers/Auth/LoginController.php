<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class LoginController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        $credentials = $request->only('email', 'password');

        Log::info('Percobaan login untuk email: ' . $credentials['email']);

        $user = User::where('email', $credentials['email'])->first();

        if ($user && Hash::check($credentials['password'], $user->password)) {
            Auth::login($user, $request->boolean('remember'));
            $request->session()->regenerate();

            $jwtToken = null; // Inisialisasi token menjadi null
            try {
                $jwtToken = JWTAuth::fromUser($user);
                Log::info('JWT Token berhasil dibuat untuk user: ' . $user->email . ' (dari LoginController).');
            } catch (JWTException $e) {
                Log::error('Gagal membuat JWT Token (JWTException) dari LoginController untuk user ' . $user->email . ': ' . $e->getMessage());
            } catch (\Exception $e) {
                Log::error('Gagal membuat JWT Token (Exception umum) dari LoginController untuk user ' . $user->email . ': ' . $e->getMessage());
            }

            Log::info('Login SESI BERHASIL untuk user: ' . $user->email . '. Mengarahkan ke halaman penerima token.');

            // --- PERUBAHAN UTAMA DI SINI ---
            // Redirect ke halaman perantara yang akan menyimpan token ke localStorage
            return redirect()->route('token.receiver', ['token' => $jwtToken]);

        } else {
            Log::warning('Login GAGAL untuk email: ' . $credentials['email'] . ' - Kredensial tidak valid.');
            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login')->with('status', 'Anda telah berhasil logout.');
    }
}