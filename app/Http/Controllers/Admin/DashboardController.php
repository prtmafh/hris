<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\Izin;
use App\Models\Karyawan;
use App\Models\Lembur;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();

        $totalKaryawan = Karyawan::where('status', 'aktif')->count();

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

        $hadirHariIni = $absensiHariIni->where('status', 'hadir')->count();
        $terlambatHariIni = $absensiHariIni->where('status', 'terlambat')->count();
        $izinHariIni = $absensiHariIni->where('status', 'izin')->count();
        $tidakHadirHariIni = max($totalKaryawan - ($hadirHariIni + $terlambatHariIni + $izinHariIni), 0);

        $izinPending = Izin::where('status_approval', 'pending')->count();
        $lemburPending = Lembur::where('status', 'pending')->count();

        $absensiBulanIni = Absensi::whereMonth('tanggal', $today->month)
            ->whereYear('tanggal', $today->year);

        $ringkasanBulanIni = [
            'hadir' => (clone $absensiBulanIni)->where('status', 'hadir')->count(),
            'terlambat' => (clone $absensiBulanIni)->where('status', 'terlambat')->count(),
            'izin' => (clone $absensiBulanIni)->where('status', 'izin')->count(),
            'alpha' => (clone $absensiBulanIni)->where('status', 'alpha')->count(),
        ];

        return view('admin.dashboard', compact(
            'today',
            'totalKaryawan',
            'absensiHariIni',
            'hadirHariIni',
            'terlambatHariIni',
            'izinHariIni',
            'tidakHadirHariIni',
            'izinPending',
            'lemburPending',
            'ringkasanBulanIni'
        ));
    }
}
