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
        $tahun = $request->input('tahun', Carbon::now()->year);
        $jabatanId = $request->input('jabatan_id');

        $query = PenilaianKaryawan::with('karyawan.jabatan')
            ->where('periode_tahun', $tahun);

        if ($jabatanId) {
            $query->whereHas('karyawan', function ($q) use ($jabatanId) {
                $q->where('jabatan_id', $jabatanId);
            });
        }

        $penilaian = $query
            ->orderByDesc('nilai_total')
            ->get();

        $jabatan = Jabatan::orderBy('nama_jabatan')->get();

        $tahunList = range(
            Carbon::now()->year,
            Carbon::now()->year - 5
        );

        $rekapGrade = [
            'A' => $penilaian->where('grade', 'A')->count(),
            'B' => $penilaian->where('grade', 'B')->count(),
            'C' => $penilaian->where('grade', 'C')->count(),
            'D' => $penilaian->where('grade', 'D')->count(),
        ];

        return view('pimpinan.penilaian.index', compact(
            'penilaian',
            'jabatan',
            'tahun',
            'jabatanId',
            'tahunList',
            'rekapGrade'
        ));
    }

    public function create()
    {
        $tahun = Carbon::now()->year;

        $karyawan = Karyawan::with('jabatan')
            ->where('status', 'aktif')
            ->orderBy('nama')
            ->get();

        $tahunList = range(
            Carbon::now()->year,
            Carbon::now()->year - 5
        );

        $nilaiKehadiranMap = $this->buatMapNilaiKehadiran(
            $karyawan->pluck('id')->all(),
            $tahunList
        );

        $sudahDinilai = PenilaianKaryawan::where(
            'periode_tahun',
            $tahun
        )->pluck('karyawan_id')->toArray();

        return view('pimpinan.penilaian.create', compact(
            'karyawan',
            'tahun',
            'tahunList',
            'sudahDinilai',
            'nilaiKehadiranMap'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'karyawan_id' => 'required|exists:karyawan,id',
            'periode_tahun' => 'required|integer|min:2020',
            'nilai_kedisiplinan' => 'required|numeric|min:0|max:100',
            'nilai_kinerja' => 'required|numeric|min:0|max:100',
            'catatan' => 'nullable|string|max:1000',
        ]);

        $existing = PenilaianKaryawan::where(
            'karyawan_id',
            $request->karyawan_id
        )
            ->where(
                'periode_tahun',
                $request->periode_tahun
            )
            ->first();

        if ($existing) {
            return back()
                ->withErrors([
                    'error' => 'Karyawan sudah dinilai pada tahun tersebut.'
                ])
                ->withInput();
        }

        $nilaiKehadiran = $this->hitungNilaiKehadiran(
            (int) $request->karyawan_id,
            (int) $request->periode_tahun
        );

        $nilaiTotal = PenilaianKaryawan::hitungNilaiTotal(
            $nilaiKehadiran,
            $request->nilai_kedisiplinan,
            $request->nilai_kinerja
        );

        PenilaianKaryawan::create([
            'karyawan_id' => $request->karyawan_id,
            'penilaian_oleh' => Auth::user()->id,
            'periode_tahun' => $request->periode_tahun,
            'nilai_kehadiran' => $nilaiKehadiran,
            'nilai_kedisiplinan' => $request->nilai_kedisiplinan,
            'nilai_kinerja' => $request->nilai_kinerja,
            'nilai_total' => $nilaiTotal,
            'grade' => PenilaianKaryawan::hitungGrade($nilaiTotal),
            'catatan' => $request->catatan,
        ]);

        return redirect()
            ->route('pimpinan.penilaian.index')
            ->with('success', 'Penilaian berhasil disimpan.');
    }

    public function show($id)
    {
        $penilaian = PenilaianKaryawan::with([
            'karyawan.jabatan',
            'penilai'
        ])->findOrFail($id);

        $absensiPeriode = Absensi::where(
            'karyawan_id',
            $penilaian->karyawan_id
        )
            ->whereYear(
                'tanggal',
                $penilaian->periode_tahun
            )
            ->get();

        $rekapAbsensi = [
            'hadir' => $absensiPeriode->where('status', 'hadir')->count(),
            'terlambat' => $absensiPeriode->where('status', 'terlambat')->count(),
            'izin' => $absensiPeriode->where('status', 'izin')->count(),
            'alpha' => $absensiPeriode->where('status', 'alpha')->count(),
        ];

        return view(
            'pimpinan.penilaian.show',
            compact(
                'penilaian',
                'rekapAbsensi'
            )
        );
    }

    public function edit($id)
    {
        $penilaian = PenilaianKaryawan::with(
            'karyawan.jabatan'
        )->findOrFail($id);

        $tahunList = range(
            Carbon::now()->year,
            Carbon::now()->year - 5
        );

        $nilaiKehadiran = $this->hitungNilaiKehadiran(
            $penilaian->karyawan_id,
            $penilaian->periode_tahun
        );

        return view(
            'pimpinan.penilaian.edit',
            compact(
                'penilaian',
                'tahunList',
                'nilaiKehadiran'
            )
        );
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nilai_kedisiplinan' => 'required|numeric|min:0|max:100',
            'nilai_kinerja' => 'required|numeric|min:0|max:100',
            'catatan' => 'nullable|string|max:1000',
        ]);

        $penilaian = PenilaianKaryawan::findOrFail($id);

        $nilaiKehadiran = $this->hitungNilaiKehadiran(
            $penilaian->karyawan_id,
            $penilaian->periode_tahun
        );

        $nilaiTotal = PenilaianKaryawan::hitungNilaiTotal(
            $nilaiKehadiran,
            $request->nilai_kedisiplinan,
            $request->nilai_kinerja
        );

        $penilaian->update([
            'nilai_kehadiran' => $nilaiKehadiran,
            'nilai_kedisiplinan' => $request->nilai_kedisiplinan,
            'nilai_kinerja' => $request->nilai_kinerja,
            'nilai_total' => $nilaiTotal,
            'grade' => PenilaianKaryawan::hitungGrade($nilaiTotal),
            'catatan' => $request->catatan,
        ]);

        return redirect()
            ->route('pimpinan.penilaian.index')
            ->with(
                'success',
                'Penilaian berhasil diperbarui.'
            );
    }

    public function destroy($id)
    {
        PenilaianKaryawan::findOrFail($id)->delete();

        return back()->with(
            'success',
            'Penilaian berhasil dihapus.'
        );
    }

    private function hitungNilaiKehadiran(
        int $karyawanId,
        int $tahun
    ): float {

        $karyawan = Karyawan::find($karyawanId);

        if (!$karyawan) {
            return 0;
        }

        $today = Carbon::today();
        $tglMasuk = Carbon::parse($karyawan->tgl_masuk);
        //periode awal
        if ($tahun < $tglMasuk->year) {
            return 0;
        }

        if ($tahun == $tglMasuk->year) {
            $tanggalMulai = $tglMasuk->copy();
        } else {
            $tanggalMulai = Carbon::create($tahun, 1, 1);
        }

        //periode akhir
        if ($tahun == $today->year) {
            $tanggalAkhir = $today;
        } else {
            $tanggalAkhir = Carbon::create($tahun, 12, 31);
        }

        $totalHari = $tanggalMulai->diffInDays($tanggalAkhir) + 1;

        if ($totalHari <= 0) {
            return 0;
        }

        $hadir = Absensi::where('karyawan_id', $karyawanId)
            ->whereYear('tanggal', $tahun)
            ->whereIn('status', ['hadir', 'terlambat'])
            ->count();

        return round(
            ($hadir / $totalHari) * 100,
            2
        );
    }

    private function buatMapNilaiKehadiran(
        array $karyawanIds,
        array $tahunList
    ): array {
        $map = [];

        foreach ($karyawanIds as $karyawanId) {
            foreach ($tahunList as $tahun) {

                $map[$karyawanId][$tahun] =
                    $this->hitungNilaiKehadiran(
                        $karyawanId,
                        $tahun
                    );
            }
        }

        return $map;
    }
}
