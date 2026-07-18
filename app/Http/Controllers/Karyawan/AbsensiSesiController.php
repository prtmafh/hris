<?php

namespace App\Http\Controllers\Karyawan;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\AbsensiSesi;
use App\Models\Karyawan;
use App\Models\Pengaturan;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AbsensiSesiController extends Controller
{
    public function show($id)
    {
        $sesi = AbsensiSesi::with(['absensi.karyawan.jabatan'])->findOrFail($id);
        return view('karyawan.absensi.show-sesi', compact('sesi'));
    }

    public function edit($id)
    {
        $sesi = AbsensiSesi::with(['absensi.karyawan'])->findOrFail($id);
        return view('karyawan.absensi.edit-sesi', compact('sesi'));
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

        return redirect()->route('data_absenKaryawan', ['type' => 'sesi'])->with('success', 'Data absensi sesi berhasil diperbarui.');
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

        return redirect()->route('data_absenKaryawan', ['type' => 'sesi'])->with('success', 'Data absensi sesi berhasil dihapus.');
    }

    public function create()
    {
        $karyawanList = Karyawan::where('status', 'aktif')
            ->where('status_gaji', 'harian')
            ->orderBy('nama')
            ->get();
        $maxSesi = (int) Pengaturan::getValue('max_sesi_harian', 3);

        return view('karyawan.absensi.create-sesi', compact('karyawanList', 'maxSesi'));
    }

    public function store(Request $request)
    {
        $maxSesi = (int) Pengaturan::getValue('max_sesi_harian', 3);
        $validated = $request->validate([
            'karyawan_id' => [
                'required',
                Rule::exists('karyawan', 'id')->where(fn($query) => $query
                    ->where('status', 'aktif')
                    ->where('status_gaji', 'harian')),
            ],
            'tanggal' => 'required|date',
            'sesi_ke' => ['required', 'integer', 'between:1,' . $maxSesi],
            'jam_checkin' => 'nullable|date_format:H:i|required_with:jam_checkout',
            'jam_checkout' => 'nullable|date_format:H:i|after_or_equal:jam_checkin',
            'status' => 'required|in:hadir,izin,alpha,terlambat',
            'keterangan' => 'nullable|string|max:1000',
        ], [
            'karyawan_id.exists' => 'Karyawan harian tidak ditemukan atau sudah tidak aktif.',
            'sesi_ke.between' => "Nomor sesi harus antara 1 sampai {$maxSesi}.",
            'jam_checkin.required_with' => 'Jam check-in harus diisi jika jam check-out diisi.',
            'jam_checkout.after_or_equal' => 'Jam check-out harus sama dengan atau setelah jam check-in.',
        ]);

        $duplicate = AbsensiSesi::whereHas('absensi', fn($query) => $query
            ->where('karyawan_id', $validated['karyawan_id'])
            ->whereDate('tanggal', $validated['tanggal']))
            ->where('sesi_ke', $validated['sesi_ke'])
            ->exists();

        if ($duplicate) {
            return back()->withInput()->with('error', 'Absensi untuk karyawan, tanggal, dan sesi tersebut sudah ada.');
        }

        DB::transaction(function () use ($validated) {
            $absensi = Absensi::firstOrCreate(
                [
                    'karyawan_id' => $validated['karyawan_id'],
                    'tanggal' => $validated['tanggal'],
                ],
                ['status' => $validated['status']]
            );

            AbsensiSesi::create([
                'absensi_id' => $absensi->id,
                'sesi_ke' => $validated['sesi_ke'],
                'jam_checkin' => $validated['jam_checkin'] ?? null,
                'jam_checkout' => $validated['jam_checkout'] ?? null,
                'status' => $validated['status'],
                'keterangan' => $validated['keterangan'] ?? null,
            ]);

            $sesi = $absensi->sesi()->get();
            $status = $sesi->contains('status', 'hadir') ? 'hadir'
                : ($sesi->contains('status', 'terlambat') ? 'terlambat'
                    : ($sesi->contains('status', 'izin') ? 'izin' : 'alpha'));

            $absensi->update([
                'jam_masuk' => $sesi->whereNotNull('jam_checkin')->min('jam_checkin'),
                'jam_keluar' => $sesi->whereNotNull('jam_checkout')->max('jam_checkout'),
                'status' => $status,
            ]);
        });

        return redirect()->route('data_absenKaryawan', ['type' => 'sesi'])
            ->with('success', 'Absensi sesi manual berhasil ditambahkan.');
    }
}
