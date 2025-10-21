<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\LokasiController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\PemeliharaanController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
   ->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
   
    Route::resource('user', UserController::class);
    Route::resource('kategori', KategoriController::class);
    Route::resource('lokasi', LokasiController::class);
    
    // Route Barang - laporan harus di atas resource
    Route::get('/barang/laporan', [BarangController::class, 'cetakLaporan'])->name('barang.laporan');
    Route::resource('barang', BarangController::class);
    
    // Route Peminjaman - ROUTE KHUSUS HARUS DI ATAS RESOURCE!
    Route::get('/peminjaman/laporan', [PeminjamanController::class, 'laporan'])->name('peminjaman.laporan');
    Route::get('peminjaman/{id}/kembalikan', [PeminjamanController::class, 'kembalikan'])->name('peminjaman.kembalikan');
    Route::post('peminjaman/{id}/update-kembali', [PeminjamanController::class, 'updateKembali'])->name('peminjaman.update-kembali');
    Route::get('api/barang/{id}/info', [PeminjamanController::class, 'getBarangInfo'])->name('barang.info');
    Route::resource('peminjaman', PeminjamanController::class);
    
    // Route Pemeliharaan - LAPORAN HARUS DI ATAS RESOURCE!
    Route::get('/pemeliharaan/laporan', [PemeliharaanController::class, 'cetakLaporan'])
        ->name('pemeliharaan.laporan');
    
    // Route khusus untuk ajukan pemeliharaan dari halaman barang
    Route::post('barang/{barang}/ajukan-pemeliharaan', [PemeliharaanController::class, 'ajukanPemeliharaan'])
        ->name('barang.ajukan-pemeliharaan');
    
    // CRUD Pemeliharaan - HARUS DI BAWAH ROUTE KHUSUS
    Route::resource('pemeliharaan', PemeliharaanController::class);
});

require __DIR__.'/auth.php';