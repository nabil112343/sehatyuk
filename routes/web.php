<?php

use App\Http\Controllers\Admin\DoctorController;
use App\Http\Controllers\Admin\PoliController;
use App\Http\Controllers\Admin\QueueController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\TicketController;
use App\Http\Middleware\RoleMiddleware;
use Illuminate\Support\Facades\Route;

// ---------- Auth (satu halaman untuk pasien, admin & petugas) ----------
// ---------- Landing publik (tanpa login) ----------
Route::get('/', [LandingController::class, 'index']);
Route::get('/artikel', [LandingController::class, 'archive']);
Route::get('/artikel/{article:slug}', [LandingController::class, 'show']);

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::get('/auth/google', [AuthController::class, 'googleRedirect']);
Route::get('/auth/google/callback', [AuthController::class, 'googleCallback']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth');

// Data layar TV (publik, supaya bisa ditampilkan di TV ruang tunggu)
Route::get('/api/tv', [TicketController::class, 'tv']);

// ---------- Data katalog (semua user yang login) ----------
Route::middleware('auth')->group(function () {
    Route::get('/api/poli', [CatalogController::class, 'polis']);
    Route::get('/api/dokter', [CatalogController::class, 'doctors']);
});

// ---------- Area PASIEN ----------
Route::middleware(['auth', RoleMiddleware::class . ':pasien'])->group(function () {
    Route::view('/katalog', 'user.katalog');
    Route::view('/ambil-tiket', 'user.ambil-tiket');
    Route::view('/tiket-saya', 'user.tiket-saya');
    Route::view('/layar-tv', 'user.layar-tv');

    Route::post('/api/tiket', [TicketController::class, 'store']);
    Route::get('/api/tiket-saya', [TicketController::class, 'mine']);
});

// ---------- Area ADMIN & PETUGAS ----------
Route::prefix('admin')->middleware(['auth', RoleMiddleware::class . ':admin,petugas'])->group(function () {
    Route::view('/layar-tv', 'admin.layar-tv');
    Route::view('/antrean', 'admin.antrean');
    Route::view('/dokter', 'admin.dokter');
    Route::view('/poli', 'admin.poli');

    Route::get('/api/antrean', [QueueController::class, 'index']);
    Route::post('/api/antrean/next', [QueueController::class, 'next']);
    Route::post('/api/antrean/{ticket}/{action}', [QueueController::class, 'action']);

    Route::post('/api/dokter', [DoctorController::class, 'store']);
    Route::put('/api/dokter/{doctor}', [DoctorController::class, 'update']);
    Route::delete('/api/dokter/{doctor}', [DoctorController::class, 'destroy']);

    Route::post('/api/poli', [PoliController::class, 'store']);
    Route::put('/api/poli/{poli}', [PoliController::class, 'update']);
    Route::delete('/api/poli/{poli}', [PoliController::class, 'destroy']);
});
