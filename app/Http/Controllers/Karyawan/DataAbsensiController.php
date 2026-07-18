<?php

namespace App\Http\Controllers\Karyawan;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\Karyawan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;
use App\Exports\AbsensiExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\AbsensiSesi;

class DataAbsensiController extends Controller
{
    public function index(Request $request)
    {
        $type = $request->get('type', 'biasa');
        $bulan = (int) $request->input('bulan', now()->month);
        $tahun = (int) $request->input('tahun', now()->year);
        $daftarTahun = range(Carbon::now()->year, Carbon::now()->year - 3);

        if ($type === 'sesi') {

            $absensi = $this->buildFilteredQuerySesi($request)
                // ->whereMonth('tanggal', $bulan)
                // ->whereYear('tanggal', $tahun)

                ->orderByDesc('absensi_id')
                ->get();
        } else {

            $absensi = $this->buildFilteredQuery($request)
                ->whereMonth('tanggal', $bulan)
                ->whereYear('tanggal', $tahun)
                ->orderByDesc('tanggal')
                ->orderByDesc('id')
                ->get();
        }

        $karyawanList = Karyawan::orderBy('nama')->get();

        return view('karyawan.absensi.index', compact(
            'absensi',
            'karyawanList',
            'bulan',
            'tahun',
            'daftarTahun',
        ));
    }

    public function export(Request $request)
    {
        return Excel::download(
            new AbsensiExport($request),
            'data-absensi.xlsx'
        );
    }

    public function create()
    {
        $karyawanList = Karyawan::where('status', 'aktif')->orderBy('nama')->get();
        return view('karyawan.absensi.create', compact('karyawanList'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'karyawan_id'  => 'required|exists:karyawan,id',
            'tanggal'      => 'required|date',
            'jam_masuk'    => 'nullable|date_format:H:i',
            'jam_keluar'   => 'nullable|date_format:H:i',
            'status'       => 'required|in:hadir,izin,alpha,terlambat',
        ]);

        $exists = Absensi::where('karyawan_id', $request->karyawan_id)
            ->whereDate('tanggal', $request->tanggal)
            ->exists();

        if ($exists) {
            return back()->withInput()->with('error', 'Data absensi karyawan ini pada tanggal tersebut sudah ada.');
        }

        Absensi::create([
            'karyawan_id' => $request->karyawan_id,
            'tanggal'     => $request->tanggal,
            'jam_masuk'   => $request->jam_masuk,
            'jam_keluar'  => $request->jam_keluar,
            'status'      => $request->status,
        ]);

        return redirect()->route('data_absenKaryawan')->with('success', 'Data absensi berhasil ditambahkan.');
    }

    public function show($id)
    {
        $absensi = Absensi::with(['karyawan.jabatan'])->findOrFail($id);
        return view('karyawan.absensi.show', compact('absensi'));
    }

    public function edit($id)
    {
        $absensi = Absensi::with('karyawan')->findOrFail($id);
        return view('karyawan.absensi.edit', compact('absensi'));
    }

    public function update(Request $request, $id)
    {
        $absensi = Absensi::findOrFail($id);

        $request->validate([
            'jam_masuk'  => 'nullable|date_format:H:i',
            'jam_keluar' => 'nullable|date_format:H:i',
            'status'     => 'required|in:hadir,izin,alpha,terlambat',
        ]);

        $absensi->update([
            'jam_masuk'  => $request->jam_masuk,
            'jam_keluar' => $request->jam_keluar,
            'status'     => $request->status,
        ]);

        return redirect()->route('data_absenKaryawan')->with('success', 'Data absensi berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $absensi = Absensi::findOrFail($id);

        if ($absensi->foto_masuk) {
            Storage::disk('public')->delete($absensi->foto_masuk);
        }
        if ($absensi->foto_keluar) {
            Storage::disk('public')->delete($absensi->foto_keluar);
        }

        $absensi->delete();

        return redirect()->route('data_absenKaryawan')->with('success', 'Data absensi berhasil dihapus.');
    }

    public function rekap(Request $request)
    {
        $tahun = $request->get('tahun', now()->year);
        $bulan = $request->get('bulan');

        $karyawanList = Karyawan::with('jabatan')
            ->whereIn('role_id',  [1, 3])
            ->orderBy('nama')
            ->get();

        $rekap = $karyawanList->map(function ($k) use ($tahun, $bulan) {
            $query = Absensi::where('karyawan_id', $k->id)
                ->whereYear('tanggal', $tahun);

            if ($bulan) {
                $query->whereMonth('tanggal', $bulan);
            }

            $data = $query->get();

            $hadir     = $data->where('status', 'hadir')->count();
            $terlambat = $data->where('status', 'terlambat')->count();
            $izin      = $data->where('status', 'izin')->count();
            $alpha     = $data->where('status', 'alpha')->count();
            $total     = $data->count();

            // Hadir + Terlambat dianggap masuk kerja
            $persentase = $total > 0
                ? round((($hadir + $terlambat) / $total) * 100, 2)
                : 0;

            return [
                'karyawan'   => $k,
                'hadir'      => $hadir,
                'terlambat'  => $terlambat,
                'izin'       => $izin,
                'alpha'      => $alpha,
                'total'      => $total,
                'persentase' => $persentase,
            ];
        });

        $tahunList = range(now()->year, now()->year - 5);

        return view('karyawan.absensi.rekap', compact('rekap', 'tahun', 'bulan', 'tahunList'));
    }

    private function buildFilteredQuery(Request $request): Builder
    {
        return Absensi::with(['karyawan.jabatan'])
            ->whereDoesntHave('sesi')  // Hanya absensi yang TIDAK punya sesi
            ->when($request->filled('tanggal_dari'), function (Builder $query) use ($request) {
                $query->whereDate('tanggal', '>=', $request->tanggal_dari);
            })
            ->when($request->filled('tanggal_sampai'), function (Builder $query) use ($request) {
                $query->whereDate('tanggal', '<=', $request->tanggal_sampai);
            })
            ->when($request->filled('karyawan_id'), function (Builder $query) use ($request) {
                $query->where('karyawan_id', $request->karyawan_id);
            })
            ->when($request->filled('status'), function (Builder $query) use ($request) {
                $query->where('status', $request->status);
            })
            ->orderBy('tanggal', 'desc')
            ->orderBy('jam_masuk', 'desc');
    }

    private function buildFilteredQuerySesi(Request $request)
    {
        return AbsensiSesi::with(['absensi.karyawan.jabatan'])
            ->when($request->filled('tanggal_dari'), function ($query) use ($request) {
                $query->whereHas('absensi', fn($q) => $q->whereDate('tanggal', '>=', $request->tanggal_dari));
            })
            ->when($request->filled('tanggal_sampai'), function ($query) use ($request) {
                $query->whereHas('absensi', fn($q) => $q->whereDate('tanggal', '<=', $request->tanggal_sampai));
            })
            ->when($request->filled('karyawan_id'), function ($query) use ($request) {
                $query->whereHas('absensi', fn($q) => $q->where('karyawan_id', $request->karyawan_id));
            })
            ->when($request->filled('status'), function ($query) use ($request) {
                $query->where('status', $request->status);
            })
            ->orderByDesc('created_at');
    }
}
