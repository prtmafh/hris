<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Karyawan;
use App\Models\Lembur;
use Illuminate\Http\Request;

class LemburController extends Controller
{
    public function index(Request $request)
    {
        $query = Lembur::with('karyawan');

        if ($request->filled('tanggal')) {
            $query->whereDate('tanggal', $request->tanggal);
        }

        if ($request->filled('karyawan_id')) {
            $query->where('karyawan_id', $request->karyawan_id);
        }

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

        $lembur->update(['status' => 'disetujui']);

        return back()->with('success', 'Pengajuan lembur disetujui.');
    }

    public function rejectLembur($id)
    {
        $lembur = Lembur::findOrFail($id);
        $lembur->update([
            'status' => 'ditolak'
        ]);

        return redirect()->back()->with('success', 'Pengajuan Lembur ditolak');
    }
}
