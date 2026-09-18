<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Perusahaan;
use App\Models\Karyawan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class PerusahaanController extends Controller
{
    public function index()
    {
        $perusahaan = Perusahaan::withCount('karyawan')->orderBy('nama_perusahaan')->get();
        $karyawanList = Karyawan::with(['jabatan', 'perusahaan'])
            ->whereHas('role', fn($query) => $query->whereIn('nama_role', ['karyawan', 'admin_kecil', 'admin', 'pimpinan']))
            ->orderBy('nama')
            ->get();
        return view('admin.perusahaan.index', compact('perusahaan', 'karyawanList'));
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        if ($request->hasFile('logo')) $data['logo'] = $request->file('logo')->store('perusahaan', 'public');
        Perusahaan::create($data);
        return redirect()->route('admin.perusahaan.index')->with('success', 'Data perusahaan berhasil ditambahkan.');
    }

    public function show(Perusahaan $perusahaan)
    {
        $perusahaan->load(['karyawan' => fn($q) => $q->with('jabatan')->orderBy('nama')]);
        return view('admin.perusahaan.show', compact('perusahaan'));
    }

    public function update(Request $request, Perusahaan $perusahaan)
    {
        $data = $this->validated($request, $perusahaan);
        if ($request->hasFile('logo')) {
            if ($perusahaan->logo) Storage::disk('public')->delete($perusahaan->logo);
            $data['logo'] = $request->file('logo')->store('perusahaan', 'public');
        }
        $perusahaan->update($data);
        return redirect()->route('admin.perusahaan.index')->with('success', 'Data perusahaan berhasil diperbarui.');
    }

    public function destroy(Perusahaan $perusahaan)
    {
        if ($perusahaan->karyawan()->exists()) {
            return back()->with('error', 'Perusahaan tidak dapat dihapus karena masih memiliki karyawan. Pindahkan karyawan terlebih dahulu atau nonaktifkan perusahaan.');
        }
        if ($perusahaan->logo) Storage::disk('public')->delete($perusahaan->logo);
        $perusahaan->delete();
        return redirect()->route('admin.perusahaan.index')->with('success', 'Data perusahaan berhasil dihapus.');
    }

    public function assignKaryawan(Request $request, Perusahaan $perusahaan)
    {
        if ($perusahaan->status !== 'aktif') {
            return back()->with('error', 'Karyawan hanya dapat ditempatkan pada perusahaan aktif.');
        }

        $data = $request->validate([
            'karyawan_ids' => 'nullable|array',
            'karyawan_ids.*' => 'required|exists:karyawan,id',
        ]);

        $karyawanIds = $data['karyawan_ids'] ?? [];
        $karyawanQuery = Karyawan::whereHas('role', fn($query) => $query->whereIn('nama_role', ['karyawan', 'admin_kecil', 'admin', 'pimpinan']));

        (clone $karyawanQuery)
            ->where('perusahaan_id', $perusahaan->id)
            ->when(! empty($karyawanIds), fn($query) => $query->whereNotIn('id', $karyawanIds))
            ->update(['perusahaan_id' => null]);

        Karyawan::whereIn('id', $karyawanIds)
            ->whereHas('role', fn($query) => $query->whereIn('nama_role', ['karyawan', 'admin_kecil', 'admin', 'pimpinan']))
            ->update(['perusahaan_id' => $perusahaan->id]);

        return redirect()->route('admin.perusahaan.index')
            ->with('success', count($karyawanIds) . ' karyawan tersimpan pada ' . $perusahaan->nama_perusahaan . '.');
    }

    private function validated(Request $request, ?Perusahaan $perusahaan = null): array
    {
        return $request->validate([
            'nama_perusahaan' => 'required|string|max:150',
            'nama_singkat' => 'nullable|string|max:50',
            'nib' => ['required', 'string', 'max:50', Rule::unique('perusahaan', 'nib')->ignore($perusahaan)],
            'npwp' => 'nullable|string|max:50',
            'alamat' => 'nullable|string',
            'no_telepon' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:100',
            'website' => 'nullable|url|max:255',
            'logo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'deskripsi' => 'nullable|string',
            'status' => 'required|in:aktif,nonaktif',
        ]);
    }
}
