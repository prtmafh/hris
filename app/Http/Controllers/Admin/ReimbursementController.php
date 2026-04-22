<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KategoriReimbursement;
use App\Models\Karyawan;
use App\Models\Reimbursement;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class ReimbursementController extends Controller
{
    public function index(Request $request)
    {
        $query = Reimbursement::with(['karyawan', 'kategoriReimbursement', 'penyetuju']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('karyawan_id')) {
            $query->where('karyawan_id', $request->karyawan_id);
        }

        if ($request->filled('kategori_reimbursement_id')) {
            $query->where('kategori_reimbursement_id', $request->kategori_reimbursement_id);
        }

        $reimbursement = $query->orderByDesc('tanggal_pengajuan')
            ->orderByDesc('created_at')
            ->get();
        $karyawanList = Karyawan::orderBy('nama')->get();
        $kategoriList = KategoriReimbursement::where('status', 'aktif')->orderBy('nama')->get();

        return view('admin.reimbursement.index', compact('reimbursement', 'karyawanList', 'kategoriList'));
    }

    public function store(Request $request)
    {
        $validated = $this->validateReimbursement($request);
        $kategori = KategoriReimbursement::where('status', 'aktif')->findOrFail($validated['kategori_reimbursement_id']);

        $this->validatePlafon(
            $validated['jumlah_diajukan'],
            $kategori,
            $validated['karyawan_id'],
            $validated['tanggal_transaksi']
        );

        if ($kategori->perlu_bukti && !$request->hasFile('bukti')) {
            return back()->withErrors(['bukti' => 'Bukti wajib diunggah untuk kategori ini.'])->withInput();
        }

        if ($request->hasFile('bukti')) {
            $validated['bukti'] = $request->file('bukti')->store('reimbursement', 'public');
        }

        $validated['tanggal_pengajuan'] = now()->toDateString();
        $validated['status'] = 'pending';

        Reimbursement::create($validated);

        return back()->with('success', 'Pengajuan reimbursement berhasil ditambahkan.');
    }

    public function approve(Request $request, $id)
    {
        $reimbursement = Reimbursement::with('kategoriReimbursement')->findOrFail($id);

        if ($reimbursement->status !== 'pending') {
            return back()->with('error', 'Hanya reimbursement berstatus pending yang dapat disetujui.');
        }

        $validated = $request->validate([
            'jumlah_disetujui' => 'nullable|numeric|min:0|max:' . $reimbursement->jumlah_diajukan,
            'catatan_approval' => 'nullable|string|max:1000',
        ]);

        $jumlahDisetujui = $validated['jumlah_disetujui'] ?? $reimbursement->jumlah_diajukan;
        $this->validatePlafon(
            $jumlahDisetujui,
            $reimbursement->kategoriReimbursement,
            $reimbursement->karyawan_id,
            $reimbursement->tanggal_transaksi,
            $reimbursement->id
        );

        $reimbursement->update([
            'jumlah_disetujui' => $jumlahDisetujui,
            'status' => 'disetujui',
            'catatan_approval' => $validated['catatan_approval'] ?? null,
            'disetujui_oleh' => Auth::id(),
            'tgl_disetujui' => now(),
        ]);

        return back()->with('success', 'Pengajuan reimbursement berhasil disetujui.');
    }

    public function reject(Request $request, $id)
    {
        $reimbursement = Reimbursement::findOrFail($id);

        if (!in_array($reimbursement->status, ['pending', 'disetujui'], true)) {
            return back()->with('error', 'Status reimbursement ini tidak dapat ditolak.');
        }

        $validated = $request->validate([
            'catatan_approval' => 'nullable|string|max:1000',
        ]);

        $reimbursement->update([
            'jumlah_disetujui' => null,
            'status' => 'ditolak',
            'catatan_approval' => $validated['catatan_approval'] ?? null,
            'disetujui_oleh' => Auth::id(),
            'tgl_disetujui' => now(),
            'tgl_dibayar' => null,
        ]);

        return back()->with('success', 'Pengajuan reimbursement berhasil ditolak.');
    }

    public function markPaid($id)
    {
        $reimbursement = Reimbursement::findOrFail($id);

        if ($reimbursement->status !== 'disetujui') {
            return back()->with('error', 'Hanya reimbursement yang sudah disetujui yang dapat ditandai dibayar.');
        }

        $reimbursement->update([
            'status' => 'dibayar',
            'tgl_dibayar' => now()->toDateString(),
        ]);

        return back()->with('success', 'Reimbursement berhasil ditandai dibayar.');
    }

    public function destroy($id)
    {
        $reimbursement = Reimbursement::findOrFail($id);

        if ($reimbursement->bukti) {
            Storage::disk('public')->delete($reimbursement->bukti);
        }

        $reimbursement->delete();

        return back()->with('success', 'Data reimbursement berhasil dihapus.');
    }

    private function validateReimbursement(Request $request): array
    {
        return $request->validate([
            'karyawan_id' => 'required|exists:karyawan,id',
            'kategori_reimbursement_id' => 'required|exists:kategori_reimbursement,id',
            'tanggal_transaksi' => 'required|date',
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string|max:1000',
            'jumlah_diajukan' => 'required|numeric|min:1',
            'bukti' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);
    }

    private function validatePlafon(
        float $jumlah,
        KategoriReimbursement $kategori,
        int $karyawanId,
        $tanggalTransaksi,
        ?int $ignoreId = null
    ): void {
        if ($kategori->plafon_per_pengajuan && $jumlah > $kategori->plafon_per_pengajuan) {
            throw ValidationException::withMessages([
                'jumlah_disetujui' => 'Jumlah melebihi plafon per pengajuan kategori.',
                'jumlah_diajukan' => 'Jumlah melebihi plafon per pengajuan kategori.',
            ]);
        }

        if (!$kategori->plafon_per_bulan) {
            return;
        }

        $tanggal = Carbon::parse($tanggalTransaksi);
        $query = Reimbursement::where('karyawan_id', $karyawanId)
            ->where('kategori_reimbursement_id', $kategori->id)
            ->whereIn('status', ['pending', 'disetujui', 'dibayar'])
            ->whereMonth('tanggal_transaksi', $tanggal->month)
            ->whereYear('tanggal_transaksi', $tanggal->year);

        if ($ignoreId) {
            $query->where('id', '!=', $ignoreId);
        }

        $totalBulanIni = (float) $query->sum(DB::raw('COALESCE(jumlah_disetujui, jumlah_diajukan)'));

        if (($totalBulanIni + $jumlah) > $kategori->plafon_per_bulan) {
            throw ValidationException::withMessages([
                'jumlah_disetujui' => 'Total reimbursement bulan ini melebihi plafon bulanan kategori.',
                'jumlah_diajukan' => 'Total reimbursement bulan ini melebihi plafon bulanan kategori.',
            ]);
        }
    }
}
