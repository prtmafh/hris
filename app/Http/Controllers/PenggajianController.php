<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use App\Models\Penggajian;
use Illuminate\Http\Request;

class PenggajianController extends Controller
{
    public function data_gaji(Request $request)
    {
        $query = Penggajian::with('karyawan');

        // FILTER BULAN
        if ($request->filled('bulan')) {
            $query->where('periode_bulan', $request->bulan);
        }

        // FILTER TAHUN
        if ($request->filled('tahun')) {
            $query->where('periode_tahun', $request->tahun);
        }

        // FILTER KARYAWAN
        if ($request->filled('karyawan_id')) {
            $query->where('karyawan_id', $request->karyawan_id);
        }

        // FILTER STATUS
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $penggajian = $query->latest()->paginate(10);
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
}
