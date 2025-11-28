<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\FlightController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\TrainController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\FlightSeatController;
use App\Http\Controllers\Api\BookingController;
use App\Http\Controllers\Api\WagonController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\TicketController;
use App\Http\Controllers\Api\SearchController;

// ==============================
// 1. PUBLIC ROUTES
// ==============================

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// ==============================
// 2. PRIVATE ROUTES (Sanctum)
// ==============================
Route::middleware(['auth:sanctum'])->group(function () {

    // Auth & User
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'me']);

    // Master Data
    Route::apiResource('trains', TrainController::class);
    Route::apiResource('flights', FlightController::class);

    // Fitur Kelola Profil (Caca) ⬇️ 2. TAMBAH INI
    Route::get('/profile', [ProfileController::class, 'show']);   // Lihat Profil
    Route::put('/profile', [ProfileController::class, 'update']); // Update Profil

    Route::prefix('flights/{id}')->group(function () {
        Route::get('/seats', [FlightSeatController::class, 'index']);   // Lihat Layout Kursi
        Route::post('/seats', [FlightSeatController::class, 'store']);  // Generate Kursi Otomatis
        Route::delete('/seats', [FlightSeatController::class, 'destroy']); // Reset/Hapus Semua Kursi
    });

    Route::post('/booking', [BookingController::class, 'store']);
    Route::post('/wagons', [WagonController::class, 'store']);

    // 1. Payment
    Route::post('/payment/pay', [PaymentController::class, 'pay']);

    // 2. E-Ticket Detail
    Route::get('/ticket/{booking_code}', [TicketController::class, 'show']);
    Route::post('/search', [SearchController::class, 'index']);
});
