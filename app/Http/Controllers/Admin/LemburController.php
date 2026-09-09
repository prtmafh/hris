<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Karyawan;
use App\Models\Lembur;
use App\Models\Pengaturan;
use Carbon\Carbon;
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

        $lembur = $query->latest()->get();
        $karyawanList = Karyawan::orderBy('nama')->get();
        $hasFilter = $request->filled('tanggal')
            || $request->filled('karyawan_id')
            || $request->filled('status');

        return view('admin.pengajuan.lembur', compact('lembur', 'karyawanList', 'hasFilter'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'karyawan_id' => 'required|exists:karyawan,id',
            'tanggal'     => 'required|date',
            'jam_mulai'   => 'required',
            'jam_selesai' => 'required|after:jam_mulai',
            'keterangan'  => 'required|string|max:500',
        ]);

        $jamMulai = Carbon::parse($validated['jam_mulai']);
        $jamSelesai = Carbon::parse($validated['jam_selesai']);

        if ($jamSelesai->lessThan($jamMulai)) {
            $jamSelesai->addDay();
        }

        Lembur::create([
            ...$validated,
            'total_jam'  => $jamMulai->floatDiffInHours($jamSelesai),
            'total_upah' => Pengaturan::getValue('tarif_lembur') ?? 100000,
            'status'     => 'pending',
        ]);

        return redirect()->route('admin.lembur')
            ->with('success', 'Pengajuan lembur berhasil ditambahkan dan menunggu persetujuan.');
    }

    public function approve($id)
    {
        $lembur = Lembur::findOrFail($id);

        $lembur->update(['status' => 'disetujui']);

        return back()->with('success', 'Pengajuan lembur disetujui.');
    }

    public function reject($id)
    {
        $lembur = Lembur::findOrFail($id);
        $lembur->update([
            'status' => 'ditolak'
        ]);

        return redirect()->back()->with('success', 'Pengajuan Lembur ditolak');
    }
}
