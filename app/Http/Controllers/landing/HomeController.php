<?php

namespace App\Http\Controllers\landing;

use App\Http\Controllers\Controller;
use App\Models\Lowongan;
use App\Models\Pelamar;
use Carbon\Carbon;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        return view('landing.index');
    }

    public function karir()
    {
        $lowongan = $this->activeLowongan();
        $trackingPelamar = collect();
        $trackingEmail = null;

        return view('landing.karir', compact('lowongan', 'trackingPelamar', 'trackingEmail'));
    }

    public function lamar(Request $request, $id)
    {
        $lowongan = Lowongan::where('status', 'aktif')
            ->whereDate('tanggal_buka', '<=', Carbon::today())
            ->whereDate('tanggal_tutup', '>=', Carbon::today())
            ->findOrFail($id);

        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'no_hp' => 'nullable|string|max:20',
            'tanggal_lahir' => 'nullable|date|before:today',
            'alamat' => 'nullable|string|max:1000',
            'cv' => 'required|file|mimes:pdf,doc,docx|max:4096',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $validated['lowongan_id'] = $lowongan->id;
        $validated['cv'] = $request->file('cv')->store('pelamar/cv', 'public');
        $validated['foto'] = $request->hasFile('foto')
            ? $request->file('foto')->store('pelamar/foto', 'public')
            : null;
        $validated['status'] = 'pending';
        $validated['applied_at'] = now();

        Pelamar::create($validated);

        return redirect()->route('tsi-group.karir')
            ->with('success', 'Lamaran berhasil dikirim. Terima kasih sudah melamar di PT. Tidarjaya Solidindo.');
    }

    public function tracking(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email|max:255',
        ]);

        $lowongan = $this->activeLowongan();
        $trackingEmail = $validated['email'];
        $trackingPelamar = Pelamar::with(['lowongan.jabatan'])
            ->where('email', $trackingEmail)
            ->orderBy('applied_at', 'desc')
            ->get();

        return view('landing.karir', compact('lowongan', 'trackingPelamar', 'trackingEmail'));
    }

    private function activeLowongan()
    {
        $today = Carbon::today();

        return Lowongan::with('jabatan')
            ->where('status', 'aktif')
            ->whereDate('tanggal_buka', '<=', $today)
            ->whereDate('tanggal_tutup', '>=', $today)
            ->orderBy('tanggal_tutup')
            ->orderBy('created_at', 'desc')
            ->get();
    }
}
