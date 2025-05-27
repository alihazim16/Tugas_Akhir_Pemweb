<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;

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

// Public route
Route::get('/welcome', function () {
    return response()->json(['message' => 'Welcome to the API']);
});

// Protected route - only for authenticated users with role "admin"
Route::middleware(['auth:api', 'role:admin'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index']);
});
