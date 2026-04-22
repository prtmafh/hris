<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\PanggilanInterviewMail;
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
            'jadwal_interview' => 'nullable|date',
            'catatan_hr' => 'nullable|string|max:2000',
        ]);

        $validated['processed_at'] = now();
        $pelamar->update($validated);

        return redirect()->route('admin.pelamar')->with('success', 'Data pelamar berhasil diperbarui.');
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

    public function kirimPanggilan(Request $request, $id)
    {
        $pelamar = Pelamar::with(['lowongan.jabatan'])->findOrFail($id);

        $validated = $request->validate([
            'jadwal_interview' => 'required|date',
            'pesan' => 'required|string|max:3000',
        ]);

        $pelamar->update([
            'status' => 'interview',
            'jadwal_interview' => $validated['jadwal_interview'],
            'catatan_hr' => $validated['pesan'],
            'processed_at' => now(),
        ]);

        Mail::to($pelamar->email)->send(new PanggilanInterviewMail($pelamar, $validated['pesan']));

        return redirect()->route('admin.pelamar')->with('success', 'Email panggilan berhasil dikirim.');
    }

    public function kirimUpdateProses(Request $request, $id)
    {
        $pelamar = Pelamar::with(['lowongan.jabatan'])->findOrFail($id);

        $validated = $request->validate([
            'status' => 'required|in:pending,screening,interview,offering,diterima,ditolak',
            'jadwal_interview' => 'nullable|date',
            'pesan' => 'required|string|max:3000',
        ]);

        $pelamar->update([
            'status' => $validated['status'],
            'jadwal_interview' => $validated['jadwal_interview'] ?? $pelamar->jadwal_interview,
            'catatan_hr' => $validated['pesan'],
            'processed_at' => now(),
        ]);

        Mail::to($pelamar->email)->send(new UpdateProsesLamaranMail($pelamar, $validated['pesan']));

        return redirect()->route('admin.pelamar')->with('success', 'Email update proses lamaran berhasil dikirim.');
    }
}
