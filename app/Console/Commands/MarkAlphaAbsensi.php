<?php

namespace App\Console\Commands;

use App\Models\Absensi;
use App\Models\HariLibur;
use App\Models\Karyawan;
use Carbon\Carbon;
use Illuminate\Console\Command;

class MarkAlphaAbsensi extends Command
{
    protected $signature = 'absensi:mark-alpha {--tanggal= : Tanggal yang akan diproses (Y-m-d), default hari ini}';

    protected $description = 'Tandai karyawan yang tidak absen pada hari kerja sebagai alpha';

    public function handle(): int
    {
        $tanggal = $this->option('tanggal')
            ? Carbon::parse($this->option('tanggal'))->startOfDay()
            : Carbon::today();

        $this->info("Memproses tanggal: {$tanggal->format('d/m/Y')}");

        if (! $this->isHariKerja($tanggal)) {
            $this->info('Bukan hari kerja. Proses dihentikan.');
            return Command::SUCCESS;
        }

        if ($this->isHariLibur($tanggal)) {
            $this->info('Hari libur. Proses dihentikan.');
            return Command::SUCCESS;
        }

        $karyawanList = Karyawan::where('status', 'aktif')
            ->whereDate('tgl_masuk', '<=', $tanggal)
            ->whereHas('user', fn($q) => $q->where('role', 'karyawan')->where('status', 'aktif'))
            ->get();

        $sudahAbsen = Absensi::whereDate('tanggal', $tanggal)->pluck('karyawan_id')->toArray();

        $count = 0;
        foreach ($karyawanList as $karyawan) {
            if (in_array($karyawan->id, $sudahAbsen)) {
                continue;
            }

            Absensi::firstOrCreate(
                [
                    'karyawan_id' => $karyawan->id,
                    'tanggal'     => $tanggal->toDateString(),
                ],
                [
                    'status'      => 'alpha',
                ]
            );

            $count++;
        }

        $this->info("Selesai. {$count} karyawan ditandai alpha.");
        return Command::SUCCESS;
    }

    private function isHariKerja(Carbon $tanggal): bool
    {
        return $tanggal->isWeekday();
    }

    private function isHariLibur(Carbon $tanggal): bool
    {
        // Cek tanggal eksak
        $eksak = HariLibur::whereDate('tanggal', $tanggal)->exists();
        if ($eksak) {
            return true;
        }

        // Cek hari libur berulang tahunan (bulan dan hari sama)
        return HariLibur::where('berulang_tahunan', true)
            ->whereMonth('tanggal', $tanggal->month)
            ->whereDay('tanggal', $tanggal->day)
            ->exists();
    }
}
