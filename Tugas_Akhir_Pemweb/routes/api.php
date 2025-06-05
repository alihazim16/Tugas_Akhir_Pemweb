<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController; // Ini adalah AuthController untuk API JWT
use App\Http\Controllers\Api\ProjectController; // Import ProjectController API
// use App\Http\Controllers\Api\TaskController;    // Import TaskController (jika sudah ada/akan dibuat)
// use App\Http\Controllers\Api\CommentController; // Import CommentController (jika sudah ada/akan dibuat)
// use App\Http\Controllers\DashboardController; // Jika kamu punya API dashboard terpisah

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Auth routes (via JWT)
// Endpoint ini tidak dilindungi oleh auth:api karena mereka adalah gerbang autentikasi itu sendiri
Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']); // Register user API
    Route::post('/login', [AuthController::class, 'login']);       // Login user API (mengembalikan JWT token)
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:api'); // Logout API (membutuhkan token)
    Route::post('/refresh', [AuthController::class, 'refresh'])->middleware('auth:api'); // Refresh token API (membutuhkan token)
    Route::get('/me', [AuthController::class, 'me'])->middleware('auth:api');             // Mendapatkan info user yang sedang login (membutuhkan token)
});

// Protected API routes (requires JWT Token)
// Semua rute di dalam grup ini akan memerlukan token JWT yang valid.
Route::middleware(['auth:api'])->group(function () {
    // Project Routes (dilindungi oleh permission di dalam controller)
    // Route::apiResource akan membuat rute seperti GET /projects, POST /projects, GET /projects/{id}, PUT /projects/{id}, DELETE /projects/{id}
    Route::apiResource('projects', ProjectController::class);

    // Task Routes (akan dibuat nanti)
    // Route::apiResource('tasks', TaskController::class);

    // Comment Routes (akan dibuat nanti)
    // Route::apiResource('comments', CommentController::class);

    // Dashboard Ringkasan API (jika ada)
    // Route::get('/dashboard-api', [DashboardController::class, 'getApiSummary'])->middleware('permission:view dashboard');
});
