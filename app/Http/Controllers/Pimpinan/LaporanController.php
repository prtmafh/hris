<?php

namespace App\Http\Controllers\Pimpinan;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\Izin;
use App\Models\Jabatan;
use App\Models\Karyawan;
use App\Models\Lembur;
use App\Models\Penggajian;
use App\Models\Reimbursement;
use Carbon\Carbon;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function absensi(Request $request)
    {
        $bulan      = $request->input('bulan', Carbon::now()->month);
        $tahun      = $request->input('tahun', Carbon::now()->year);
        $jabatanId  = $request->input('jabatan_id');
        $karyawanId = $request->input('karyawan_id');

        $query = Absensi::with('karyawan.jabatan')
            ->whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun);

        if ($jabatanId) {
            $query->whereHas('karyawan', fn($q) => $q->where('jabatan_id', $jabatanId));
        }
        if ($karyawanId) {
            $query->where('karyawan_id', $karyawanId);
        }

        $absensi  = $query->orderBy('tanggal', 'desc')->get();
        $jabatan  = Jabatan::orderBy('nama_jabatan')->get();
        $karyawan = Karyawan::where('status', 'aktif')->orderBy('nama')->get();

        $rekap = [
            'hadir'     => $absensi->where('status', 'hadir')->count(),
            'terlambat' => $absensi->where('status', 'terlambat')->count(),
            'izin'      => $absensi->where('status', 'izin')->count(),
            'alpha'     => $absensi->where('status', 'alpha')->count(),
        ];

        $tahunList = range(Carbon::now()->year, Carbon::now()->year - 3);

        return view('pimpinan.laporan.absensi', compact(
            'absensi', 'jabatan', 'karyawan', 'rekap',
            'bulan', 'tahun', 'jabatanId', 'karyawanId', 'tahunList'
        ));
    }

    public function penggajian(Request $request)
    {
        $bulan = $request->input('bulan', Carbon::now()->month);
        $tahun = $request->input('tahun', Carbon::now()->year);

        $penggajian = Penggajian::with('karyawan.jabatan')
            ->where('periode_bulan', $bulan)
            ->where('periode_tahun', $tahun)
            ->orderBy('total_gaji', 'desc')
            ->get();

        $totalGaji  = $penggajian->sum('total_gaji');
        $sudahBayar = $penggajian->where('status', 'dibayar')->count();
        $belumBayar = $penggajian->where('status', 'proses')->count();

        $tahunList = range(Carbon::now()->year, Carbon::now()->year - 3);

        return view('pimpinan.laporan.penggajian', compact(
            'penggajian', 'bulan', 'tahun', 'totalGaji',
            'sudahBayar', 'belumBayar', 'tahunList'
        ));
    }

    public function izin(Request $request)
    {
        $bulan  = $request->input('bulan', Carbon::now()->month);
        $tahun  = $request->input('tahun', Carbon::now()->year);
        $status = $request->input('status');

        $query = Izin::with('karyawan.jabatan')
            ->whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun);

        if ($status) {
            $query->where('status_approval', $status);
        }

        $izin = $query->orderBy('tanggal', 'desc')->get();

        $rekap = [
            'pending'   => $izin->where('status_approval', 'pending')->count(),
            'disetujui' => $izin->where('status_approval', 'disetujui')->count(),
            'ditolak'   => $izin->where('status_approval', 'ditolak')->count(),
        ];

        $tahunList = range(Carbon::now()->year, Carbon::now()->year - 3);

        return view('pimpinan.laporan.izin', compact(
            'izin', 'bulan', 'tahun', 'status', 'rekap', 'tahunList'
        ));
    }

    public function lembur(Request $request)
    {
        $bulan  = $request->input('bulan', Carbon::now()->month);
        $tahun  = $request->input('tahun', Carbon::now()->year);
        $status = $request->input('status');

        $query = Lembur::with('karyawan.jabatan')
            ->whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun);

        if ($status) {
            $query->where('status', $status);
        }

        $lembur = $query->orderBy('tanggal', 'desc')->get();

        $rekap = [
            'pending'     => $lembur->where('status', 'pending')->count(),
            'disetujui'   => $lembur->where('status', 'disetujui')->count(),
            'ditolak'     => $lembur->where('status', 'ditolak')->count(),
            'total_jam'   => $lembur->where('status', 'disetujui')->sum('total_jam'),
            'total_upah'  => $lembur->where('status', 'disetujui')->sum('total_upah'),
        ];

        $tahunList = range(Carbon::now()->year, Carbon::now()->year - 3);

        return view('pimpinan.laporan.lembur', compact(
            'lembur', 'bulan', 'tahun', 'status', 'rekap', 'tahunList'
        ));
    }

    public function reimbursement(Request $request)
    {
        $bulan  = $request->input('bulan', Carbon::now()->month);
        $tahun  = $request->input('tahun', Carbon::now()->year);
        $status = $request->input('status');

        $query = Reimbursement::with(['karyawan.jabatan', 'kategori'])
            ->whereMonth('tanggal_pengajuan', $bulan)
            ->whereYear('tanggal_pengajuan', $tahun);

        if ($status) {
            $query->where('status', $status);
        }

        $reimbursement = $query->orderBy('tanggal_pengajuan', 'desc')->get();

        $rekap = [
            'pending'   => $reimbursement->where('status', 'pending')->count(),
            'disetujui' => $reimbursement->where('status', 'disetujui')->count(),
            'ditolak'   => $reimbursement->where('status', 'ditolak')->count(),
            'dibayar'   => $reimbursement->where('status', 'dibayar')->count(),
            'total_diajukan'  => $reimbursement->sum('jumlah_diajukan'),
            'total_disetujui' => $reimbursement->whereIn('status', ['disetujui', 'dibayar'])->sum('jumlah_disetujui'),
        ];

        $tahunList = range(Carbon::now()->year, Carbon::now()->year - 3);

        return view('pimpinan.laporan.reimbursement', compact(
            'reimbursement', 'bulan', 'tahun', 'status', 'rekap', 'tahunList'
        ));
    }
}
