<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\Karyawan;
use App\Models\Lembur;
use App\Models\Pengaturan;
use App\Models\Penggajian;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DataGajiController extends Controller
{
    public function index(Request $request)
    {
        $query = Penggajian::with('karyawan');

        if ($request->filled('bulan')) {
            $query->where('periode_bulan', $request->bulan);
        }

        if ($request->filled('tahun')) {
            $query->where('periode_tahun', $request->tahun);
        }

        if ($request->filled('karyawan_id')) {
            $query->where('karyawan_id', $request->karyawan_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $penggajian   = $query->latest()->paginate(10);
        $karyawanList = Karyawan::orderBy('nama')->get();

        $hasFilter = $request->filled('bulan')
            || $request->filled('tahun')
            || $request->filled('karyawan_id')
            || $request->filled('status');

        return view('admin.penggajian.data_gaji', compact(
            'penggajian',
            'karyawanList',
            'hasFilter'
        ));
    }

    public function generate(Request $request)
    {
        $request->validate([
            'bulan' => 'required|integer|between:1,12',
            'tahun' => 'required|integer|min:2000|max:' . (date('Y') + 1),
        ]);

        $bulan             = (int) $request->bulan;
        $tahun             = (int) $request->tahun;
        $dendaPerTerlambat = (float) Pengaturan::getValue('denda_keterlambatan', 0);

        $karyawanList = Karyawan::where('status', 'aktif')->get();

        $generated = 0;
        $skipped   = 0;

        foreach ($karyawanList as $karyawan) {
            $exists = Penggajian::where('karyawan_id', $karyawan->id)
                ->where('periode_bulan', $bulan)
                ->where('periode_tahun', $tahun)
                ->exists();

            if ($exists) {
                $skipped++;
                continue;
            }

            $absensi = Absensi::where('karyawan_id', $karyawan->id)
                ->whereMonth('tanggal', $bulan)
                ->whereYear('tanggal', $tahun)
                ->get();

            $totalHadir      = $absensi->whereIn('status', ['hadir', 'terlambat'])->count();
            $totalTerlambat  = $absensi->where('status', 'terlambat')->count();
            $ringkasanLembur = $this->hitungRingkasanLembur($karyawan->id, $bulan, $tahun);

            $potongan = $totalTerlambat * $dendaPerTerlambat;

            $gajiDasar = $karyawan->status_gaji === 'harian'
                ? (float) ($karyawan->gaji_per_hari ?? 0) * $totalHadir
                : (float) ($karyawan->gaji_pokok ?? 0);

            $totalGaji = max($gajiDasar + $ringkasanLembur['total_upah'] - $potongan, 0);

            DB::transaction(function () use (
                $karyawan,
                $bulan,
                $tahun,
                $totalHadir,
                $ringkasanLembur,
                $potongan,
                $totalGaji,
                $gajiDasar,
                $totalTerlambat
            ) {
                $penggajian = Penggajian::create([
                    'karyawan_id'   => $karyawan->id,
                    'periode_bulan' => $bulan,
                    'periode_tahun' => $tahun,
                    'total_hadir'   => $totalHadir,
                    'total_lembur'  => $ringkasanLembur['total_upah'],
                    'potongan'      => $potongan,
                    'total_gaji'    => $totalGaji,
                    'status'        => 'proses',
                ]);

                $labelGaji = $karyawan->status_gaji === 'harian'
                    ? 'Gaji Harian (' . $totalHadir . ' hari x Rp ' . number_format((float) ($karyawan->gaji_per_hari ?? 0), 0, ',', '.') . ')'
                    : 'Gaji Pokok';

                $details = [[
                    'keterangan' => $labelGaji,
                    'jumlah'     => $gajiDasar,
                    'tipe'       => 'pemasukan',
                ]];

                if ($ringkasanLembur['total_upah'] > 0) {
                    $details[] = [
                        'keterangan' => 'Upah Lembur (' . $this->formatJamLembur($ringkasanLembur['total_jam']) . ' jam)',
                        'jumlah'     => $ringkasanLembur['total_upah'],
                        'tipe'       => 'pemasukan',
                    ];
                }

                if ($potongan > 0) {
                    $details[] = [
                        'keterangan' => "Potongan Keterlambatan ({$totalTerlambat}x)",
                        'jumlah'     => $potongan,
                        'tipe'       => 'potongan',
                    ];
                }

                $penggajian->details()->createMany($details);
            });

            $generated++;
        }

        $msg = "Generate gaji berhasil: {$generated} karyawan diproses.";
        if ($skipped > 0) {
            $msg .= " {$skipped} karyawan dilewati karena sudah ada data.";
        }

        return redirect()
            ->route('admin.penggajian', ['bulan' => $bulan, 'tahun' => $tahun])
            ->with('success', $msg);
    }

    private function hitungRingkasanLembur(int $karyawanId, int $bulan, int $tahun): array
    {
        $lemburDisetujui = Lembur::where('karyawan_id', $karyawanId)
            ->whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->where('status', 'disetujui')
            ->get();

        $totalJam  = 0.0;
        $totalUpah = 0.0;

        foreach ($lemburDisetujui as $lembur) {
            $totalJam  += (float) ($lembur->total_jam ?? 0);
            $totalUpah += (float) ($lembur->total_upah ?? 0);
        }

        return [
            'total_jam' => round($totalJam, 2),
            'total_upah' => round($totalUpah, 2),
        ];
    }

    private function formatJamLembur(float $totalJam): string
    {
        return rtrim(rtrim(number_format($totalJam, 2, '.', ''), '0'), '.');
    }

    public function show(int $id)
    {
        $penggajian = Penggajian::with(['karyawan.jabatan', 'details'])->findOrFail($id);

        return view('admin.penggajian.show', compact('penggajian'));
    }

    public function markBayar(int $id)
    {
        $penggajian = Penggajian::findOrFail($id);
        $penggajian->update([
            'status'      => 'dibayar',
            'tgl_dibayar' => Carbon::today(),
        ]);

        return redirect()
            ->route('admin.penggajian.show', $id)
            ->with('success', 'Gaji berhasil ditandai sebagai dibayar.');
    }
}
