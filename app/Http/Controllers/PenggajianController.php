<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\DetailPenggajian;
use App\Models\Karyawan;
use App\Models\Lembur;
use App\Models\Penggajian;
use App\Models\Pengaturan;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PenggajianController extends Controller
{
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

            $totalHadir     = $absensi->whereIn('status', ['hadir', 'terlambat'])->count();
            $totalTerlambat = $absensi->where('status', 'terlambat')->count();

            $totalLembur = (float) Lembur::where('karyawan_id', $karyawan->id)
                ->whereMonth('tanggal', $bulan)
                ->whereYear('tanggal', $tahun)
                ->where('status', 'disetujui')
                ->sum('total_upah');

            $potongan = $totalTerlambat * $dendaPerTerlambat;

            $gajiDasar = $karyawan->status_gaji === 'harian'
                ? (float) $karyawan->gaji_per_hari * $totalHadir
                : (float) $karyawan->gaji_pokok;

            $totalGaji = max($gajiDasar + $totalLembur - $potongan, 0);

            $penggajian = Penggajian::create([
                'karyawan_id'   => $karyawan->id,
                'periode_bulan' => $bulan,
                'periode_tahun' => $tahun,
                'total_hadir'   => $totalHadir,
                'total_lembur'  => $totalLembur,
                'potongan'      => $potongan,
                'total_gaji'    => $totalGaji,
                'status'        => 'proses',
            ]);

            // Detail pemasukan
            $labelGaji = $karyawan->status_gaji === 'harian'
                ? "Gaji Harian ({$totalHadir} hari × Rp " . number_format($karyawan->gaji_per_hari, 0, ',', '.') . ')'
                : 'Gaji Pokok';

            $penggajian->details()->create([
                'keterangan' => $labelGaji,
                'jumlah'     => $gajiDasar,
                'tipe'       => 'pemasukan',
            ]);

            if ($totalLembur > 0) {
                $penggajian->details()->create([
                    'keterangan' => 'Tunjangan Lembur',
                    'jumlah'     => $totalLembur,
                    'tipe'       => 'pemasukan',
                ]);
            }

            // Detail potongan
            if ($potongan > 0) {
                $penggajian->details()->create([
                    'keterangan' => "Potongan Keterlambatan ({$totalTerlambat}x)",
                    'jumlah'     => $potongan,
                    'tipe'       => 'potongan',
                ]);
            }

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

    public function show(int $id)
    {
        $penggajian = Penggajian::with(['karyawan.jabatan', 'details'])->findOrFail($id);

        return view('admin.penggajian.show', compact('penggajian'));
    }

    public function markBayar(int $id)
    {
        $penggajian = Penggajian::findOrFail($id);
        $penggajian->update([
            'status'     => 'dibayar',
            'tgl_dibayar' => Carbon::today(),
        ]);

        return redirect()
            ->route('admin.penggajian.show', $id)
            ->with('success', 'Gaji berhasil ditandai sebagai dibayar.');
    }

    public function data_gaji(Request $request)
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

    public function showSlip(int $id)
    {
        /** @var \App\Models\User $user */
        $user      = auth()->user();
        $karyawan  = $user->karyawan()->firstOrFail();
        $penggajian = Penggajian::with(['details', 'karyawan.jabatan'])
            ->where('karyawan_id', $karyawan->id)
            ->findOrFail($id);

        return view('karyawan.slip-gaji-detail', compact('penggajian'));
    }
}
