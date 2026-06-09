<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Lowongan;
use App\Models\Pelamar;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PelamarController extends Controller
{
    public function store(Request $request, $id)
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

        $validated['cv'] = $request->file('cv')
            ->store('pelamar/cv', 'public');

        $validated['foto'] = $request->hasFile('foto')
            ? $request->file('foto')->store('pelamar/foto', 'public')
            : null;

        $validated['status'] = 'pending';
        $validated['applied_at'] = now();

        $pelamar = Pelamar::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Lamaran berhasil dikirim',
            'data' => $pelamar
        ], 201);
    }

    public function tracking(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $pelamar = Pelamar::with([
            'lowongan.jabatan'
        ])
            ->where('email', $request->email)
            ->orderByDesc('applied_at')
            ->get();

        return response()->json($pelamar);
    }
}
