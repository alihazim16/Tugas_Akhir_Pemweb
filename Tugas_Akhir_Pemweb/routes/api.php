<?php


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\RegisterController;
use App\Http\Controllers\Api\UserController;
// use App\Http\Controllers\Api\ProjectController;

// Update the import path if ProjectController is in a different namespace, for example:
use App\Http\Controllers\ProjectController;
// Or, if it is in another folder, adjust accordingly:
// use App\Http\Controllers\AnotherFolder\ProjectController;

// Endpoint register user (tanpa login)
Route::post('register', [RegisterController::class, 'register']);

// Contoh: endpoint login (jika sudah ada AuthController)
// Route::post('login', [AuthController::class, 'login']);

// Endpoint CRUD user & project (hanya bisa diakses setelah login)
Route::middleware('auth:api')->group(function () {
    Route::apiResource('users', UserController::class);
    Route::apiResource('projects', ProjectController::class);
    // Tambahkan route lain di sini jika perlu
});