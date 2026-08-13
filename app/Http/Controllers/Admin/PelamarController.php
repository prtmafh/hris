<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\UpdateProsesLamaranMail;
use App\Models\Lowongan;
use App\Models\Pelamar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class PelamarController extends Controller
{
    public function index(Request $request)
    {
        $query = Pelamar::with(['lowongan.jabatan'])
            ->orderBy('applied_at', 'desc')
            ->orderBy('created_at', 'desc');

        if ($request->filled('lowongan_id')) {
            $query->where('lowongan_id', $request->lowongan_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $pelamar = $query->get();
        $lowongan = Lowongan::orderBy('judul')->get();

        return view('admin.pelamar.index', compact('pelamar', 'lowongan'));
    }

    public function update(Request $request, $id)
    {
        $pelamar = Pelamar::findOrFail($id);

        $validated = $request->validate([
            'status' => 'required|in:pending,screening,interview,offering,diterima,ditolak',
            'jadwal_interview' => 'nullable|date|required_if:status,interview',
            'catatan_hr' => 'nullable|string|max:3000|required_if:kirim_email,1',
            'kirim_email' => 'nullable|boolean',
        ]);

        $kirimEmail = $request->boolean('kirim_email');
        unset($validated['kirim_email']);
        $validated['jadwal_interview'] = $validated['status'] === 'interview'
            ? ($validated['jadwal_interview'] ?? null)
            : null;
        $validated['processed_at'] = now();
        $pelamar->update($validated);

        if ($kirimEmail) {
            $pelamar->loadMissing(['lowongan.jabatan']);
            Mail::to($pelamar->email)->send(new UpdateProsesLamaranMail($pelamar, $pelamar->catatan_hr));
        }

        $message = $kirimEmail
            ? 'Data pelamar diperbarui dan email pemberitahuan berhasil dikirim.'
            : 'Data pelamar berhasil diperbarui.';

        return redirect()->route('admin.pelamar')->with('success', $message);
    }

    public function destroy($id)
    {
        $pelamar = Pelamar::findOrFail($id);

        if ($pelamar->cv) {
            Storage::disk('public')->delete($pelamar->cv);
        }

        if ($pelamar->foto) {
            Storage::disk('public')->delete($pelamar->foto);
        }

        $pelamar->delete();

        return redirect()->route('admin.pelamar')->with('success', 'Data pelamar berhasil dihapus.');
    }

}
