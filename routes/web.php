<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\KosController;
use App\Http\Controllers\PesananController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\KosController as AdminKosController;
use App\Http\Controllers\Admin\PesananController as AdminPesananController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Halaman utama -> redirect ke daftar kos
Route::get('/', function () {
    return redirect()->route('kos.index');
});

// ==========================================
// 1. ROUTE AUTENTIKASI (LOGIN & REGISTER)
// ==========================================
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// ==========================================
// 2. ROUTE PENGGUNA (HANYA MELIHAT KOS)
// ==========================================
Route::get('/kos', [KosController::class, 'index'])->name('kos.index');
Route::get('/kos/{id}', [KosController::class, 'show'])->name('kos.show');

// ==========================================
// 3. ROUTE PENGGUNA (MEMESAN KOS)
// ==========================================
Route::middleware('auth')->group(function () {
    // Riwayat pesanan pengguna
    Route::get('/pesanan', [PesananController::class, 'index'])->name('pesanan.index');
    // Form pemesanan kos
    Route::get('/pesan/{kos_id}', [PesananController::class, 'create'])->name('pesanan.create');
    Route::post('/pesan', [PesananController::class, 'store'])->name('pesanan.store');
});

// ==========================================
// 4. ROUTE KHUSUS ADMIN (MENGEDIT / KELOLA WEB)
// ==========================================
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    // Dashboard Admin
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Kelola Data Kos (Tambah, Edit, Hapus, Detail)
    Route::resource('kos', AdminKosController::class);

    // Kelola Pesanan Pelanggan (Lihat, Konfirmasi / Tolak, Hapus)
    Route::get('/pesanan', [AdminPesananController::class, 'index'])->name('pesanan.index');
    Route::patch('/pesanan/{id}/status', [AdminPesananController::class, 'updateStatus'])->name('pesanan.updateStatus');
    Route::delete('/pesanan/{id}', [AdminPesananController::class, 'destroy'])->name('pesanan.destroy');
});
