<?php

namespace App\Http\Controllers\Karyawan;

use App\Http\Controllers\Controller;
use App\Models\HariLibur;
use App\Models\Izin;
use App\Models\JenisIzinKhusus;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class IzinController extends Controller
{
    public function index(Request $request)
    {
        $karyawan = Auth::user();
        $query = Izin::with('jenisIzinKhusus')->where('karyawan_id', $karyawan->id);
        if (in_array($request->filter, ['cuti', 'izin_khusus'], true)) $query->where('jenis_pengajuan', $request->filter);
        if (in_array($request->filter, ['pending', 'disetujui', 'ditolak'], true)) $query->where('status_approval', $request->filter);

        $izin = $query->orderByDesc('tanggal_mulai')->orderByDesc('id')->get();
        $kuotaIzin = (int) ($karyawan->kuota_izin ?? 12);
        $cutiDisetujui = Izin::where('karyawan_id', $karyawan->id)
            ->where('jenis_pengajuan', 'cuti')
            ->where('status_approval', 'disetujui')
            ->sum('jumlah_hari');
        $sisaIzin = max($kuotaIzin - $cutiDisetujui, 0);
        $jenisIzinKhusus = JenisIzinKhusus::where('aktif', true)->orderBy('nama')->get();

        return view('karyawan.izin-saya', compact('izin', 'kuotaIzin', 'cutiDisetujui', 'sisaIzin', 'jenisIzinKhusus'));
    }

    public function store(Request $request)
    {
        $karyawan = Auth::user();
        $validated = $request->validate([
            'jenis_pengajuan' => ['required', Rule::in(['cuti', 'izin_khusus'])],
            'jenis_izin' => ['nullable', 'string', 'max:100'],
            'jenis_izin_khusus_id' => ['nullable', Rule::exists('jenis_izin_khusus', 'id')->where('aktif', true)],
            'tanggal_mulai' => ['required', 'date'],
            'tanggal_selesai' => ['required', 'date', 'after_or_equal:tanggal_mulai'],
            'keterangan' => ['required', 'string', 'max:500'],
            'bukti' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:2048'],
        ]);

        if ($validated['jenis_pengajuan'] === 'cuti' && blank($validated['jenis_izin'] ?? null)) {
            return back()->withInput()->withErrors(['jenis_izin' => 'Pilih jenis cuti.']);
        }

        if ($validated['jenis_pengajuan'] === 'izin_khusus' && !$request->filled('jenis_izin_khusus_id')) {
            return back()->withInput()->withErrors(['jenis_izin_khusus_id' => 'Pilih jenis izin khusus.']);
        }

        $jenisKhusus = $request->filled('jenis_izin_khusus_id') ? JenisIzinKhusus::findOrFail($request->jenis_izin_khusus_id) : null;
        if ($jenisKhusus?->wajib_bukti && !$request->hasFile('bukti')) {
            return back()->withInput()->withErrors(['bukti' => 'Bukti wajib diunggah untuk jenis izin ini.']);
        }

        $mulai = Carbon::parse($validated['tanggal_mulai'])->startOfDay();
        $selesai = Carbon::parse($validated['tanggal_selesai'])->startOfDay();
        $jumlahHari = $this->hitungHariKerja($mulai, $selesai);

        if ($jumlahHari < 1) {
            return back()->withInput()->withErrors(['tanggal_mulai' => 'Rentang tanggal tidak memiliki hari kerja.']);
        }

        if ($validated['jenis_pengajuan'] === 'cuti') {
            $kuotaSaatIni = (int) ($karyawan->kuota_izin ?? 12);
            $cutiDisetujui = Izin::where('karyawan_id', $karyawan->id)
                ->where('jenis_pengajuan', 'cuti')
                ->where('status_approval', 'disetujui')
                ->sum('jumlah_hari');
            $sisaKuota = max($kuotaSaatIni - $cutiDisetujui, 0);

            if ($jumlahHari > $sisaKuota) {
                return back()->withInput()->with('error', 'Sisa kuota cuti tidak mencukupi.');
            }
        }

        Izin::create([
            'karyawan_id' => $karyawan->id,
            'tanggal' => $mulai->toDateString(),
            'jenis_pengajuan' => $validated['jenis_pengajuan'],
            'jenis_izin' => $validated['jenis_pengajuan'] === 'cuti' ? $validated['jenis_izin'] : $jenisKhusus->nama,
            'jenis_izin_khusus_id' => $jenisKhusus?->id,
            'tanggal_mulai' => $mulai,
            'tanggal_selesai' => $selesai,
            'jumlah_hari' => $jumlahHari,
            'keterangan' => $validated['keterangan'],
            'bukti' => $request->hasFile('bukti') ? $request->file('bukti')->store('bukti_izin', 'public') : null,
            'status_approval' => 'pending',
        ]);

        return back()->with('success', 'Pengajuan berhasil dikirim dan menunggu approval.');
    }

    private function hitungHariKerja(Carbon $mulai, Carbon $selesai): int
    {
        $hariLibur = HariLibur::get();
        return collect(CarbonPeriod::create($mulai, $selesai))->filter(function (Carbon $tanggal) use ($hariLibur) {
            return $tanggal->isWeekday() && !$hariLibur->contains(function ($libur) use ($tanggal) {
                $liburTanggal = Carbon::parse($libur->tanggal);
                return $libur->berulang_tahunan ? ($liburTanggal->month === $tanggal->month && $liburTanggal->day === $tanggal->day) : $liburTanggal->isSameDay($tanggal);
            });
        })->count();
    }
}
