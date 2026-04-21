<?php

namespace App\Http\Controllers\Karyawan;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\Pengaturan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AbsensiController extends Controller
{
    public function index(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();
        $karyawan = $user->karyawan()->firstOrFail();

        $bulan = $request->get('bulan', Carbon::now()->month);
        $tahun = $request->get('tahun', Carbon::now()->year);

        $absensi = Absensi::where('karyawan_id', $karyawan->id)
            ->whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->orderByDesc('tanggal')
            ->get();

        $totalHadir    = $absensi->whereIn('status', ['hadir', 'terlambat'])->count();
        $totalTerlambat = $absensi->where('status', 'terlambat')->count();
        $totalAlpha    = $absensi->where('status', 'alpha')->count();
        $totalIzin     = $absensi->where('status', 'izin')->count();

        $daftarTahun = range(Carbon::now()->year, Carbon::now()->year - 3);

        return view('karyawan.absensi-saya', compact(
            'absensi',
            'bulan',
            'tahun',
            'daftarTahun',
            'totalHadir',
            'totalTerlambat',
            'totalAlpha',
            'totalIzin'
        ));
    }

    public function absenMasuk(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();
        $karyawan = $user->karyawan()->firstOrFail();
        $today    = Carbon::today();

        $existing = Absensi::where('karyawan_id', $karyawan->id)
            ->whereDate('tanggal', $today)
            ->first();

        if ($existing && $existing->jam_masuk) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Anda sudah melakukan absen masuk hari ini.'
            ]);
        }

        $now              = Carbon::now();
        $jamMasuk         = Pengaturan::getValue('jam_masuk', '08:00');
        $toleransiMenit   = Pengaturan::getValue('toleransi_keterlambatan', 10);
        $batasLambat      = Carbon::parse($jamMasuk)->addMinutes($toleransiMenit);
        $status           = $now->gt($batasLambat) ? 'terlambat' : 'hadir';

        $fotoPath = null;
        if ($request->filled('foto')) {
            $imageData = preg_replace('/^data:image\/\w+;base64,/', '', $request->foto);
            $decoded   = base64_decode($imageData);
            $filename  = 'absen_masuk_' . $karyawan->id . '_' . $now->format('Ymd_His') . '.jpg';
            Storage::disk('public')->put('foto_absen/' . $filename, $decoded);
            $fotoPath = 'foto_absen/' . $filename;
        }

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
            'message' => "Absen masuk berhasil! Status: {$label}"
        ]);
    }

    public function absenPulang(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();
        $karyawan = $user->karyawan()->firstOrFail();
        $today    = Carbon::today();

        $absensi = Absensi::where('karyawan_id', $karyawan->id)
            ->whereDate('tanggal', $today)
            ->first();

        if (!$absensi || !$absensi->jam_masuk) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Anda belum melakukan absen masuk hari ini.'
            ]);
        }

        if ($absensi->jam_keluar) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Anda sudah melakukan absen pulang hari ini.'
            ]);
        }

        $now = Carbon::now();

        $fotoPath = null;
        if ($request->filled('foto')) {
            $imageData = preg_replace('/^data:image\/\w+;base64,/', '', $request->foto);
            $decoded   = base64_decode($imageData);
            $filename  = 'absen_keluar_' . $karyawan->id . '_' . $now->format('Ymd_His') . '.jpg';
            Storage::disk('public')->put('foto_absen/' . $filename, $decoded);
            $fotoPath = 'foto_absen/' . $filename;
        }

        $absensi->update([
            'jam_keluar'       => $now->format('H:i:s'),
            'latitude_keluar'  => $request->latitude ?? null,
            'longitude_keluar' => $request->longitude ?? null,
            'foto_keluar'      => $fotoPath,
        ]);

        return response()->json([
            'status'  => 'success',
            'message' => 'Absen pulang berhasil!'
        ]);
    }
}
