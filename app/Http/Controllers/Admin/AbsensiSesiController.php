<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AbsensiSesi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AbsensiSesiController extends Controller
{
    public function show($id)
    {
        $sesi = AbsensiSesi::with(['absensi.karyawan.jabatan'])->findOrFail($id);
        return view('admin.absensi.show-sesi', compact('sesi'));
    }

    public function edit($id)
    {
        $sesi = AbsensiSesi::with(['absensi.karyawan'])->findOrFail($id);
        return view('admin.absensi.edit-sesi', compact('sesi'));
    }

    public function update(Request $request, $id)
    {
        $sesi = AbsensiSesi::findOrFail($id);

        $request->validate([
            'jam_checkin'  => 'nullable|date_format:H:i',
            'jam_checkout' => 'nullable|date_format:H:i',
            'status'       => 'required|in:hadir,izin,alpha,terlambat',
            'keterangan'   => 'nullable|string',
        ]);

        $sesi->update([
            'jam_checkin'  => $request->jam_checkin,
            'jam_checkout' => $request->jam_checkout,
            'status'       => $request->status,
            'keterangan'   => $request->keterangan,
        ]);

        return redirect()->route('data_absen', ['type' => 'sesi'])->with('success', 'Data absensi sesi berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $sesi = AbsensiSesi::findOrFail($id);

        if ($sesi->foto_masuk) {
            Storage::disk('public')->delete($sesi->foto_masuk);
        }
        if ($sesi->foto_keluar) {
            Storage::disk('public')->delete($sesi->foto_keluar);
        }

        $sesi->delete();

        return redirect()->route('data_absen', ['type' => 'sesi'])->with('success', 'Data absensi sesi berhasil dihapus.');
    }
}
