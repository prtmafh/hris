<?php

namespace App\Http\Controllers\Karyawan;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Karyawan\AbsensiController;
use App\Models\Absensi;
use App\Models\AbsensiSesi;
use App\Models\Izin;
use App\Models\Lembur;
use App\Models\Pengaturan;
use App\Models\Penggajian;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $karyawan = $user->karyawan()->with('jabatan')->firstOrFail();

        $today        = Carbon::today();
        $currentMonth = $today->month;
        $currentYear  = $today->year;
        $statusGaji   = $karyawan->status_gaji;

        $cek = Absensi::where('karyawan_id', $karyawan->id)
            ->whereDate('tanggal', $today)
            ->first();

        // Data sesi untuk karyawan harian
        $sesiHariIni  = collect();
        $aktiveSesi   = null;
        $sesiSaatIni  = null; // sesi_ke berdasarkan window waktu saat ini
        $bisaMasukSesi = false;
        $maxSesi      = (int) Pengaturan::getValue('max_sesi_harian', 3);

        if ($statusGaji === 'harian') {
            [$sesiSaatIni] = AbsensiController::deteksiSesiAktif(Carbon::now(), $maxSesi);

            if ($cek) {
                $sesiHariIni = AbsensiSesi::where('absensi_id', $cek->id)
                    ->orderBy('sesi_ke')
                    ->get();

                $aktiveSesi = $sesiHariIni->first(fn($s) => $s->jam_checkin && !$s->jam_checkout);
            }

            // Bisa masuk sesi jika: ada window aktif, tidak ada sesi aktif yang belum ditutup,
            // dan sesi tersebut belum diisi hari ini
            $sudahMasukSesiIni = $sesiSaatIni && $sesiHariIni->contains('sesi_ke', $sesiSaatIni);
            $bisaMasukSesi     = $sesiSaatIni && !$aktiveSesi && !$sudahMasukSesiIni;
        }

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
            'statusGaji',
            'sesiHariIni',
            'aktiveSesi',
            'sesiSaatIni',
            'bisaMasukSesi',
            'maxSesi',
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
