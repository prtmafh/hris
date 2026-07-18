<?php

namespace App\Http\Controllers\Karyawan;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\AbsensiSesi;
use App\Models\Pengaturan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AbsensiController extends Controller
{
    public function index(Request $request)
    {
        /** @var \App\Models\Karyawan $karyawan */
        $karyawan = Auth::user();

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
        /** @var \App\Models\Karyawan $user */
        $karyawan = Auth::user();
        // $karyawan = $user->karyawan()->firstOrFail();

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
        /** @var \App\Models\Karyawan $user */
        $karyawan = Auth::user();
        // $karyawan = $user->karyawan()->firstOrFail();

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
        /** @var \App\Models\Karyawan $user */
        $karyawan = Auth::user();
        // $karyawan = $user->karyawan()->firstOrFail();

        if ($karyawan->status_gaji !== 'harian') {
            return response()->json([
                'status'  => 'error',
                'message' => 'Fitur ini hanya untuk karyawan harian.',
            ]);
        }

        $now     = Carbon::now();
        $maxSesi = (int) Pengaturan::getValue('max_sesi_harian', 3);

        // Tentukan sesi_ke berdasarkan window waktu dari pengaturan
        [$sesiKe,, $tanggalKerja] = $this->deteksiSesiAktif($now, $maxSesi);

        if (!$sesiKe) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Tidak ada sesi kerja yang aktif saat ini. Periksa jadwal sesi di pengaturan.',
            ]);
        }

        $absensiAktif = Absensi::where('karyawan_id', $karyawan->id)
            ->whereNotNull('jam_masuk')
            ->whereNull('jam_keluar')
            ->latest('tanggal')
            ->first();

        if ($absensiAktif) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Anda sudah melakukan absen masuk. Silakan lakukan absen pulang satu kali setelah pekerjaan selesai.',
            ]);
        }

        $sudahSelesai = Absensi::where('karyawan_id', $karyawan->id)
            ->whereDate('tanggal', $tanggalKerja)
            ->whereNotNull('jam_keluar')
            ->exists();

        if ($sudahSelesai) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Absensi untuk rangkaian sesi kerja ini sudah selesai.',
            ]);
        }

        // Absensi karyawan harian per sesi tidak menggunakan status terlambat.
        $status = 'hadir';

        $fotoPath = $this->simpanFoto($request->foto, 'absen_masuk_sesi', $karyawan->id, $now);

        DB::transaction(function () use ($karyawan, $tanggalKerja, $now, $status, $request, $fotoPath, $sesiKe) {
            $absensi = Absensi::updateOrCreate(
                ['karyawan_id' => $karyawan->id, 'tanggal' => $tanggalKerja],
                [
                    'status'          => $status,
                    'jam_masuk'       => $now->format('H:i:s'),
                    'jam_keluar'      => null,
                    'latitude_masuk'  => $request->latitude,
                    'longitude_masuk' => $request->longitude,
                    'foto_masuk'      => $fotoPath,
                ]
            );

            AbsensiSesi::updateOrCreate(
                ['absensi_id' => $absensi->id, 'sesi_ke' => $sesiKe],
                [
                    // Sesi pertama memakai waktu check-in aktual.
                    'jam_checkin'     => $now->format('H:i:s'),
                    'jam_checkout'    => null,
                    'status'          => $status,
                    'latitude_masuk'  => $request->latitude,
                    'longitude_masuk' => $request->longitude,
                    'foto_masuk'      => $fotoPath,
                ]
            );
        });

        return response()->json([
            'status'  => 'success',
            'message' => "Absen masuk berhasil pada sesi {$sesiKe}! Status: Hadir. Anda cukup absen pulang satu kali.",
        ]);
    }

    public function absenPulangSesi(Request $request)
    {
        /** @var \App\Models\Karyawan $user */
        $karyawan = Auth::user();
        // $karyawan = $user->karyawan()->firstOrFail();

        if ($karyawan->status_gaji !== 'harian') {
            return response()->json([
                'status'  => 'error',
                'message' => 'Fitur ini hanya untuk karyawan harian.',
            ]);
        }

        $absensi = Absensi::where('karyawan_id', $karyawan->id)
            ->whereNotNull('jam_masuk')
            ->whereNull('jam_keluar')
            ->latest('tanggal')
            ->first();

        if (!$absensi) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Anda belum melakukan absen masuk hari ini.',
            ]);
        }

        $now      = Carbon::now();
        $fotoPath = $this->simpanFoto($request->foto, 'absen_keluar_sesi', $karyawan->id, $now);

        $tanggalKerja = Carbon::parse($absensi->tanggal)->startOfDay();
        $maxSesi      = (int) Pengaturan::getValue('max_sesi_harian', 3);
        $jadwalKerja  = $this->jadwalSesi($tanggalKerja, $maxSesi);
        $sesiPertama  = AbsensiSesi::where('absensi_id', $absensi->id)
            ->whereNotNull('jam_checkin')
            ->orderBy('sesi_ke')
            ->first();
        $jadwalPertama = collect($jadwalKerja)->firstWhere('sesi_ke', $sesiPertama?->sesi_ke);
        $jamMasuk     = ($jadwalPertama['mulai'] ?? $tanggalKerja)->copy()
            ->setTimeFromTimeString($absensi->jam_masuk);
        $sesiTerhitung = [];

        // Tentukan sesi terakhir yang dilalui. Checkout sesi terakhir memakai
        // waktu aktual, sementara batas antar-sesi mengikuti Pengaturan.
        $sesiTerakhirKe = collect($jadwalKerja)
            ->filter(function ($jadwal) use ($absensi, $jamMasuk, $now) {
                $sudahDibuat = AbsensiSesi::where('absensi_id', $absensi->id)
                    ->where('sesi_ke', $jadwal['sesi_ke'])
                    ->exists();

                return $sudahDibuat
                    || ($jamMasuk->lt($jadwal['selesai']) && $now->gt($jadwal['mulai']));
            })
            ->last()['sesi_ke'] ?? null;

        DB::transaction(function () use ($absensi, $jadwalKerja, $jamMasuk, $now, $request, $fotoPath, $sesiPertama, $sesiTerakhirKe, &$sesiTerhitung) {
            foreach ($jadwalKerja as $jadwal) {
                $existing = AbsensiSesi::where('absensi_id', $absensi->id)
                    ->where('sesi_ke', $jadwal['sesi_ke'])
                    ->first();

                // Sesi dihitung jika waktu kerja beririsan dengan window sesi tersebut.
                // Sesi yang sudah dibuat tetap ditutup jika pengaturan berubah
                // ketika absensi sedang berjalan.
                if ($existing || ($jamMasuk->lt($jadwal['selesai']) && $now->gt($jadwal['mulai']))) {
                    AbsensiSesi::updateOrCreate(
                        ['absensi_id' => $absensi->id, 'sesi_ke' => $jadwal['sesi_ke']],
                        [
                            'jam_checkin'     => $jadwal['sesi_ke'] === $sesiPertama?->sesi_ke
                                ? $jamMasuk->format('H:i:s')
                                : $jadwal['mulai']->format('H:i:s'),
                            'jam_checkout'    => $jadwal['sesi_ke'] === $sesiTerakhirKe
                                ? $now->format('H:i:s')
                                : $jadwal['selesai']->format('H:i:s'),
                            'status'          => $existing?->status ?? 'hadir',
                            'latitude_masuk'  => $existing?->latitude_masuk ?? $absensi->latitude_masuk,
                            'longitude_masuk' => $existing?->longitude_masuk ?? $absensi->longitude_masuk,
                            'foto_masuk'      => $existing?->foto_masuk ?? $absensi->foto_masuk,
                            'latitude_keluar' => $request->latitude,
                            'longitude_keluar' => $request->longitude,
                            'foto_keluar'     => $fotoPath,
                        ]
                    );

                    $sesiTerhitung[] = $jadwal['sesi_ke'];
                }
            }

            $absensi->update([
                'jam_keluar'       => $now->format('H:i:s'),
                'latitude_keluar'  => $request->latitude,
                'longitude_keluar' => $request->longitude,
                'foto_keluar'      => $fotoPath,
            ]);
        });

        $daftarSesi = implode(', ', $sesiTerhitung);

        return response()->json([
            'status'  => 'success',
            'message' => "Absen pulang berhasil! Sesi yang dihitung: {$daftarSesi}.",
        ]);
    }

    // ──────────────────────────────────────────────
    //  HELPER
    // ──────────────────────────────────────────────

    /**
     * Deteksi sesi yang sedang aktif berdasarkan window waktu di pengaturan.
     * Mengembalikan [sesi_ke, jam mulai, tanggal kerja] dan mendukung sesi lintas hari.
     */
    public static function deteksiSesiAktif(Carbon $now, int $maxSesi): array
    {
        foreach ([$now->copy()->startOfDay(), $now->copy()->subDay()->startOfDay()] as $tanggalKerja) {
            foreach (self::jadwalSesi($tanggalKerja, $maxSesi) as $jadwal) {
                if ($now->betweenIncluded($jadwal['mulai'], $jadwal['selesai'])) {
                    return [$jadwal['sesi_ke'], $jadwal['mulai'], $tanggalKerja];
                }
            }
        }

        return [null, null, null];
    }

    /** Susun window sesi berurutan mulai dari tanggal kerja sesi pertama. */
    private static function jadwalSesi(Carbon $tanggalKerja, int $maxSesi): array
    {
        $jadwal = [];
        $mulaiSebelumnya = null;

        for ($i = 1; $i <= $maxSesi; $i++) {
            $mulai   = Pengaturan::getValue("sesi_{$i}_mulai");
            $selesai = Pengaturan::getValue("sesi_{$i}_selesai");

            if (!$mulai || !$selesai) {
                continue;
            }

            $jamMulai = $tanggalKerja->copy()->setTimeFromTimeString($mulai);
            while ($mulaiSebelumnya && $jamMulai->lte($mulaiSebelumnya)) {
                $jamMulai->addDay();
            }

            $jamSelesai = $jamMulai->copy()->setTimeFromTimeString($selesai);
            if ($jamSelesai->lt($jamMulai)) {
                $jamSelesai->addDay();
            }

            $jadwal[] = ['sesi_ke' => $i, 'mulai' => $jamMulai, 'selesai' => $jamSelesai];
            $mulaiSebelumnya = $jamMulai;
        }

        return $jadwal;
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
