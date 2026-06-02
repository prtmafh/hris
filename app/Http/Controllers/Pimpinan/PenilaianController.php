<?php

namespace App\Http\Controllers\Pimpinan;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\Jabatan;
use App\Models\Karyawan;
use App\Models\PenilaianKaryawan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PenilaianController extends Controller
{
    public function index(Request $request)
    {
        $bulan     = $request->input('bulan', Carbon::now()->month);
        $tahun     = $request->input('tahun', Carbon::now()->year);
        $jabatanId = $request->input('jabatan_id');

        $query = PenilaianKaryawan::with('karyawan.jabatan')
            ->where('periode_bulan', $bulan)
            ->where('periode_tahun', $tahun);

        if ($jabatanId) {
            $query->whereHas('karyawan', fn($q) => $q->where('jabatan_id', $jabatanId));
        }

        $penilaian = $query->orderByDesc('nilai_total')->get();
        $jabatan   = Jabatan::orderBy('nama_jabatan')->get();
        $tahunList = range(Carbon::now()->year, Carbon::now()->year - 3);

        $rekapGrade = [
            'A' => $penilaian->where('grade', 'A')->count(),
            'B' => $penilaian->where('grade', 'B')->count(),
            'C' => $penilaian->where('grade', 'C')->count(),
            'D' => $penilaian->where('grade', 'D')->count(),
        ];

        return view('pimpinan.penilaian.index', compact(
            'penilaian', 'jabatan', 'bulan', 'tahun', 'jabatanId', 'tahunList', 'rekapGrade'
        ));
    }

    public function create()
    {
        $bulan     = Carbon::now()->month;
        $tahun     = Carbon::now()->year;
        $karyawan  = Karyawan::with('jabatan')->where('status', 'aktif')->orderBy('nama')->get();
        $tahunList = range(Carbon::now()->year, Carbon::now()->year - 2);
        $nilaiKehadiranMap = $this->buatMapNilaiKehadiran($karyawan->pluck('id')->all(), $tahunList);

        // Ambil id karyawan yang sudah dinilai bulan ini
        $sudahDinilai = PenilaianKaryawan::where('periode_bulan', $bulan)
            ->where('periode_tahun', $tahun)
            ->pluck('karyawan_id')
            ->toArray();

        return view('pimpinan.penilaian.create', compact(
            'karyawan', 'bulan', 'tahun', 'tahunList', 'sudahDinilai', 'nilaiKehadiranMap'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'karyawan_id'      => 'required|exists:karyawan,id',
            'periode_bulan'    => 'required|integer|min:1|max:12',
            'periode_tahun'    => 'required|integer|min:2020',
            'nilai_kedisiplinan' => 'required|numeric|min:0|max:100',
            'nilai_kinerja'    => 'required|numeric|min:0|max:100',
            'catatan'          => 'nullable|string|max:1000',
        ]);

        $existing = PenilaianKaryawan::where('karyawan_id', $request->karyawan_id)
            ->where('periode_bulan', $request->periode_bulan)
            ->where('periode_tahun', $request->periode_tahun)
            ->first();

        if ($existing) {
            return back()->withErrors(['error' => 'Karyawan ini sudah dinilai untuk periode tersebut.'])->withInput();
        }

        $nilaiKehadiran = $this->hitungNilaiKehadiran(
            (int) $request->karyawan_id,
            (int) $request->periode_bulan,
            (int) $request->periode_tahun
        );

        $nilaiTotal = PenilaianKaryawan::hitungNilaiTotal(
            $nilaiKehadiran,
            $request->nilai_kedisiplinan,
            $request->nilai_kinerja
        );

        PenilaianKaryawan::create([
            'karyawan_id'       => $request->karyawan_id,
            'penilaian_oleh'    => Auth::id(),
            'periode_bulan'     => $request->periode_bulan,
            'periode_tahun'     => $request->periode_tahun,
            'nilai_kehadiran'   => $nilaiKehadiran,
            'nilai_kedisiplinan' => $request->nilai_kedisiplinan,
            'nilai_kinerja'     => $request->nilai_kinerja,
            'nilai_total'       => $nilaiTotal,
            'grade'             => PenilaianKaryawan::hitungGrade($nilaiTotal),
            'catatan'           => $request->catatan,
        ]);

        return redirect()->route('pimpinan.penilaian.index')
            ->with('success', 'Penilaian berhasil disimpan.');
    }

    public function show($id)
    {
        $penilaian = PenilaianKaryawan::with(['karyawan.jabatan', 'penilai'])->findOrFail($id);

        // Ambil data absensi periode yang sama untuk referensi
        $absensiPeriode = Absensi::where('karyawan_id', $penilaian->karyawan_id)
            ->whereMonth('tanggal', $penilaian->periode_bulan)
            ->whereYear('tanggal', $penilaian->periode_tahun)
            ->get();

        $rekapAbsensi = [
            'hadir'     => $absensiPeriode->where('status', 'hadir')->count(),
            'terlambat' => $absensiPeriode->where('status', 'terlambat')->count(),
            'izin'      => $absensiPeriode->where('status', 'izin')->count(),
            'alpha'     => $absensiPeriode->where('status', 'alpha')->count(),
        ];

        return view('pimpinan.penilaian.show', compact('penilaian', 'rekapAbsensi'));
    }

    public function edit($id)
    {
        $penilaian = PenilaianKaryawan::with('karyawan.jabatan')->findOrFail($id);
        $tahunList = range(Carbon::now()->year, Carbon::now()->year - 2);
        $nilaiKehadiran = $this->hitungNilaiKehadiran(
            (int) $penilaian->karyawan_id,
            (int) $penilaian->periode_bulan,
            (int) $penilaian->periode_tahun
        );

        return view('pimpinan.penilaian.edit', compact('penilaian', 'tahunList', 'nilaiKehadiran'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nilai_kedisiplinan' => 'required|numeric|min:0|max:100',
            'nilai_kinerja'     => 'required|numeric|min:0|max:100',
            'catatan'           => 'nullable|string|max:1000',
        ]);

        $penilaian  = PenilaianKaryawan::findOrFail($id);
        $nilaiKehadiran = $this->hitungNilaiKehadiran(
            (int) $penilaian->karyawan_id,
            (int) $penilaian->periode_bulan,
            (int) $penilaian->periode_tahun
        );

        $nilaiTotal = PenilaianKaryawan::hitungNilaiTotal(
            $nilaiKehadiran,
            $request->nilai_kedisiplinan,
            $request->nilai_kinerja
        );

        $penilaian->update([
            'nilai_kehadiran'    => $nilaiKehadiran,
            'nilai_kedisiplinan' => $request->nilai_kedisiplinan,
            'nilai_kinerja'      => $request->nilai_kinerja,
            'nilai_total'        => $nilaiTotal,
            'grade'              => PenilaianKaryawan::hitungGrade($nilaiTotal),
            'catatan'            => $request->catatan,
        ]);

        return redirect()->route('pimpinan.penilaian.index')
            ->with('success', 'Penilaian berhasil diperbarui.');
    }

    public function destroy($id)
    {
        PenilaianKaryawan::findOrFail($id)->delete();
        return back()->with('success', 'Penilaian berhasil dihapus.');
    }

    private function hitungNilaiKehadiran(int $karyawanId, int $bulan, int $tahun): float
    {
        $absensi = Absensi::where('karyawan_id', $karyawanId)
            ->whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->get();

        $total = $absensi->count();
        if ($total === 0) {
            return 0;
        }

        $hadir = $absensi->whereIn('status', ['hadir', 'terlambat'])->count();

        return round(($hadir / $total) * 100, 2);
    }

    private function buatMapNilaiKehadiran(array $karyawanIds, array $tahunList): array
    {
        if (empty($karyawanIds) || empty($tahunList)) {
            return [];
        }

        $awal = Carbon::create(min($tahunList), 1, 1)->startOfDay();
        $akhir = Carbon::create(max($tahunList), 12, 31)->endOfDay();

        $absensi = Absensi::whereIn('karyawan_id', $karyawanIds)
            ->whereBetween('tanggal', [$awal, $akhir])
            ->get()
            ->groupBy([
                'karyawan_id',
                fn($item) => $item->tanggal->format('Y'),
                fn($item) => $item->tanggal->format('n'),
            ]);

        $map = [];
        foreach ($karyawanIds as $karyawanId) {
            foreach ($tahunList as $tahun) {
                foreach (range(1, 12) as $bulan) {
                    $data = $absensi[$karyawanId][$tahun][$bulan] ?? collect();
                    $total = $data->count();
                    $hadir = $data->whereIn('status', ['hadir', 'terlambat'])->count();

                    $map[$karyawanId][$tahun][$bulan] = $total > 0 ? round(($hadir / $total) * 100, 2) : 0;
                }
            }
        }

        return $map;
    }
}
