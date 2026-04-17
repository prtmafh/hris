<?php

use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DaftarKaryawanController;
use App\Http\Controllers\JabatanController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\PengajuanController;
use App\Http\Controllers\PenggajianController;
use Illuminate\Support\Facades\Route;


Route::get('/', [AuthController::class, 'loginForm'])->name('login');
Route::get('/login', [AuthController::class, 'loginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');

    Route::get('/admin/daftar_admin', [AdminController::class, 'daftarAdmin'])->name('admin.daftar_admin');
    Route::post('/admin/daftar_admin', [AdminController::class, 'storeAdmin'])->name('admin.daftar_admin.store');
    Route::put('/admin/daftar_admin/{id}', [AdminController::class, 'updateAdmin'])->name('admin.daftar_admin.update');
    Route::post('/admin/daftar_admin/{id}/toggle-status', [AdminController::class, 'toggleAdminStatus'])->name('admin.daftar_admin.toggleStatus');
    Route::delete('/admin/daftar_admin/{id}', [AdminController::class, 'destroyAdmin'])->name('admin.daftar_admin.destroy');

    Route::get('/admin/jabatan', [JabatanController::class, 'index'])->name('admin.jabatan');
    Route::post('/admin/jabatan', [JabatanController::class, 'storeJabatan'])->name('admin.jabatan.store');
    Route::put('/admin/jabatan/{id}', [JabatanController::class, 'updateJabatan'])->name('admin.jabatan.update');
    Route::delete('/admin/jabatan/{id}', [JabatanController::class, 'destroyJabatan'])->name('admin.jabatan.destroy');

    Route::get('/admin/daftar_karyawan', [DaftarKaryawanController::class, 'index'])->name('admin.daftar_karyawan');
    Route::get('/admin/daftar_karyawan/tambah', [DaftarKaryawanController::class, 'tambah'])->name('admin.karyawan.create');
    Route::post('/admin/daftar_karyawan/store', [DaftarKaryawanController::class, 'store'])->name('admin.karyawan.store');
    Route::get('admin/daftar_karyawan/detail_karyawan/{id}', [DaftarKaryawanController::class, 'detail'])->name('admin.karyawan.show');
    Route::get('admin/daftar_karyawan/edit_karyawan/{id}', [DaftarKaryawanController::class, 'edit'])->name('admin.karyawan.edit');
    Route::put('admin/daftar_karyawan/update/{id}', [DaftarKaryawanController::class, 'update'])->name('admin.karyawan.update');
    Route::delete('/karyawan/{id}', [DaftarKaryawanController::class, 'destroy'])->name('admin.karyawan.destroy');
    Route::post('/admin/karyawan/{id}/toggle-status', [DaftarKaryawanController::class, 'toggleStatus'])->name('admin.karyawan.toggleStatus');
    Route::post('/admin/karyawan/{id}/toggle-karyawan-status', [DaftarKaryawanController::class, 'toggleKaryawanStatus'])->name('admin.karyawan.toggleKaryawanStatus');
    Route::post('/admin/karyawan/{id}/reset-password', [DaftarKaryawanController::class, 'resetPassword'])->name('admin.karyawan.resetPassword');

    Route::get('/admin/absensi', [AbsensiController::class, 'index'])->name('data_absen');
    Route::get('/admin/absensi/tambah', [AbsensiController::class, 'create'])->name('admin.absensi.create');
    Route::post('/admin/absensi', [AbsensiController::class, 'store'])->name('admin.absensi.store');
    Route::get('/admin/absensi/{id}', [AbsensiController::class, 'show'])->name('admin.absensi.show');
    Route::get('/admin/absensi/{id}/edit', [AbsensiController::class, 'edit'])->name('admin.absensi.edit');
    Route::put('/admin/absensi/{id}', [AbsensiController::class, 'update'])->name('admin.absensi.update');
    Route::delete('/admin/absensi/{id}', [AbsensiController::class, 'destroy'])->name('admin.absensi.destroy');
    Route::get('/admin/rekap-tahunan', [AbsensiController::class, 'rekap'])->name('rekap.tahunan');

    Route::get('admin/izin', [PengajuanController::class, 'izin'])->name('admin.izin');
    Route::post('/admin/izin/{id}/approve', [PengajuanController::class, 'approveIzin'])->name('izin.approve');
    Route::post('/admin/izin/{id}/reject', [PengajuanController::class, 'rejectIzin'])->name('izin.reject');

    Route::get('/admin/lembur', [PengajuanController::class, 'lembur'])->name('admin.lembur');
    Route::post('/admin/lembur/{id}/approve', [PengajuanController::class, 'approveLembur'])->name('admin.lembur.approve');
    Route::post('/admin/lembur/{id}/reject', [PengajuanController::class, 'rejectLembur'])->name('admin.lembur.reject');

    Route::get('/admin/penggajian', [PenggajianController::class, 'data_gaji'])->name('admin.penggajian');
    Route::get('/admin/pengaturan', [AdminController::class, 'pengaturan'])->name('admin.pengaturan');
    Route::get('/admin/jadwal-kerja', [AdminController::class, 'jadwalKerja'])->name('admin.jadwal_kerja');
    Route::get('/admin/hari-libur', [AdminController::class, 'hariLibur'])->name('admin.hari_libur');
    Route::get('/admin/kategori-reimbursement', [AdminController::class, 'kategoriReimbursement'])->name('admin.kategori_reimbursement');
    Route::get('/admin/reimbursement', [AdminController::class, 'reimbursement'])->name('admin.reimbursement');
    Route::get('/admin/training', [AdminController::class, 'training'])->name('admin.training');
    Route::get('/admin/peserta-training', [AdminController::class, 'pesertaTraining'])->name('admin.peserta_training');
});

Route::middleware(['auth', 'karyawan'])->group(function () {
    Route::get('/dashboard', [KaryawanController::class, 'index'])->name('dashboard.karyawan');
    Route::post('/absen/masuk', [KaryawanController::class, 'absenMasuk'])->name('absen.masuk');
    Route::post('/absen/pulang', [KaryawanController::class, 'absenPulang'])->name('absen.pulang');

    Route::get('/karyawan/absensi-saya', [KaryawanController::class, 'absensiSaya'])->name('karyawan.absensi');
    Route::get('/karyawan/izin-saya', [KaryawanController::class, 'izinSaya'])->name('karyawan.izin');
    Route::post('/karyawan/izin-saya', [KaryawanController::class, 'storeIzin'])->name('karyawan.izin.store');
    Route::get('/karyawan/lembur-saya', [KaryawanController::class, 'lemburSaya'])->name('karyawan.lembur');
    Route::post('/karyawan/lembur-saya', [KaryawanController::class, 'storeLembur'])->name('karyawan.lembur.store');
    Route::get('/karyawan/slip-gaji', [KaryawanController::class, 'slipGaji'])->name('karyawan.slip_gaji');
});
