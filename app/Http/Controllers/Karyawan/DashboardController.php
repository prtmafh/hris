<?php

namespace App\Http\Controllers\Karyawan;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\Izin;
use App\Models\Lembur;
use App\Models\Pengaturan;
use App\Models\Penggajian;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        /** @var User $user */
        $user = Auth::user();
        $karyawan = $user->karyawan()->with('jabatan')->firstOrFail();

        $today        = Carbon::today();
        $currentMonth = $today->month;
        $currentYear  = $today->year;

        $cek = Absensi::where('karyawan_id', $karyawan->id)
            ->whereDate('tanggal', $today)
            ->first();

        $totalHadirBulanIni = Absensi::where('karyawan_id', $karyawan->id)
            ->whereMonth('tanggal', $currentMonth)
            ->whereYear('tanggal', $currentYear)
            ->whereIn('status', ['hadir', 'terlambat'])
            ->count();

        $totalIzinPending = Izin::where('karyawan_id', $karyawan->id)
            ->where('status_approval', 'pending')
            ->count();

        $totalLemburBulanIni = Lembur::where('karyawan_id', $karyawan->id)
            ->whereMonth('tanggal', $currentMonth)
            ->whereYear('tanggal', $currentYear)
            ->where('status', 'disetujui')
            ->sum('total_jam');

        $penggajianTerbaru = Penggajian::where('karyawan_id', $karyawan->id)
            ->orderByDesc('periode_tahun')
            ->orderByDesc('periode_bulan')
            ->first();

        $riwayatAbsensi = Absensi::where('karyawan_id', $karyawan->id)
            ->latest('tanggal')
            ->take(5)
            ->get();

        $namaKaryawan = $karyawan->nama;
        $shift_start  = Pengaturan::getValue('jam_masuk', '08:00');
        $shift_end    = Pengaturan::getValue('jam_pulang', '17:00');

        return view('karyawan.dashboard', compact(
            'karyawan',
            'cek',
            'totalHadirBulanIni',
            'totalIzinPending',
            'totalLemburBulanIni',
            'penggajianTerbaru',
            'riwayatAbsensi',
            'namaKaryawan',
            'shift_start',
            'shift_end'
        ));
    }
}
