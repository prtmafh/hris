<?php

namespace App\Http\Controllers\Karyawan;

use App\Http\Controllers\Controller;
use App\Models\Penggajian;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SlipGajiController extends Controller
{
    public function index(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();
        $karyawan = $user->karyawan()->firstOrFail();

        $tahun = $request->get('tahun', Carbon::now()->year);

        $penggajian = Penggajian::where('karyawan_id', $karyawan->id)
            ->whereYear('tgl_dibayar', $tahun)
            ->orderByDesc('periode_tahun')
            ->orderByDesc('periode_bulan')
            ->get();

        $daftarTahun = range(Carbon::now()->year, Carbon::now()->year - 3);

        return view('karyawan.slip-gaji', compact('penggajian', 'tahun', 'daftarTahun', 'karyawan'));
    }
    public function showSlip(int $id)
    {
        /** @var User $user */
        $user       = Auth::user();
        $karyawan   = $user->karyawan()->firstOrFail();
        $penggajian = Penggajian::with(['details', 'karyawan.jabatan'])
            ->where('karyawan_id', $karyawan->id)
            ->findOrFail($id);

        return view('karyawan.slip-gaji-detail', compact('penggajian'));
    }

    public function downloadSlipPdf(int $id)
    {
        /** @var User $user */
        $user       = Auth::user();
        $karyawan   = $user->karyawan()->firstOrFail();
        $penggajian = Penggajian::with(['details', 'karyawan.jabatan'])
            ->where('karyawan_id', $karyawan->id)
            ->findOrFail($id);

        $namaBulan = [
            '',
            'Januari',
            'Februari',
            'Maret',
            'April',
            'Mei',
            'Juni',
            'Juli',
            'Agustus',
            'September',
            'Oktober',
            'November',
            'Desember'
        ];

        $pdf = Pdf::loadView('pdf.slip-gaji', compact('penggajian', 'namaBulan'))
            ->setPaper('a4', 'portrait');

        $filename = 'slip-gaji-' . $penggajian->karyawan->nik . '-'
            . $namaBulan[$penggajian->periode_bulan] . '-'
            . $penggajian->periode_tahun . '.pdf';

        return $pdf->download($filename);
    }
}
