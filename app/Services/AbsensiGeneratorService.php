<?php

namespace App\Services;

use App\Models\Absensi;
use App\Models\HariLibur;
use App\Models\Karyawan;
use App\Models\Pengaturan;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\DB;

class AbsensiGeneratorService
{
    public function generateTanggal(Carbon $tanggal): array
    {
        return $this->generatePeriode($tanggal->copy()->startOfDay(), $tanggal->copy()->startOfDay());
    }

    public function generateBulan(int $bulan, int $tahun): array
    {
        $awal = Carbon::create($tahun, $bulan, 1)->startOfDay();

        return $this->generatePeriode($awal, $awal->copy()->endOfMonth()->startOfDay());
    }

    private function generatePeriode(Carbon $awal, Carbon $akhir): array
    {
        $hasil = ['dibuat' => 0, 'sudah_tersedia' => 0, 'hari_libur' => 0, 'sesi_dibuat' => 0];
        $karyawan = Karyawan::where('status', 'aktif')->get();
        $maxSesi = max(1, (int) Pengaturan::getValue('max_sesi_harian', 3));

        DB::transaction(function () use ($awal, $akhir, $karyawan, $maxSesi, &$hasil) {
            foreach (CarbonPeriod::create($awal, $akhir) as $tanggal) {
                if ($this->bukanHariKerja($tanggal)) {
                    $hasil['hari_libur']++;

                    continue;
                }

                foreach ($karyawan as $pegawai) {
                    if ($pegawai->tgl_masuk && Carbon::parse($pegawai->tgl_masuk)->startOfDay()->gt($tanggal)) {
                        continue;
                    }

                    if (Absensi::where('karyawan_id', $pegawai->id)->whereDate('tanggal', $tanggal)->exists()) {
                        $hasil['sudah_tersedia']++;

                        continue;
                    }

                    $absensi = Absensi::create([
                        'karyawan_id' => $pegawai->id,
                        'tanggal' => $tanggal->toDateString(),
                        'status' => 'alpha',
                    ]);
                    $hasil['dibuat']++;

                    if ($pegawai->status_gaji === 'harian') {
                        $details = [];
                        for ($sesi = 1; $sesi <= $maxSesi; $sesi++) {
                            $details[] = ['sesi_ke' => $sesi, 'status' => 'alpha'];
                        }
                        $absensi->sesi()->createMany($details);
                        $hasil['sesi_dibuat'] += $maxSesi;
                    }
                }
            }
        });

        return $hasil;
    }

    private function bukanHariKerja(Carbon $tanggal): bool
    {
        if ($tanggal->isWeekend()) {
            return true;
        }

        return HariLibur::whereDate('tanggal', $tanggal)->exists()
            || HariLibur::where('berulang_tahunan', true)
                ->whereMonth('tanggal', $tanggal->month)
                ->whereDay('tanggal', $tanggal->day)
                ->exists();
    }
}
