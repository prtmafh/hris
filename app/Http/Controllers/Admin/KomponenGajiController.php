<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Karyawan;
use App\Models\KomponenGaji;
use App\Models\KomponenGajiKaryawan;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class KomponenGajiController extends Controller
{
    public function index()
    {
        $komponen = KomponenGaji::withCount('pengaturanKaryawan')
            ->orderBy('tipe')
            ->orderBy('nama')
            ->get();

        return view('admin.komponen_gaji.index', compact('komponen'));
    }

    public function store(Request $request)
    {
        KomponenGaji::create($this->validateKomponen($request));

        return back()->with('success', 'Komponen gaji berhasil ditambahkan.');
    }

    public function update(Request $request, int $id)
    {
        $komponen = KomponenGaji::findOrFail($id);
        $komponen->update($this->validateKomponen($request));

        return back()->with('success', 'Komponen gaji berhasil diperbarui.');
    }

    public function toggle(int $id)
    {
        $komponen = KomponenGaji::findOrFail($id);
        $komponen->update([
            'status' => $komponen->status === 'aktif' ? 'nonaktif' : 'aktif',
        ]);

        return back()->with('success', 'Status komponen gaji berhasil diubah.');
    }

    public function destroy(int $id)
    {
        $komponen = KomponenGaji::findOrFail($id);

        if ($komponen->pengaturanKaryawan()->exists()) {
            return back()->with('error', 'Komponen tidak dapat dihapus karena sudah digunakan oleh karyawan.');
        }

        $komponen->delete();

        return back()->with('success', 'Komponen gaji berhasil dihapus.');
    }

    public function karyawan(Request $request)
    {
        $karyawanList = Karyawan::orderBy('nama')->get();
        $karyawan = null;
        $pengaturan = collect();

        if ($request->filled('karyawan_id')) {
            $karyawan = Karyawan::findOrFail($request->integer('karyawan_id'));
        } elseif ($karyawanList->isNotEmpty()) {
            $karyawan = $karyawanList->first();
        }

        if ($karyawan) {
            $pengaturan = KomponenGajiKaryawan::with('komponen')
                ->where('karyawan_id', $karyawan->id)
                ->get()
                ->sortBy(fn ($item) => $item->komponen->tipe.'-'.$item->komponen->nama);
        }

        $komponenTersedia = KomponenGaji::where('status', 'aktif')
            ->when($karyawan, function ($query) use ($karyawan) {
                $query->whereDoesntHave('pengaturanKaryawan', function ($subquery) use ($karyawan) {
                    $subquery->where('karyawan_id', $karyawan->id);
                });
            })
            ->orderBy('tipe')
            ->orderBy('nama')
            ->get();

        return view('admin.komponen_gaji.karyawan', compact(
            'karyawanList',
            'karyawan',
            'pengaturan',
            'komponenTersedia'
        ));
    }

    public function storeKaryawan(Request $request)
    {
        KomponenGajiKaryawan::create($this->validatePengaturan($request));

        return redirect()
            ->route('admin.komponen-gaji.karyawan', ['karyawan_id' => $request->karyawan_id])
            ->with('success', 'Komponen gaji karyawan berhasil ditambahkan.');
    }

    public function updateKaryawan(Request $request, int $id)
    {
        $pengaturan = KomponenGajiKaryawan::findOrFail($id);
        $data = $this->validatePengaturan($request, $pengaturan);
        $pengaturan->update($data);

        return redirect()
            ->route('admin.komponen-gaji.karyawan', ['karyawan_id' => $pengaturan->karyawan_id])
            ->with('success', 'Komponen gaji karyawan berhasil diperbarui.');
    }

    public function destroyKaryawan(int $id)
    {
        $pengaturan = KomponenGajiKaryawan::findOrFail($id);
        $karyawanId = $pengaturan->karyawan_id;
        $pengaturan->delete();

        return redirect()
            ->route('admin.komponen-gaji.karyawan', ['karyawan_id' => $karyawanId])
            ->with('success', 'Komponen gaji karyawan berhasil dihapus.');
    }

    private function validateKomponen(Request $request): array
    {
        return $request->validate([
            'nama' => 'required|string|max:255',
            'tipe' => 'required|in:pemasukan,potongan',
            'status' => 'required|in:aktif,nonaktif',
            'keterangan' => 'nullable|string|max:1000',
        ]);
    }

    private function validatePengaturan(
        Request $request,
        ?KomponenGajiKaryawan $pengaturan = null
    ): array {
        return $request->validate([
            'karyawan_id' => 'required|exists:karyawan,id',
            'komponen_gaji_id' => [
                'required',
                'exists:komponen_gaji,id',
                Rule::unique('komponen_gaji_karyawan')
                    ->where(fn ($query) => $query->where('karyawan_id', $request->karyawan_id))
                    ->ignore($pengaturan?->id),
            ],
            'metode' => 'required|in:nominal,persentase',
            'nilai' => 'required|numeric|min:0',
            'tanggal_mulai' => 'nullable|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
            'status' => 'required|in:aktif,nonaktif',
            'keterangan' => 'nullable|string|max:1000',
        ]);
    }
}
