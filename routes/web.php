<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\PemeliharaanController;
use App\Http\Controllers\PenggunaanBhpController;
use App\Http\Controllers\StockOpnameController;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Super Admin & Petugas Sarpras
    Route::middleware(['role:Super Admin|Petugas Sarpras'])->group(function () {
        Route::get('kategori/trash', [KategoriController::class, 'trash'])->name('kategori.trash');
        Route::post('kategori/restore/{id}', [KategoriController::class, 'restore'])->name('kategori.restore');
        Route::delete('kategori/force-delete/{id}', [KategoriController::class, 'forceDelete'])->name('kategori.force-delete');
        Route::resource('kategori', KategoriController::class);

        Route::get('barang/export', [BarangController::class, 'export'])->name('barang.export');
        Route::resource('barang', BarangController::class);
        Route::resource('pemeliharaan', PemeliharaanController::class);
        Route::resource('penggunaan-bhp', PenggunaanBhpController::class);

        Route::get('stock-opname/scan/{id}', [StockOpnameController::class, 'scan'])->name('stock-opname.scan');
        Route::post('stock-opname/update-scan/{id}', [StockOpnameController::class, 'updateScan'])->name('stock-opname.update-scan');
        Route::post('stock-opname/finalize/{id}', [StockOpnameController::class, 'finalize'])->name('stock-opname.finalize');
        Route::resource('stock-opname', StockOpnameController::class);

        Route::post('peminjaman/kembalikan/{id}', [PeminjamanController::class, 'kembalikan'])->name('peminjaman.kembalikan');
    });

    // All Auth Users
    Route::resource('peminjaman', PeminjamanController::class)->only(['index', 'create', 'store', 'destroy']);
});
