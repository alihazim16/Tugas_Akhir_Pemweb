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

    // Tampilkan form login (Blade)
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Login untuk Blade (form HTML)
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        $credentials = $request->only('email', 'password');

        // Jika request dari API (AJAX/SPA), balas JSON
        if ($request->expectsJson() || $request->is('api/*')) {
            try {
                if (!$token = JWTAuth::attempt($credentials)) {
                    return response()->json(['error' => 'Unauthorized'], 401);
                }
            } catch (JWTException $e) {
                return response()->json(['error' => 'Could not create token'], 500);
            }
            $user = Auth::user();
            return response()->json([
                'token' => $token,
                'user' => $user,
            ]);
        }

        // Jika request dari form Blade
        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended('/');
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->withInput($request->only('email'));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Jika logout dari API
        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json(['message' => 'Anda telah berhasil logout.']);
        }

        // Jika logout dari web
        return redirect('/login')->with('status', 'Anda telah berhasil logout.');
    }
}