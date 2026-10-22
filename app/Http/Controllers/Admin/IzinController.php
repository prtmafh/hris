<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\Izin;
use App\Models\Karyawan;
use App\Models\Pengaturan;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class IzinController extends Controller
{
    public function index(Request $request)
    {
        $query = Izin::with(['karyawan', 'jenisIzinKhusus', 'approver']);
        if ($request->tanggal) $query->whereDate('tanggal_mulai', $request->tanggal);
        if (in_array($request->status, ['pending', 'disetujui', 'ditolak'], true)) $query->where('status_approval', $request->status);
        if (in_array($request->jenis_pengajuan, ['cuti', 'izin_khusus'], true)) $query->where('jenis_pengajuan', $request->jenis_pengajuan);
        if ($request->karyawan_id) $query->where('karyawan_id', $request->karyawan_id);
        return view('admin.pengajuan.izin', ['izin' => $query->latest('tanggal_mulai')->get(), 'karyawanList' => Karyawan::orderBy('nama')->get()]);
    }

    public function approve(Request $request, int $id)
    {
        $request->validate(['catatan_approval' => 'nullable|string|max:1000']);

        try {
            DB::transaction(function () use ($id, $request) {
                $izin = Izin::lockForUpdate()->findOrFail($id);
                if ($izin->status_approval !== 'pending') {
                    throw new \RuntimeException('Pengajuan ini sudah diproses.');
                }

                $karyawan = Karyawan::lockForUpdate()->findOrFail($izin->karyawan_id);

                if ($izin->jenis_pengajuan === 'cuti') {
                    $cutiDisetujui = Izin::where('karyawan_id', $karyawan->id)
                        ->where('jenis_pengajuan', 'cuti')
                        ->where('status_approval', 'disetujui')
                        ->sum('jumlah_hari');
                    $sisaKuota = max((int) $karyawan->kuota_izin - (int) $cutiDisetujui, 0);

                    if ($izin->jumlah_hari > $sisaKuota) {
                        throw new \RuntimeException('Sisa kuota cuti tidak mencukupi.');
                    }
                }

                $izin->update([
                    'status_approval' => 'disetujui',
                    'catatan_approval' => $request->catatan_approval,
                    'disetujui_oleh' => Auth::user()->id,
                    'tanggal_approval' => now(),
                ]);

                if ($izin->jenis_pengajuan === 'cuti') {
                    $karyawan->update(['kuota_izin' => max((int) $karyawan->kuota_izin - (int) $izin->jumlah_hari, 0)]);
                }

                $this->terapkanAbsensiIzin($izin, $karyawan);
            });
        } catch (\RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        }

        return back()->with('success', 'Pengajuan berhasil disetujui.');
    }

    public function reject(Request $request, int $id)
    {
        $request->validate(['catatan_approval' => 'nullable|string|max:1000']);
        $izin = Izin::where('status_approval', 'pending')->findOrFail($id);
        $izin->update([
            'status_approval' => 'ditolak',
            'catatan_approval' => $request->catatan_approval,
            'disetujui_oleh' => Auth::user()->id,
            'tanggal_approval' => now(),
        ]);

        return back()->with('success', 'Pengajuan berhasil ditolak.');
    }

    public function cancel(int $id)
    {
        try {
            DB::transaction(function () use ($id) {
                $izin = Izin::lockForUpdate()->findOrFail($id);
                if ($izin->status_approval !== 'disetujui') {
                    throw new \RuntimeException('Hanya pengajuan yang disetujui dapat dibatalkan.');
                }

                $karyawan = Karyawan::lockForUpdate()->findOrFail($izin->karyawan_id);
                if ($izin->jenis_pengajuan === 'cuti') {
                    $karyawan->increment('kuota_izin', $izin->jumlah_hari);
                }

                $izin->update([
                    'status_approval' => 'ditolak',
                    'catatan_approval' => 'Approval dibatalkan oleh admin.',
                    'disetujui_oleh' => Auth::user()->id,
                    'tanggal_approval' => now(),
                ]);
            });
        } catch (\RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        }

        return back()->with('success', 'Approval dibatalkan dan kuota cuti dikembalikan.');
    }

    private function terapkanAbsensiIzin(Izin $izin, Karyawan $karyawan): void
    {
        $maxSesi = max(1, (int) Pengaturan::getValue('max_sesi_harian', 3));
        foreach (CarbonPeriod::create($izin->tanggal_mulai, $izin->tanggal_selesai) as $tanggal) {
            if (!$tanggal->isWeekday()) continue;
            $absensi = Absensi::updateOrCreate(['karyawan_id' => $karyawan->id, 'tanggal' => $tanggal->toDateString()], ['status' => 'izin', 'jam_masuk' => null, 'jam_keluar' => null]);
            if ($karyawan->status_gaji === 'harian') for ($sesi = 1; $sesi <= $maxSesi; $sesi++) $absensi->sesi()->updateOrCreate(['sesi_ke' => $sesi], ['status' => 'izin', 'jam_checkin' => null, 'jam_checkout' => null, 'keterangan' => 'Pengajuan izin disetujui']);
        }
    }
}
