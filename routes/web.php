<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\JabatanController;
use Illuminate\Support\Facades\Route;


Route::get('/', [AuthController::class, 'loginForm'])->name('login');
Route::get('/login', [AuthController::class, 'loginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');

    Route::get('/admin/jabatan', [JabatanController::class, 'index'])->name('admin.jabatan');
    Route::post('/admin/jabatan', [JabatanController::class, 'storeJabatan'])->name('admin.jabatan.store');
    Route::put('/admin/jabatan/{id}', [JabatanController::class, 'updateJabatan'])->name('admin.jabatan.update');
    Route::delete('/admin/jabatan/{id}', [JabatanController::class, 'destroyJabatan'])->name('admin.jabatan.destroy');

    Route::get('/admin/daftar_karyawan', [AdminController::class, 'daftarKaryawan'])->name('admin.daftar_karyawan');
    Route::post('/admin/daftar_karyawan/store', [AdminController::class, 'storeDaftarKaryawan'])->name('admin.karyawan.store');
    Route::put('/karyawan/{id}', [AdminController::class, 'updateDaftarKaryawan'])->name('admin.karyawan.update');
    Route::delete('/karyawan/{id}', [AdminController::class, 'destroyDaftarKaryawan'])->name('admin.karyawan.destroy');
});
// Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('dashboard');
