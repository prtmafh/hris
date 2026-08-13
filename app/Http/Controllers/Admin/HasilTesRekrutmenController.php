<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HasilTesRekrutmen;
use App\Models\Pelamar;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class HasilTesRekrutmenController extends Controller
{
    public function index(Request $request)
    {
        $query = HasilTesRekrutmen::with(['pelamar.lowongan.jabatan'])
            ->latest('tanggal_tes')
            ->latest('id');

        if ($request->filled('pelamar_id')) {
            $query->where('pelamar_id', $request->integer('pelamar_id'));
        }

        if ($request->filled('jenis_tes')) {
            $query->where('jenis_tes', $request->string('jenis_tes'));
        }

        if ($request->filled('hasil')) {
            $query->where('hasil', $request->string('hasil'));
        }

        $hasilTes = $query->get();
        $pelamar = Pelamar::with(['lowongan.jabatan'])
            ->orderBy('nama')
            ->get();
        $jenisTes = HasilTesRekrutmen::query()
            ->select('jenis_tes')
            ->distinct()
            ->orderBy('jenis_tes')
            ->pluck('jenis_tes');
        $pelamarDipilih = $request->filled('pelamar_id')
            ? $pelamar->firstWhere('id', $request->integer('pelamar_id'))
            : null;
        if ($pelamarDipilih) {
            $pelamarDipilih->setRelation('hasilTes', $hasilTes);
        }

        return view('admin.hasil_tes_rekrutmen.index', compact(
            'hasilTes',
            'pelamar',
            'jenisTes',
            'pelamarDipilih'
        ));
    }

    public function store(Request $request)
    {
        HasilTesRekrutmen::create($this->validated($request));

        return back()->with('success', 'Hasil tes rekrutmen berhasil ditambahkan.');
    }

    public function update(Request $request, HasilTesRekrutmen $hasilTes)
    {
        $hasilTes->update($this->validated($request));

        return back()->with('success', 'Hasil tes rekrutmen berhasil diperbarui.');
    }

    public function destroy(HasilTesRekrutmen $hasilTes)
    {
        $hasilTes->delete();

        return back()->with('success', 'Hasil tes rekrutmen berhasil dihapus.');
    }

    public function terapkanRekomendasi(Pelamar $pelamar)
    {
        $pelamar->load('hasilTes');
        $rekomendasi = $pelamar->rekomendasiHasilTes();

        $status = match ($rekomendasi) {
            'direkomendasikan' => 'diterima',
            'tidak_direkomendasikan' => 'ditolak',
            default => null,
        };

        if (! $status) {
            return back()->with('error', 'Rekomendasi masih perlu dipertimbangkan dan belum dapat diterapkan sebagai keputusan akhir.');
        }

        $pelamar->update([
            'status' => $status,
            'processed_at' => now(),
        ]);

        return back()->with('success', 'Rekomendasi berhasil diterapkan ke status pelamar.');
    }

    public function downloadPdf(Pelamar $pelamar)
    {
        $pelamar->load([
            'lowongan.jabatan',
            'hasilTes' => fn ($query) => $query->orderBy('tanggal_tes')->orderBy('id'),
        ]);

        $pdf = Pdf::loadView('pdf.hasil-tes-rekrutmen', [
            'pelamar' => $pelamar,
            'rekomendasi' => $pelamar->rekomendasiHasilTes(),
        ])->setPaper('a4');

        $namaFile = 'hasil-tes-'.str($pelamar->nama)->slug().'.pdf';

        return $pdf->download($namaFile);
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'pelamar_id' => 'required|exists:pelamar,id',
            'jenis_tes' => 'required|string|max:100',
            'tanggal_tes' => 'required|date',
            'nilai' => 'nullable|numeric|min:0|max:999999.99',
            'hasil' => 'required|in:lulus,tidak_lulus,dipertimbangkan',
            'penguji' => 'nullable|string|max:150',
            'catatan' => 'nullable|string|max:3000',
        ]);
    }
}
