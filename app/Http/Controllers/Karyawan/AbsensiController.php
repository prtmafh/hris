<?php

namespace App\Http\Controllers\Karyawan;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\AbsensiSesi;
use App\Models\Pengaturan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AbsensiController extends Controller
{
    public function index(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $karyawan = $user->karyawan()->firstOrFail();
        $statusGaji = $karyawan->status_gaji;

        $bulan = $request->get('bulan', Carbon::now()->month);
        $tahun = $request->get('tahun', Carbon::now()->year);

        $query = Absensi::where('karyawan_id', $karyawan->id)
            ->whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->orderByDesc('tanggal');

        $absensi = $statusGaji === 'harian'
            ? $query->with('sesi')->get()
            : $query->get();

        $totalHadir     = $absensi->whereIn('status', ['hadir', 'terlambat'])->count();
        $totalTerlambat = $absensi->where('status', 'terlambat')->count();
        $totalAlpha     = $absensi->where('status', 'alpha')->count();
        $totalIzin      = $absensi->where('status', 'izin')->count();

        $daftarTahun = range(Carbon::now()->year, Carbon::now()->year - 3);

        return view('karyawan.absensi-saya', compact(
            'absensi',
            'bulan',
            'tahun',
            'daftarTahun',
            'totalHadir',
            'totalTerlambat',
            'totalAlpha',
            'totalIzin',
            'statusGaji'
        ));
    }

    // ──────────────────────────────────────────────
    //  ABSENSI BULANAN
    // ──────────────────────────────────────────────

    public function absenMasuk(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $karyawan = $user->karyawan()->firstOrFail();

        if ($karyawan->status_gaji === 'harian') {
            return response()->json([
                'status'  => 'error',
                'message' => 'Karyawan harian menggunakan absen per sesi.',
            ]);
        }

        $today = Carbon::today();

        $existing = Absensi::where('karyawan_id', $karyawan->id)
            ->whereDate('tanggal', $today)
            ->first();

        if ($existing && $existing->jam_masuk) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Anda sudah melakukan absen masuk hari ini.',
            ]);
        }

        $now            = Carbon::now();
        $jamMasuk       = Pengaturan::getValue('jam_masuk', '08:00');
        $toleransiMenit = Pengaturan::getValue('toleransi_keterlambatan', 10);
        $batasLambat    = Carbon::parse($jamMasuk)->addMinutes($toleransiMenit);
        $status         = $now->gt($batasLambat) ? 'terlambat' : 'hadir';

        $fotoPath = $this->simpanFoto($request->foto, 'absen_masuk', $karyawan->id, $now);

        $data = [
            'jam_masuk'       => $now->format('H:i:s'),
            'status'          => $status,
            'latitude_masuk'  => $request->latitude,
            'longitude_masuk' => $request->longitude,
            'foto_masuk'      => $fotoPath,
        ];

        if ($existing) {
            $existing->update($data);
        } else {
            Absensi::create(array_merge($data, [
                'karyawan_id' => $karyawan->id,
                'tanggal'     => $today,
            ]));
        }

        $label = $status === 'terlambat' ? 'Terlambat' : 'Hadir';

        return response()->json([
            'status'  => 'success',
            'message' => "Absen masuk berhasil! Status: {$label}",
        ]);
    }

    public function absenPulang(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $karyawan = $user->karyawan()->firstOrFail();

        if ($karyawan->status_gaji === 'harian') {
            return response()->json([
                'status'  => 'error',
                'message' => 'Karyawan harian menggunakan absen per sesi.',
            ]);
        }

        $today = Carbon::today();

        $absensi = Absensi::where('karyawan_id', $karyawan->id)
            ->whereDate('tanggal', $today)
            ->first();

        if (!$absensi || !$absensi->jam_masuk) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Anda belum melakukan absen masuk hari ini.',
            ]);
        }

        if ($absensi->jam_keluar) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Anda sudah melakukan absen pulang hari ini.',
            ]);
        }

        $now      = Carbon::now();
        $fotoPath = $this->simpanFoto($request->foto, 'absen_keluar', $karyawan->id, $now);

        $absensi->update([
            'jam_keluar'       => $now->format('H:i:s'),
            'latitude_keluar'  => $request->latitude,
            'longitude_keluar' => $request->longitude,
            'foto_keluar'      => $fotoPath,
        ]);

        return response()->json([
            'status'  => 'success',
            'message' => 'Absen pulang berhasil!',
        ]);
    }

    // ──────────────────────────────────────────────
    //  ABSENSI HARIAN (PER SESI)
    // ──────────────────────────────────────────────

    public function absenMasukSesi(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $karyawan = $user->karyawan()->firstOrFail();

        if ($karyawan->status_gaji !== 'harian') {
            return response()->json([
                'status'  => 'error',
                'message' => 'Fitur ini hanya untuk karyawan harian.',
            ]);
        }

        $now     = Carbon::now();
        $today   = Carbon::today();
        $maxSesi = (int) Pengaturan::getValue('max_sesi_harian', 3);

        // Tentukan sesi_ke berdasarkan window waktu dari pengaturan
        [$sesiKe, $jamMulaiSesi] = $this->deteksiSesiAktif($now, $maxSesi);

        if (!$sesiKe) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Tidak ada sesi kerja yang aktif saat ini. Periksa jadwal sesi di pengaturan.',
            ]);
        }

        $absensi = Absensi::firstOrCreate(
            ['karyawan_id' => $karyawan->id, 'tanggal' => $today],
            ['status' => 'hadir']
        );

        // Cek sudah absen masuk sesi ini hari ini
        $sudahMasukSesiIni = AbsensiSesi::where('absensi_id', $absensi->id)
            ->where('sesi_ke', $sesiKe)
            ->exists();

        if ($sudahMasukSesiIni) {
            return response()->json([
                'status'  => 'error',
                'message' => "Anda sudah melakukan absen masuk sesi {$sesiKe} hari ini.",
            ]);
        }

        // Cek ada sesi aktif yang belum ditutup
        $aktiveSesi = AbsensiSesi::where('absensi_id', $absensi->id)
            ->whereNotNull('jam_checkin')
            ->whereNull('jam_checkout')
            ->first();

        if ($aktiveSesi) {
            return response()->json([
                'status'  => 'error',
                'message' => "Anda masih dalam sesi {$aktiveSesi->sesi_ke}. Selesaikan absen pulang sesi ini dulu.",
            ]);
        }

        $toleransiMenit = (int) Pengaturan::getValue('toleransi_keterlambatan', 10);
        $batasLambat    = $jamMulaiSesi->copy()->addMinutes($toleransiMenit);
        $status         = $now->gt($batasLambat) ? 'terlambat' : 'hadir';

        $fotoPath = $this->simpanFoto($request->foto, 'absen_masuk_sesi', $karyawan->id, $now);

        AbsensiSesi::create([
            'absensi_id'      => $absensi->id,
            'sesi_ke'         => $sesiKe,
            'jam_checkin'     => $now->format('H:i:s'),
            'status'          => $status,
            'latitude_masuk'  => $request->latitude,
            'longitude_masuk' => $request->longitude,
            'foto_masuk'      => $fotoPath,
        ]);

        // Update parent: set jam_masuk & status dari sesi pertama yang masuk
        if (!$absensi->jam_masuk) {
            $absensi->update(['status' => $status, 'jam_masuk' => $now->format('H:i:s')]);
        }

        $label = $status === 'terlambat' ? 'Terlambat' : 'Hadir';

        return response()->json([
            'status'  => 'success',
            'message' => "Absen masuk sesi {$sesiKe} berhasil! Status: {$label}",
        ]);
    }

    public function absenPulangSesi(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $karyawan = $user->karyawan()->firstOrFail();

        if ($karyawan->status_gaji !== 'harian') {
            return response()->json([
                'status'  => 'error',
                'message' => 'Fitur ini hanya untuk karyawan harian.',
            ]);
        }

        $today = Carbon::today();

        $absensi = Absensi::where('karyawan_id', $karyawan->id)
            ->whereDate('tanggal', $today)
            ->first();

        if (!$absensi) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Anda belum melakukan absen masuk hari ini.',
            ]);
        }

        $aktiveSesi = AbsensiSesi::where('absensi_id', $absensi->id)
            ->whereNotNull('jam_checkin')
            ->whereNull('jam_checkout')
            ->first();

        if (!$aktiveSesi) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Tidak ada sesi aktif untuk diakhiri.',
            ]);
        }

        $now      = Carbon::now();
        $fotoPath = $this->simpanFoto($request->foto, 'absen_keluar_sesi', $karyawan->id, $now);

        $aktiveSesi->update([
            'jam_checkout'     => $now->format('H:i:s'),
            'latitude_keluar'  => $request->latitude,
            'longitude_keluar' => $request->longitude,
            'foto_keluar'      => $fotoPath,
        ]);

        $absensi->update(['jam_keluar' => $now->format('H:i:s')]);

        return response()->json([
            'status'  => 'success',
            'message' => "Absen pulang sesi {$aktiveSesi->sesi_ke} berhasil!",
        ]);
    }

    // ──────────────────────────────────────────────
    //  HELPER
    // ──────────────────────────────────────────────

    /**
     * Deteksi sesi yang sedang aktif berdasarkan window waktu di pengaturan.
     * Mengembalikan [sesi_ke, Carbon jamMulai] atau [null, null] jika di luar window.
     */
    public static function deteksiSesiAktif(Carbon $now, int $maxSesi): array
    {
        for ($i = 1; $i <= $maxSesi; $i++) {
            $mulai   = Pengaturan::getValue("sesi_{$i}_mulai");
            $selesai = Pengaturan::getValue("sesi_{$i}_selesai");

            if (!$mulai || !$selesai) {
                continue;
            }

            $jamMulai   = Carbon::parse($mulai);
            $jamSelesai = Carbon::parse($selesai);

            if ($now->between($jamMulai, $jamSelesai)) {
                return [$i, $jamMulai];
            }
        }

        return [null, null];
    }

    private function simpanFoto(?string $base64, string $prefix, int $karyawanId, Carbon $now): ?string
    {
        if (!$base64) {
            return null;
        }

        $imageData = preg_replace('/^data:image\/\w+;base64,/', '', $base64);
        $decoded   = base64_decode($imageData);
        $filename  = "{$prefix}_{$karyawanId}_{$now->format('Ymd_His')}.jpg";
        Storage::disk('public')->put('foto_absen/' . $filename, $decoded);

        return 'foto_absen/' . $filename;
    }
}
