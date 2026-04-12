<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\Izin;
use App\Models\Lembur;
use App\Models\Penggajian;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class KaryawanController extends Controller
{
    // Waktu shift default (bisa disesuaikan)
    const SHIFT_START = '08:00:00';
    const SHIFT_END   = '17:00:00';
    // Toleransi keterlambatan (menit)
    const TOLERANCE_MINUTES = 15;

    public function index()
    {
        $user     = Auth::user();
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
        $shift_start  = self::SHIFT_START;
        $shift_end    = self::SHIFT_END;

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

    public function absensiSaya(Request $request)
    {
        $karyawan = Auth::user()->karyawan()->firstOrFail();

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
            'absensi', 'bulan', 'tahun', 'daftarTahun',
            'totalHadir', 'totalTerlambat', 'totalAlpha', 'totalIzin'
        ));
    }

    public function izinSaya(Request $request)
    {
        $karyawan = Auth::user()->karyawan()->firstOrFail();

        $izin = Izin::where('karyawan_id', $karyawan->id)
            ->orderByDesc('tanggal')
            ->paginate(10);

        return view('karyawan.izin-saya', compact('izin'));
    }

    public function storeIzin(Request $request)
    {
        $karyawan = Auth::user()->karyawan()->firstOrFail();

        $request->validate([
            'tanggal'    => 'required|date',
            'jenis_izin' => 'required|in:sakit,izin,cuti',
            'keterangan' => 'required|string|max:500',
        ]);

        $sudahAda = Izin::where('karyawan_id', $karyawan->id)
            ->whereDate('tanggal', $request->tanggal)
            ->exists();

        if ($sudahAda) {
            return back()->with('error', 'Pengajuan izin untuk tanggal tersebut sudah ada.');
        }

        Izin::create([
            'karyawan_id'     => $karyawan->id,
            'tanggal'         => $request->tanggal,
            'jenis_izin'      => $request->jenis_izin,
            'keterangan'      => $request->keterangan,
            'status_approval' => 'pending',
        ]);

        return back()->with('success', 'Pengajuan izin berhasil dikirim.');
    }

    public function lemburSaya(Request $request)
    {
        $karyawan = Auth::user()->karyawan()->firstOrFail();

        $lembur = Lembur::where('karyawan_id', $karyawan->id)
            ->orderByDesc('tanggal')
            ->paginate(10);

        return view('karyawan.lembur-saya', compact('lembur'));
    }

    public function storeLembur(Request $request)
    {
        $karyawan = Auth::user()->karyawan()->firstOrFail();

        $request->validate([
            'tanggal'     => 'required|date',
            'jam_mulai'   => 'required',
            'jam_selesai' => 'required|after:jam_mulai',
            'keterangan'  => 'required|string|max:500',
        ]);

        $mulai    = Carbon::parse($request->tanggal . ' ' . $request->jam_mulai);
        $selesai  = Carbon::parse($request->tanggal . ' ' . $request->jam_selesai);
        $totalJam = $selesai->diffInHours($mulai);

        Lembur::create([
            'karyawan_id' => $karyawan->id,
            'tanggal'     => $request->tanggal,
            'jam_mulai'   => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,
            'total_jam'   => $totalJam,
            'keterangan'  => $request->keterangan,
            'status'      => 'pending',
        ]);

        return back()->with('success', 'Pengajuan lembur berhasil dikirim.');
    }

    public function slipGaji(Request $request)
    {
        $karyawan = Auth::user()->karyawan()->firstOrFail();

        $tahun = $request->get('tahun', Carbon::now()->year);

        $penggajian = Penggajian::where('karyawan_id', $karyawan->id)
            ->whereYear('tgl_dibayar', $tahun)
            ->orderByDesc('periode_tahun')
            ->orderByDesc('periode_bulan')
            ->get();

        $daftarTahun = range(Carbon::now()->year, Carbon::now()->year - 3);

        return view('karyawan.slip-gaji', compact('penggajian', 'tahun', 'daftarTahun', 'karyawan'));
    }

    public function absenMasuk(Request $request)
    {
        $karyawan = Auth::user()->karyawan;
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

        $now        = Carbon::now();
        $batasLambat = Carbon::parse(self::SHIFT_START)->addMinutes(self::TOLERANCE_MINUTES);
        $status     = $now->gt($batasLambat) ? 'terlambat' : 'hadir';

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
        $karyawan = Auth::user()->karyawan;
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
