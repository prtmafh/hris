<?php

use App\Http\Controllers\Admin\DaftarAdminController;
use App\Http\Controllers\Admin\DaftarKaryawanController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DataAbsensiController;
use App\Http\Controllers\Admin\DataGajiController;
use App\Http\Controllers\Admin\IzinController;
use App\Http\Controllers\Admin\JabatanController;
use App\Http\Controllers\Admin\KategoriReimbursementController;
use App\Http\Controllers\Admin\LemburController;
use App\Http\Controllers\Admin\PengaturanController;
use App\Http\Controllers\Admin\ReimbursementController as AdminReimbursementController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Karyawan\AbsensiController as KaryawanAbsensiController;
use App\Http\Controllers\Karyawan\DashboardController as KaryawanDashboardController;
use App\Http\Controllers\Karyawan\IzinController as KaryawanIzinController;
use App\Http\Controllers\Karyawan\LemburController as KaryawanLemburController;
use App\Http\Controllers\Karyawan\ReimbursementController as KaryawanReimbursementController;
use App\Http\Controllers\Karyawan\SlipGajiController;
use Illuminate\Support\Facades\Route;


Route::get('/', [AuthController::class, 'loginForm'])->name('login');
Route::get('/login', [AuthController::class, 'loginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

    Route::get('/admin/daftar_admin', [DaftarAdminController::class, 'index'])->name('admin.daftar_admin');
    Route::post('/admin/daftar_admin', [DaftarAdminController::class, 'store'])->name('admin.daftar_admin.store');
    Route::put('/admin/daftar_admin/{id}', [DaftarAdminController::class, 'update'])->name('admin.daftar_admin.update');
    Route::post('/admin/daftar_admin/{id}/toggle-status', [DaftarAdminController::class, 'toggleAdminStatus'])->name('admin.daftar_admin.toggleStatus');
    Route::delete('/admin/daftar_admin/{id}', [DaftarAdminController::class, 'destroy'])->name('admin.daftar_admin.destroy');

    Route::get('/admin/jabatan', [JabatanController::class, 'index'])->name('admin.jabatan');
    Route::post('/admin/jabatan', [JabatanController::class, 'store'])->name('admin.jabatan.store');
    Route::put('/admin/jabatan/{id}', [JabatanController::class, 'update'])->name('admin.jabatan.update');
    Route::delete('/admin/jabatan/{id}', [JabatanController::class, 'destroy'])->name('admin.jabatan.destroy');

    Route::get('/admin/daftar_karyawan', [DaftarKaryawanController::class, 'index'])->name('admin.daftar_karyawan');
    Route::get('/admin/daftar_karyawan/tambah', [DaftarKaryawanController::class, 'storeView'])->name('admin.karyawan.create');
    Route::post('/admin/daftar_karyawan/store', [DaftarKaryawanController::class, 'store'])->name('admin.karyawan.store');
    Route::get('admin/daftar_karyawan/detail_karyawan/{id}', [DaftarKaryawanController::class, 'detail'])->name('admin.karyawan.show');
    Route::get('admin/daftar_karyawan/edit_karyawan/{id}', [DaftarKaryawanController::class, 'updateView'])->name('admin.karyawan.edit');
    Route::put('admin/daftar_karyawan/update/{id}', [DaftarKaryawanController::class, 'update'])->name('admin.karyawan.update');
    Route::delete('/karyawan/{id}', [DaftarKaryawanController::class, 'destroy'])->name('admin.karyawan.destroy');
    Route::post('/admin/karyawan/{id}/toggle-status', [DaftarKaryawanController::class, 'toggleStatus'])->name('admin.karyawan.toggleStatus');
    Route::post('/admin/karyawan/{id}/toggle-karyawan-status', [DaftarKaryawanController::class, 'toggleKaryawanStatus'])->name('admin.karyawan.toggleKaryawanStatus');
    Route::post('/admin/karyawan/{id}/reset-password', [DaftarKaryawanController::class, 'resetPassword'])->name('admin.karyawan.resetPassword');

    Route::get('/admin/absensi', [DataAbsensiController::class, 'index'])->name('data_absen');
    Route::get('/admin/absensi/tambah', [DataAbsensiController::class, 'create'])->name('admin.absensi.create');
    Route::post('/admin/absensi', [DataAbsensiController::class, 'store'])->name('admin.absensi.store');
    Route::get('/admin/absensi/{id}', [DataAbsensiController::class, 'show'])->name('admin.absensi.show');
    Route::get('/admin/absensi/{id}/edit', [DataAbsensiController::class, 'edit'])->name('admin.absensi.edit');
    Route::put('/admin/absensi/{id}', [DataAbsensiController::class, 'update'])->name('admin.absensi.update');
    Route::delete('/admin/absensi/{id}', [DataAbsensiController::class, 'destroy'])->name('admin.absensi.destroy');
    Route::get('/admin/rekap-tahunan', [DataAbsensiController::class, 'rekap'])->name('rekap.tahunan');

    Route::get('admin/izin', [IzinController::class, 'index'])->name('admin.izin');
    Route::post('/admin/izin/{id}/approve', [IzinController::class, 'approve'])->name('izin.approve');
    Route::post('/admin/izin/{id}/reject', [IzinController::class, 'reject'])->name('izin.reject');

    Route::get('/admin/lembur', [LemburController::class, 'index'])->name('admin.lembur');
    Route::post('/admin/lembur/{id}/approve', [LemburController::class, 'approve'])->name('admin.lembur.approve');
    Route::post('/admin/lembur/{id}/reject', [LemburController::class, 'reject'])->name('admin.lembur.reject');

    Route::get('/admin/penggajian', [DataGajiController::class, 'index'])->name('admin.penggajian');
    Route::post('/admin/penggajian/generate', [DataGajiController::class, 'generate'])->name('admin.penggajian.generate');
    Route::get('/admin/penggajian/{id}', [DataGajiController::class, 'show'])->name('admin.penggajian.show');
    Route::post('/admin/penggajian/{id}/bayar', [DataGajiController::class, 'markBayar'])->name('admin.penggajian.bayar');

    Route::get('/admin/pengaturan', [PengaturanController::class, 'index'])->name('admin.pengaturan');
    Route::put('/admin/pengaturan/{id}', [PengaturanController::class, 'update'])->name('admin.pengaturan.update');

    Route::get('/admin/jadwal-kerja', [AdminController::class, 'jadwalKerja'])->name('admin.jadwal_kerja');
    Route::get('/admin/hari-libur', [AdminController::class, 'hariLibur'])->name('admin.hari_libur');
    Route::get('/admin/kategori-reimbursement', [KategoriReimbursementController::class, 'index'])->name('admin.kategori_reimbursement');
    Route::post('/admin/kategori-reimbursement', [KategoriReimbursementController::class, 'store'])->name('admin.kategori-reimbursement.store');
    Route::put('/admin/kategori-reimbursement/{id}', [KategoriReimbursementController::class, 'update'])->name('admin.kategori-reimbursement.update');
    Route::post('/admin/kategori-reimbursement/{id}/toggle', [KategoriReimbursementController::class, 'toggle'])->name('admin.kategori-reimbursement.toggle');
    Route::delete('/admin/kategori-reimbursement/{id}', [KategoriReimbursementController::class, 'destroy'])->name('admin.kategori-reimbursement.destroy');

    Route::get('/admin/reimbursement', [AdminReimbursementController::class, 'index'])->name('admin.reimbursement');
    Route::post('/admin/reimbursement', [AdminReimbursementController::class, 'store'])->name('admin.reimbursement.store');
    Route::post('/admin/reimbursement/{id}/approve', [AdminReimbursementController::class, 'approve'])->name('admin.reimbursement.approve');
    Route::post('/admin/reimbursement/{id}/reject', [AdminReimbursementController::class, 'reject'])->name('admin.reimbursement.reject');
    Route::post('/admin/reimbursement/{id}/bayar', [AdminReimbursementController::class, 'markPaid'])->name('admin.reimbursement.bayar');
    Route::delete('/admin/reimbursement/{id}', [AdminReimbursementController::class, 'destroy'])->name('admin.reimbursement.destroy');
    Route::get('/admin/training', [AdminController::class, 'training'])->name('admin.training');
    Route::get('/admin/peserta-training', [AdminController::class, 'pesertaTraining'])->name('admin.peserta_training');
});

