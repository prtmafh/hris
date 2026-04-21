<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Izin;
use App\Models\Karyawan;
use Illuminate\Http\Request;

class IzinController extends Controller
{
    public function index(Request $request)
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

    public function approve($id)
    {
        $izin = Izin::findOrFail($id);
        $izin->update([
            'status_approval' => 'disetujui',
        ]);

        return redirect()->back()->with('success', 'Pengajuan izin berhasil disetujui.');
    }

    public function reject($id)
    {
        $izin = Izin::findOrFail($id);
        $izin->update([
            'status_approval' => 'ditolak',
        ]);

        return redirect()->back()->with('success', 'Pengajuan izin berhasil ditolak.');
    }
}
