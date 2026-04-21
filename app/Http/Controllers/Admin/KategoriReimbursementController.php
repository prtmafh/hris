<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KategoriReimbursement;
use Illuminate\Http\Request;

class KategoriReimbursementController extends Controller
{
    public function index()
    {
        $data = KategoriReimbursement::orderBy('nama')->get();

        return view('admin.referensi.kategori_reimbursement.index', compact('data'));
    }

    public function store(Request $request)
    {
        $validated = $this->validateKategori($request);
        $validated['perlu_bukti'] = $request->boolean('perlu_bukti');

        KategoriReimbursement::create($validated);

        return back()->with('success', 'Kategori reimbursement berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $kategori = KategoriReimbursement::findOrFail($id);

        $validated = $this->validateKategori($request);
        $validated['perlu_bukti'] = $request->boolean('perlu_bukti');

        $kategori->update($validated);

        return back()->with('success', 'Kategori reimbursement berhasil diperbarui.');
    }

    public function toggle($id)
    {
        $kategori = KategoriReimbursement::findOrFail($id);
        $kategori->update([
            'status' => $kategori->status === 'aktif' ? 'nonaktif' : 'aktif',
        ]);

        return back()->with('success', 'Status kategori reimbursement berhasil diubah.');
    }

    public function destroy($id)
    {
        $kategori = KategoriReimbursement::findOrFail($id);

        if ($kategori->reimbursement()->exists()) {
            return back()->with('error', 'Kategori tidak dapat dihapus karena sudah digunakan pada reimbursement.');
        }

        $kategori->delete();

        return back()->with('success', 'Kategori reimbursement berhasil dihapus.');
    }

    private function validateKategori(Request $request): array
    {
        return $request->validate([
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string|max:1000',
            'plafon_per_bulan' => 'nullable|numeric|min:0',
            'plafon_per_pengajuan' => 'nullable|numeric|min:0',
            'status' => 'required|in:aktif,nonaktif',
        ]);
    }
}
