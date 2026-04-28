<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HariLibur;
use Illuminate\Http\Request;

class HariLiburController extends Controller
{
    public function index()
    {
        return view('admin.hari_libur.index');
    }

    public function data()
    {
        $data = HariLibur::orderBy('tanggal')
            ->get(['id', 'tanggal', 'nama', 'jenis', 'keterangan', 'berulang_tahunan'])
            ->map(fn($item) => [
                'id'               => $item->id,
                'tanggal'          => $item->tanggal->format('Y-m-d'),
                'nama'             => $item->nama,
                'jenis'            => $item->jenis,
                'keterangan'       => $item->keterangan,
                'berulang_tahunan' => $item->berulang_tahunan,
            ]);

        return response()->json(['data' => $data]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tanggal'          => 'required|date',
            'nama'             => 'required|string|max:255',
            'jenis'            => 'required|in:nasional,cuti_bersama,perusahaan',
            'keterangan'       => 'nullable|string|max:500',
            'berulang_tahunan' => 'boolean',
        ]);

        $item = HariLibur::create([
            'tanggal'          => $validated['tanggal'],
            'nama'             => $validated['nama'],
            'jenis'            => $validated['jenis'],
            'keterangan'       => $validated['keterangan'] ?? null,
            'berulang_tahunan' => $request->boolean('berulang_tahunan'),
        ]);

        return response()->json(['message' => 'Hari libur berhasil ditambahkan.', 'data' => $item], 201);
    }

    public function update(Request $request, int $id)
    {
        $hariLibur = HariLibur::findOrFail($id);

        $validated = $request->validate([
            'tanggal'          => 'required|date',
            'nama'             => 'required|string|max:255',
            'jenis'            => 'required|in:nasional,cuti_bersama,perusahaan',
            'keterangan'       => 'nullable|string|max:500',
            'berulang_tahunan' => 'boolean',
        ]);

        $hariLibur->update([
            'tanggal'          => $validated['tanggal'],
            'nama'             => $validated['nama'],
            'jenis'            => $validated['jenis'],
            'keterangan'       => $validated['keterangan'] ?? null,
            'berulang_tahunan' => $request->boolean('berulang_tahunan'),
        ]);

        return response()->json(['message' => 'Hari libur berhasil diperbarui.', 'data' => $hariLibur]);
    }

    public function destroy(int $id)
    {
        HariLibur::findOrFail($id)->delete();

        return response()->json(['message' => 'Hari libur berhasil dihapus.']);
    }
}
