<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\CekRole;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\TarifController;
use App\Http\Controllers\Admin\AreaController;
use App\Http\Controllers\Admin\KendaraanController;
use App\Http\Controllers\Admin\TransaksiController;
use App\Http\Controllers\Admin\ActivityLogController;
use App\Http\Controllers\Admin\RekapController;

// 1. Login & Keluar
Route::get('/', [LoginController::class, 'index'])->name('login');
Route::post('/login', [LoginController::class, 'loginProses'])->name('login.proses');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// 2. Semua halaman di bawah ini wajib login
Route::middleware(['auth'])->group(function () {

    // Dashboard: tiap role hanya bisa membuka dashboard miliknya
    Route::get('/dashboard/admin', [DashboardController::class, 'index'])->middleware(CekRole::class.':admin');
    Route::get('/dashboard/petugas', [DashboardController::class, 'index'])->middleware(CekRole::class.':petugas');
    Route::get('/dashboard/owner', [DashboardController::class, 'index'])->middleware(CekRole::class.':owner');

    // ADMIN: CRUD user, area parkir, kendaraan, tarif + log aktivitas
    Route::middleware(CekRole::class.':admin')->group(function () {
        Route::get('/dashboard/admin/user', [UserController::class, 'index'])->name('user.index');
        Route::post('/dashboard/admin/user', [UserController::class, 'store'])->name('user.store');
        Route::put('/dashboard/admin/user/{id}', [UserController::class, 'update'])->name('user.update');
        Route::delete('/dashboard/admin/user/{id}', [UserController::class, 'destroy'])->name('user.destroy');

        Route::get('/dashboard/admin/area', [AreaController::class, 'index'])->name('area.index');
        Route::post('/dashboard/admin/area', [AreaController::class, 'store'])->name('area.store');
        Route::put('/dashboard/admin/area/{id}', [AreaController::class, 'update'])->name('area.update');
        Route::delete('/dashboard/admin/area/{id}', [AreaController::class, 'destroy'])->name('area.destroy');

        Route::get('/dashboard/admin/kendaraan', [KendaraanController::class, 'index'])->name('kendaraan.index');
        Route::post('/dashboard/admin/kendaraan', [KendaraanController::class, 'store'])->name('kendaraan.store');
        Route::put('/dashboard/admin/kendaraan/{id}', [KendaraanController::class, 'update'])->name('kendaraan.update');
        Route::delete('/dashboard/admin/kendaraan/{id}', [KendaraanController::class, 'destroy'])->name('kendaraan.destroy');

        Route::get('/dashboard/admin/tarif', [TarifController::class, 'index'])->name('tarif.index');
        Route::post('/dashboard/admin/tarif', [TarifController::class, 'store'])->name('tarif.store');
        Route::put('/dashboard/admin/tarif/{id}', [TarifController::class, 'update'])->name('tarif.update');
        Route::delete('/dashboard/admin/tarif/{id}', [TarifController::class, 'destroy'])->name('tarif.destroy');

        Route::get('/log-aktivitas', [ActivityLogController::class, 'index'])->name('log.index');
    });

    // ADMIN + PETUGAS: transaksi parkir harian
    Route::middleware(CekRole::class.':admin,petugas')->group(function () {
        Route::get('/dashboard/admin/transaksi', [TransaksiController::class, 'index'])->name('transaksi.index');
        Route::post('/dashboard/admin/transaksi', [TransaksiController::class, 'store'])->name('transaksi.store');
        Route::post('/dashboard/admin/transaksi/checkout/{id}', [TransaksiController::class, 'checkout'])->name('transaksi.checkout');
        Route::get('/transaksi/{id}/struk', [TransaksiController::class, 'struk'])->name('transaksi.struk');
    });

    // ADMIN + OWNER: rekap transaksi
    Route::middleware(CekRole::class.':admin,owner')->group(function () {
        Route::get('/rekap-transaksi', [RekapController::class, 'index'])->name('rekap.index');
    });
});