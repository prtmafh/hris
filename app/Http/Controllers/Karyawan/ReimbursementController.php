<?php

namespace App\Http\Controllers\Karyawan;

use App\Http\Controllers\Controller;
use App\Models\KategoriReimbursement;
use App\Models\Reimbursement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ReimbursementController extends Controller
{
    public function index()
    {
        /** @var \App\Models\Karyawan $user */
        $karyawan = Auth::user();
        // $karyawan = $user->karyawan()->firstOrFail();
        $kategoriList = KategoriReimbursement::where('status', 'aktif')->orderBy('nama')->get();
        $reimbursement = Reimbursement::with(['kategoriReimbursement', 'penyetuju', 'buktiFile'])
            ->where('karyawan_id', $karyawan->id)
            ->orderByDesc('tanggal_pengajuan')
            ->orderByDesc('created_at')
            ->get();

        return view('karyawan.reimbursement-saya', compact('reimbursement', 'kategoriList'));
    }

    public function store(Request $request)
    {
        /** @var \App\Models\Karyawan $karyawan */
        $karyawan = Auth::user();

        $validated = $request->validate([
            'kategori_reimbursement_id' => 'required|exists:kategori_reimbursement,id',
            'tanggal_transaksi' => 'required|date',
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string|max:1000',
            'jumlah_diajukan' => 'required|numeric|min:1',

            'bukti' => 'nullable|array',
            'bukti.*' => 'file|mimes:jpg,jpeg,png,pdf',
        ]);

        $kategori = KategoriReimbursement::where('status', 'aktif')
            ->findOrFail($validated['kategori_reimbursement_id']);

        if ($kategori->perlu_bukti && !$request->hasFile('bukti')) {
            return back()
                ->withErrors([
                    'bukti' => 'Bukti wajib diunggah untuk kategori ini.'
                ])
                ->withInput();
        }

        $validated['karyawan_id'] = $karyawan->id;
        $validated['tanggal_pengajuan'] = now()->toDateString();
        $validated['status'] = 'pending';

        // Kolom bukti tidak lagi disimpan di tabel reimbursement
        unset($validated['bukti']);

        $reimbursement = Reimbursement::create($validated);

        // Simpan semua file bukti
        if ($request->hasFile('bukti')) {

            foreach ($request->file('bukti') as $file) {

                $path = $file->store('reimbursement', 'public');

                $reimbursement->buktiFile()->create([
                    'file' => $path,
                ]);
            }
        }

        return back()->with('success', 'Pengajuan reimbursement berhasil dikirim.');
    }

    public function destroy($id)
    {
        /** @var \App\Models\Karyawan $user */
        $karyawan = Auth::user();
        // $karyawan = $user->karyawan()->firstOrFail();
        $reimbursement = Reimbursement::where('karyawan_id', $karyawan->id)->findOrFail($id);

        if ($reimbursement->status !== 'pending') {
            return back()->with('error', 'Hanya pengajuan pending yang dapat dihapus.');
        }

        if ($reimbursement->bukti) {
            Storage::disk('public')->delete($reimbursement->bukti);
        }

        $reimbursement->delete();

        return back()->with('success', 'Pengajuan reimbursement berhasil dihapus.');
    }
}
