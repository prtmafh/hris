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

        $totalKaryawan  = Karyawan::where('status', 'aktif')->count();
        $hadirHariIni   = Absensi::whereDate('tanggal', today())
            ->whereIn('status', ['hadir', 'terlambat'])->count();
        $terlambat      = Absensi::whereDate('tanggal', today())->where('status', 'terlambat')->count();
        $tidakHadir     = Karyawan::where('status', 'aktif')->count()
            - Absensi::whereDate('tanggal', today())->whereIn('status', ['hadir', 'terlambat', 'izin'])->count();

        $izinPending    = Izin::where('status_approval', 'pending')->count();
        $lemburPending  = Lembur::where('status', 'pending')->count();

        $totalGajiBulanIni = Penggajian::where('periode_bulan', $bulan)
            ->where('periode_tahun', $tahun)
            ->sum('total_gaji');

        $penilaianBulanIni = PenilaianKaryawan::where('periode_bulan', $bulan)
            ->where('periode_tahun', $tahun)
            ->count();

        $ringkasanAbsensi = [
            'hadir'     => Absensi::whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->where('status', 'hadir')->count(),
            'terlambat' => Absensi::whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->where('status', 'terlambat')->count(),
            'izin'      => Absensi::whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->where('status', 'izin')->count(),
            'alpha'     => Absensi::whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->where('status', 'alpha')->count(),
        ];

        $penilaianTerakhir = PenilaianKaryawan::with('karyawan')
            ->where('periode_bulan', $bulan)
            ->where('periode_tahun', $tahun)
            ->orderByDesc('nilai_total')
            ->limit(5)
            ->get();

        return view('pimpinan.dashboard', compact(
            'now', 'totalKaryawan', 'hadirHariIni', 'terlambat', 'tidakHadir',
            'izinPending', 'lemburPending', 'totalGajiBulanIni',
            'penilaianBulanIni', 'ringkasanAbsensi', 'penilaianTerakhir'
        ));
    }
}
