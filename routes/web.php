<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DataBarangController;
use App\Http\Controllers\PembelianController;
use App\Http\Controllers\BarangMutasiController;
use App\Http\Controllers\BarangMusnahController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\PengembalianController;
use App\Http\Controllers\DetailPeminjamanController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
    ->name('logout');

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware('auth')
    ->name('dashboard');

Auth::routes(['register' => false]);
Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::resource('/databarang', DataBarangController::class);
Route::resource('/pembelian', PembelianController::class);
Route::resource('/barangmutasi', BarangMutasiController::class);
Route::resource('/barangmusnah', BarangMusnahController::class);
Route::resource('/peminjaman', PeminjamanController::class);
Route::resource('/pengembalian', PengembalianController::class);
Route::post('/peminjaman', [PeminjamanController::class, 'store'])->name('peminjaman.store');
Route::resource('/detailpeminjaman', DetailPeminjamanController::class);