Route::middleware(['auth', 'karyawan'])->group(function () {
    Route::get('/dashboard', [KaryawanDashboardController::class, 'index'])->name('dashboard.karyawan');
    Route::post('/absen/masuk', [KaryawanAbsensiController::class, 'absenMasuk'])->name('absen.masuk');
    Route::post('/absen/pulang', [KaryawanAbsensiController::class, 'absenPulang'])->name('absen.pulang');

    Route::get('/karyawan/absensi-saya', [KaryawanAbsensiController::class, 'index'])->name('karyawan.absensi');

    Route::get('/karyawan/izin-saya', [KaryawanIzinController::class, 'index'])->name('karyawan.izin');
    Route::post('/karyawan/izin-saya', [KaryawanIzinController::class, 'store'])->name('karyawan.izin.store');

    Route::get('/karyawan/lembur-saya', [KaryawanLemburController::class, 'index'])->name('karyawan.lembur');
    Route::post('/karyawan/lembur-saya', [KaryawanLemburController::class, 'store'])->name('karyawan.lembur.store');

    Route::get('/karyawan/reimbursement-saya', [KaryawanReimbursementController::class, 'index'])->name('karyawan.reimbursement');
    Route::post('/karyawan/reimbursement-saya', [KaryawanReimbursementController::class, 'store'])->name('karyawan.reimbursement.store');
    Route::delete('/karyawan/reimbursement-saya/{id}', [KaryawanReimbursementController::class, 'destroy'])->name('karyawan.reimbursement.destroy');

    Route::get('/karyawan/slip-gaji', [SlipGajiController::class, 'index'])->name('karyawan.slip_gaji');
    Route::get('/karyawan/slip-gaji/{id}', [SlipGajiController::class, 'showSlip'])->name('karyawan.slip_gaji.show');
    Route::get('/karyawan/slip-gaji/{id}/pdf', [SlipGajiController::class, 'downloadSlipPdf'])->name('karyawan.slip_gaji.pdf');
});
