<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log; // Untuk logging
use Illuminate\Validation\ValidationException; // Untuk validasi
use App\Models\User; // Import model User
use Tymon\JWTAuth\Facades\JWTAuth; // Import Facade JWTAuth
use Tymon\JWTAuth\Exceptions\JWTException; // Import exception JWT

class AuthController extends Controller
{
    /**
     * Membuat instance AuthController.
     * Middleware 'auth:api' diterapkan ke semua metode kecuali 'login' dan 'register'.
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    /**
     * Mendaftarkan user baru (API).
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        // Validasi data input
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        // Membuat user baru
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Opsional: Tetapkan role default (misal 'user') jika menggunakan Spatie Permission
        // Pastikan model User menggunakan HasRoles trait dan role 'user' sudah ada di database
        // $user->assignRole('user');

        // Membuat token JWT untuk user yang baru terdaftar
        try {
            $token = JWTAuth::fromUser($user);
        } catch (JWTException $e) {
            Log::error('Gagal membuat token JWT saat registrasi: ' . $e->getMessage());
            return response()->json(['message' => 'User berhasil terdaftar, tapi gagal membuat token.'], 500);
        }

        Log::info('User baru berhasil terdaftar: ' . $user->email);

        return response()->json([
            'message' => 'User berhasil terdaftar.',
            'user' => $user,
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60
        ], 201); // 201 Created
    }

    /**
     * Mendapatkan token JWT melalui kredensial (API).
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        // Validasi data input
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        $credentials = $request->only('email', 'password');

        // Mencoba login menggunakan kredensial
        // Guard 'api' akan digunakan karena sudah dikonfigurasi di config/auth.php
        if (!$token = auth('api')->attempt($credentials)) {
            Log::warning('Login API gagal untuk email: ' . $credentials['email'] . ' - Kredensial tidak valid.');
            return response()->json(['error' => 'Kredensial tidak valid'], 401); // 401 Unauthorized
        }

        Log::info('Login API berhasil untuk user: ' . $credentials['email']);

        // Mengembalikan token JWT jika login berhasil
        return $this->respondWithToken($token);
    }

    /**
     * Mengeluarkan user (API) dan membatalkan token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth('api')->logout(); // Membatalkan token JWT saat ini
        Log::info('User berhasil logout dari API.');
        return response()->json(['message' => 'Berhasil logout']);
    }

    /**
     * Memperbarui token JWT (API).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        // Mengembalikan token baru setelah token lama diperbarui
        Log::info('Token JWT berhasil diperbarui.');
        return $this->respondWithToken(auth('api')->refresh());
    }

    /**
     * Mendapatkan detail user yang sedang terautentikasi (API).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        // Mengembalikan detail user yang sedang login
        return response()->json(auth('api')->user());
    }

    /**
     * Struktur respons token JWT.
     *
     * @param  string  $token
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60 // Masa berlaku token dalam detik
        ]);
    }
}
