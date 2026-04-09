<?php

namespace App\Http\Controllers;

use App\Models\Izin;
use App\Models\Karyawan;
use App\Models\Lembur;
use Illuminate\Http\Request;

class PengajuanController extends Controller
{
    public function izin(Request $request)
    {
        $query = Izin::with('karyawan');

        if ($request->tanggal) {
            $query->whereDate('tanggal', $request->tanggal);
        }

        if ($request->status) {
            $query->where('status_approval', $request->status);
        }

        if ($request->karyawan_id) {
            $query->where('karyawan_id', $request->karyawan_id);
        }

        $izin = $query->latest()->paginate(10);
        $karyawanList = Karyawan::all();

        return view('admin.pengajuan.izin', compact('izin', 'karyawanList'));
    }

    public function lembur(Request $request)
    {
        $query = Lembur::with('karyawan');

        // FILTER TANGGAL
        if ($request->filled('tanggal')) {
            $query->whereDate('tanggal', $request->tanggal);
        }

        // FILTER KARYAWAN
        if ($request->filled('karyawan_id')) {
            $query->where('karyawan_id', $request->karyawan_id);
        }

        // FILTER STATUS
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $lembur = $query->latest()->paginate(10);
        $karyawanList = Karyawan::orderBy('nama')->get();
        $hasFilter = $request->filled('tanggal')
            || $request->filled('karyawan_id')
            || $request->filled('status');

        return view('admin.pengajuan.lembur', compact('lembur', 'karyawanList', 'hasFilter'));
    }

    public function approveLembur($id)
    {
        $lembur = Lembur::findOrFail($id);
        $lembur->update([
            'status' => 'disetujui'
        ]);

        return redirect()->back()->with('success', 'Lembur disetujui');
    }

    public function rejectLembur($id)
    {
        $lembur = Lembur::findOrFail($id);
        $lembur->update([
            'status' => 'ditolak'
        ]);

        return redirect()->back()->with('success', 'Lembur ditolak');
    }
}
