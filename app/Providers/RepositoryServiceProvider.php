<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // ==========================================
        // 1. Binding Kereta (TRAIN)
        // ==========================================
        $this->app->bind(
            \App\Interfaces\TrainRepositoryInterface::class,
            \App\Repositories\TrainRepository::class
        );

        // ==========================================
        // 2. Binding User (INI YANG KURANG TADI)
        // ==========================================
        $this->app->bind(
            \App\Interfaces\UserRepositoryInterface::class,
            \App\Repositories\UserRepository::class
        );
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Biarkan kosong
    }
}

// use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Route;
// use App\Http\Controllers\Api\FlightController;
// use App\Http\Controllers\Api\AuthController;
// use App\Http\Controllers\Api\TrainController;
// use App\Http\Controllers\Api\ProfileController; // ⬅️ 1. TAMBAH INI (Import Controller)

// // ==============================
// // 1. PUBLIC ROUTES
// // ==============================

// Route::post('/register', [AuthController::class, 'register']);
// Route::post('/login', [AuthController::class, 'login']);

// // ==============================
// // 2. PRIVATE ROUTES (Sanctum)
// // ==============================
// Route::middleware(['auth:sanctum'])->group(function () {
    
//     // Auth & User
//     Route::post('/logout', [AuthController::class, 'logout']);
//     Route::get('/user', [AuthController::class, 'me']);

//     // Master Data
//     Route::apiResource('trains', TrainController::class);
//     Route::apiResource('flights', FlightController::class);

//     // Fitur Kelola Profil (Caca) ⬇️ 2. TAMBAH INI
//     Route::get('/profile', [ProfileController::class, 'show']);   // Lihat Profil
//     Route::put('/profile', [ProfileController::class, 'update']); // Update Profil
// });