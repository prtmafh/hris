<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Jabatan;
use App\Models\Lowongan;
use Illuminate\Http\Request;

class LowonganController extends Controller
{
    public function index()
    {
        $lowongan = Lowongan::with(['jabatan'])
            ->withCount('pelamar')
            ->orderBy('tanggal_buka', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        $jabatan = Jabatan::orderBy('nama_jabatan')->get();

        return view('admin.lowongan.index', compact('lowongan', 'jabatan'));
    }

    public function store(Request $request)
    {
        Lowongan::create($this->validateLowongan($request));

        return redirect()->route('admin.lowongan')->with('success', 'Lowongan berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $lowongan = Lowongan::findOrFail($id);
        $lowongan->update($this->validateLowongan($request));

        return redirect()->route('admin.lowongan')->with('success', 'Lowongan berhasil diperbarui.');
    }

    public function toggle($id)
    {
        $lowongan = Lowongan::findOrFail($id);
        $lowongan->update([
            'status' => $lowongan->status === 'aktif' ? 'ditutup' : 'aktif',
        ]);

        return redirect()->route('admin.lowongan')->with('success', 'Status lowongan berhasil diubah.');
    }

    public function destroy($id)
    {
        $lowongan = Lowongan::findOrFail($id);

        if ($lowongan->pelamar()->exists()) {
            return redirect()->route('admin.lowongan')
                ->with('error', 'Lowongan tidak dapat dihapus karena sudah memiliki data pelamar.');
        }

        $lowongan->delete();

        return redirect()->route('admin.lowongan')->with('success', 'Lowongan berhasil dihapus.');
    }

    private function validateLowongan(Request $request): array
    {
        return $request->validate([
            'jabatan_id' => 'required|exists:jabatan,id',
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'kualifikasi' => 'required|string',
            'tanggung_jawab' => 'nullable|string',
            'kuota' => 'required|integer|min:1',
            'tanggal_buka' => 'required|date',
            'tanggal_tutup' => 'required|date|after_or_equal:tanggal_buka',
            'status' => 'required|in:draft,aktif,ditutup',
        ]);
    }
}
