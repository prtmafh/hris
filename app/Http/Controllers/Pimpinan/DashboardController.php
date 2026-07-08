<?php

namespace App\Http\Controllers\Pimpinan;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\Izin;
use App\Models\Karyawan;
use App\Models\Lembur;
use App\Models\PenilaianKaryawan;
use App\Models\Penggajian;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $now   = Carbon::now();
        $bulan = $now->month;
        $tahun = $now->year;
        $today = Carbon::today();

        $absensiHariIni = Absensi::with(['karyawan.jabatan'])
            ->whereDate('tanggal', $today)
            ->orderByRaw("CASE
                WHEN status = 'terlambat' THEN 1
                WHEN status = 'hadir' THEN 2
                WHEN status = 'izin' THEN 3
                WHEN status = 'alpha' THEN 4
                ELSE 5
            END")
            ->orderBy('jam_masuk')
            ->get();

        $totalKaryawan  = Karyawan::where('status', 'aktif')->count();
        $hadirHariIni   = $absensiHariIni->whereIn('status', ['hadir', 'terlambat'])->count();
        $terlambat      = $absensiHariIni->where('status', 'terlambat')->count();
        $tidakHadir     = $absensiHariIni->where('status', 'alpha')->count();

        $izinPending    = Izin::where('status_approval', 'pending')->count();
        $lemburPending  = Lembur::where('status', 'pending')->count();

        $totalGajiBulanIni = Penggajian::where('periode_bulan', $bulan)
            ->where('periode_tahun', $tahun)
            ->sum('total_gaji');

        $penilaianBulanIni = PenilaianKaryawan::where('periode_tahun', $tahun)
            ->count();

        $ringkasanAbsensi = [
            'hadir'     => Absensi::whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->where('status', 'hadir')->count(),
            'terlambat' => Absensi::whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->where('status', 'terlambat')->count(),
            'izin'      => Absensi::whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->where('status', 'izin')->count(),
            'alpha'     => Absensi::whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->where('status', 'alpha')->count(),
        ];

        $penilaianTerakhir = PenilaianKaryawan::with('karyawan')
            // ->where('periode_bulan', $bulan)
            ->where('periode_tahun', $tahun)
            ->orderByDesc('nilai_total')
            ->limit(5)
            ->get();

        return view('pimpinan.dashboard', compact(
            'now',
            'totalKaryawan',
            'hadirHariIni',
            'terlambat',
            'tidakHadir',
            'izinPending',
            'lemburPending',
            'totalGajiBulanIni',
            'penilaianBulanIni',
            'ringkasanAbsensi',
            'penilaianTerakhir'
        ));
    }
}
