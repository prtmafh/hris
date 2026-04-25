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

            // Ambil data absensi (yang tidak punya sesi)
            $absensi = Absensi::where('karyawan_id', $karyawan->id)
                ->whereMonth('tanggal', $bulan)
                ->whereYear('tanggal', $tahun)
                ->whereDoesntHave('sesi')
                ->get();

            // Ambil data absensi dengan sesi
            $absensiDenganSesi = Absensi::where('karyawan_id', $karyawan->id)
                ->whereMonth('tanggal', $bulan)
                ->whereYear('tanggal', $tahun)
                ->whereHas('sesi')
                ->with('sesi')
                ->get();

            // Hitung absensi biasa
            $totalHadirBiasa      = $absensi->whereIn('status', ['hadir', 'terlambat'])->count();
            $totalTerlambatBiasa  = $absensi->where('status', 'terlambat')->count();

            // Hitung absensi sesi
            $hitungSesi = $this->hitungAbsensiSesi($absensiDenganSesi);

            // Total kehadiran gabungan (untuk status harian dan gaji)
            $totalHadir      = $totalHadirBiasa + $hitungSesi['total_sesi_hadir'];
            $totalTerlambat  = $totalTerlambatBiasa + $hitungSesi['total_sesi_terlambat'];

            $ringkasanLembur = $this->hitungRingkasanLembur($karyawan->id, $bulan, $tahun);

            // Potongan keterlambatan
            $potongan = ($totalTerlambatBiasa + $hitungSesi['total_sesi_terlambat']) * $dendaPerTerlambat;

            // Hitung gaji berdasarkan jenis gaji
            if ($karyawan->status_gaji === 'harian') {
                // Untuk harian: hitung dari hari kerja + sesi
                $gajiDariAbsensiHarian = (float) ($karyawan->gaji_per_hari ?? 0) * $totalHadirBiasa;
                $gajiDariSesi = $this->hitungGajiSesi($karyawan, $hitungSesi);
                $gajiDasar = $gajiDariAbsensiHarian + $gajiDariSesi;
            } else {
                // Untuk bulanan: tetap gaji pokok (upah bulanan), tidak dipengaruhi absensi biasa
                $gajiDasar = (float) ($karyawan->gaji_pokok ?? 0);
            }

            $totalGaji = max($gajiDasar + $ringkasanLembur['total_upah'] - $potongan, 0);

            DB::transaction(function () use (
                $karyawan,
                $bulan,
                $tahun,
                $totalHadir,
                $totalTerlambat,
                $ringkasanLembur,
                $potongan,
                $totalGaji,
                $gajiDasar,
                $hitungSesi,
                $totalHadirBiasa,
                $totalTerlambatBiasa
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

                // Detail breakdown gaji
                $details = [];

                // Untuk karyawan harian: breakdown gaji harian + sesi
                if ($karyawan->status_gaji === 'harian') {
                    // Gaji dari absensi biasa
                    if ($totalHadirBiasa > 0) {
                        $gajiHarian = (float) ($karyawan->gaji_per_hari ?? 0) * $totalHadirBiasa;
                        $details[] = [
                            'keterangan' => 'Gaji Harian (' . $totalHadirBiasa . ' hari x Rp ' . number_format((float) ($karyawan->gaji_per_hari ?? 0), 0, ',', '.') . ')',
                            'jumlah'     => $gajiHarian,
                            'tipe'       => 'pemasukan',
                        ];
                    }

                    // Gaji dari sesi
                    if ($hitungSesi['total_sesi_hadir'] > 0 || $hitungSesi['total_sesi_terlambat'] > 0) {
                        $gajiSesi = $this->hitungGajiSesi($karyawan, $hitungSesi);
                        if ($gajiSesi > 0) {
                            $totalSesiDibayar = $hitungSesi['total_sesi_hadir'] + $hitungSesi['total_sesi_terlambat'];
                            $details[] = [
                                'keterangan' => 'Gaji Sesi (' . $totalSesiDibayar . ' sesi x Rp ' . number_format($this->hitungUpahPerSesi($karyawan), 0, ',', '.') . ')',
                                'jumlah'     => $gajiSesi,
                                'tipe'       => 'pemasukan',
                            ];
                        }
                    }
                } else {
                    // Untuk karyawan bulanan: gaji pokok tetap
                    $details[] = [
                        'keterangan' => 'Gaji Pokok (Bulanan)',
                        'jumlah'     => $gajiDasar,
                        'tipe'       => 'pemasukan',
                    ];
                }

                // Lembur
                if ($ringkasanLembur['total_upah'] > 0) {
                    $details[] = [
                        'keterangan' => 'Upah Lembur (' . $this->formatJamLembur($ringkasanLembur['total_jam']) . ' jam)',
                        'jumlah'     => $ringkasanLembur['total_upah'],
                        'tipe'       => 'pemasukan',
                    ];
                }

                // Potongan keterlambatan
                if ($potongan > 0) {
                    $totalTerlambat = $totalTerlambatBiasa + $hitungSesi['total_sesi_terlambat'];
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

    /**
     * Hitung ringkasan absensi sesi
     * Konsep: 1 hari = 1 hari kerja (tidak dihitung per sesi untuk hari kerja)
     * Tapi upah dihitung per sesi yang hadir/terlambat
     */
    private function hitungAbsensiSesi($absensiDenganSesi): array
    {
        $totalSesiHadir      = 0;
        $totalSesiTerlambat  = 0;
        $totalSesiAlpha      = 0;

        foreach ($absensiDenganSesi as $abs) {
            foreach ($abs->sesi as $sesi) {
                if ($sesi->status === 'alpha') {
                    $totalSesiAlpha++;
                } elseif ($sesi->status === 'terlambat') {
                    $totalSesiTerlambat++;
                } else {
                    // hadir atau izin tetap dihitung sebagai sesi hadir
                    $totalSesiHadir++;
                }
            }
        }

        return [
            'total_sesi_hadir'      => $totalSesiHadir,
            'total_sesi_terlambat'  => $totalSesiTerlambat,
            'total_sesi_alpha'      => $totalSesiAlpha,
        ];
    }

    /**
     * Hitung upah per sesi
     * Untuk karyawan harian: gaji_per_hari / jumlah_sesi_per_hari
     * Asumsi: jumlah sesi per hari adalah pengaturan aplikasi
     */
    private function hitungUpahPerSesi($karyawan): float
    {
        $gajiPerHari = (float) ($karyawan->gaji_per_hari ?? 0);
        $jumlahSesiPerHari = (int) Pengaturan::getValue('jumlah_sesi_per_hari', 2); // Default 2 sesi

        return $gajiPerHari / $jumlahSesiPerHari;
    }

    /**
     * Hitung total gaji dari sesi
     * Formula: (sesi_hadir + sesi_terlambat) × upah_per_sesi
     */
    private function hitungGajiSesi($karyawan, $hitungSesi): float
    {
        $totalSesiDibayar = $hitungSesi['total_sesi_hadir'] + $hitungSesi['total_sesi_terlambat'];
        $upahPerSesi = $this->hitungUpahPerSesi($karyawan);

        return $totalSesiDibayar * $upahPerSesi;
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
