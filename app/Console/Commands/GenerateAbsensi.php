<?php

namespace App\Console\Commands;

use App\Models\Absensi;
use App\Models\AbsensiSesi;
use App\Models\HariLibur;
use App\Models\Karyawan;
use Carbon\Carbon;
use Illuminate\Console\Command;

class GenerateAbsensi extends Command
{
    protected $signature = 'absensi:generate
                            {--bulan= : Bulan (1-12)}
                            {--tahun= : Tahun}';

    protected $description = 'Generate data absensi dummy';

    public function handle(): int
    {
        $bulan = (int) ($this->option('bulan') ?? now()->month);
        $tahun = (int) ($this->option('tahun') ?? now()->year);

        $mulai = Carbon::create($tahun, $bulan, 1)->startOfMonth();
        $akhir = Carbon::create($tahun, $bulan, 1)->endOfMonth();

        $hariIni = Carbon::today();

        if ($bulan == $hariIni->month && $tahun == $hariIni->year) {
            $akhir = $hariIni->copy();
        }

        $karyawanList = Karyawan::where('status', 'aktif')->get();

        if ($karyawanList->isEmpty()) {
            $this->warn('Tidak ada karyawan aktif.');
            return self::SUCCESS;
        }

        $totalAbsensi = 0;
        $totalSesi = 0;

        foreach ($karyawanList as $karyawan) {

            $this->line(
                "Generate: {$karyawan->nama} ({$karyawan->status_gaji})"
            );

            $tanggal = $mulai->copy();

            while ($tanggal <= $akhir) {

                // Sabtu & Minggu libur
                if ($tanggal->isWeekend()) {
                    $tanggal->addDay();
                    continue;
                }

                // Hari libur nasional/perusahaan
                $isHoliday = HariLibur::whereDate(
                    'tanggal',
                    $tanggal->toDateString()
                )->exists();

                if ($isHoliday) {
                    $tanggal->addDay();
                    continue;
                }

                // Skip jika sudah ada
                $sudahAda = Absensi::where(
                    'karyawan_id',
                    $karyawan->id
                )
                    ->whereDate('tanggal', $tanggal)
                    ->exists();

                if ($sudahAda) {
                    $tanggal->addDay();
                    continue;
                }

                // Distribusi status
                $rand = rand(1, 100);

                $status = match (true) {
                    $rand <= 85 => 'hadir',
                    $rand <= 95 => 'terlambat',
                    $rand <= 98 => 'izin',
                    default => 'alpha',
                };

                $jamMasuk = null;
                $jamKeluar = null;

                if (in_array($status, ['hadir', 'terlambat'])) {

                    $jamMasuk = $status === 'hadir'
                        ? Carbon::parse('08:00')->addMinutes(rand(-5, 5))
                        : Carbon::parse('08:00')->addMinutes(rand(10, 45));

                    $jamKeluar = Carbon::parse('17:00')
                        ->addMinutes(rand(-10, 30));
                }

                $absensi = Absensi::create([
                    'karyawan_id'      => $karyawan->id,
                    'tanggal'          => $tanggal->toDateString(),
                    'jam_masuk'        => $jamMasuk?->format('H:i:s'),
                    'jam_keluar'       => $jamKeluar?->format('H:i:s'),
                    'status'           => $status,
                    'latitude_masuk'   => -6.2088000,
                    'longitude_masuk'  => 106.8456000,
                    'latitude_keluar'  => -6.2088000,
                    'longitude_keluar' => 106.8456000,
                ]);

                $totalAbsensi++;

                /*
                 |--------------------------------------------------------------------------
                 | KARYAWAN BULANAN
                 |--------------------------------------------------------------------------
                 */
                if ($karyawan->status_gaji === 'bulanan') {
                    $tanggal->addDay();
                    continue;
                }

                /*
                 |--------------------------------------------------------------------------
                 | KARYAWAN HARIAN
                 |--------------------------------------------------------------------------
                 */
                for ($sesi = 1; $sesi <= 3; $sesi++) {

                    if (in_array($status, ['izin', 'alpha'])) {

                        AbsensiSesi::create([
                            'absensi_id' => $absensi->id,
                            'sesi_ke'    => $sesi,
                            'status'     => $status,
                        ]);

                        $totalSesi++;

                        continue;
                    }

                    $jamCheckin = match ($sesi) {
                        1 => Carbon::parse('08:00')->addMinutes(rand(-5, 10)),
                        2 => Carbon::parse('13:00')->addMinutes(rand(-5, 10)),
                        3 => Carbon::parse('15:30')->addMinutes(rand(-5, 10)),
                    };

                    $jamCheckout = match ($sesi) {
                        1 => Carbon::parse('12:00')->addMinutes(rand(-10, 10)),
                        2 => Carbon::parse('15:00')->addMinutes(rand(-10, 10)),
                        3 => Carbon::parse('17:00')->addMinutes(rand(-10, 10)),
                    };

                    AbsensiSesi::create([
                        'absensi_id'      => $absensi->id,
                        'sesi_ke'         => $sesi,
                        'jam_checkin'     => $jamCheckin->format('H:i:s'),
                        'jam_checkout'    => $jamCheckout->format('H:i:s'),
                        'status'          => $status,
                        'latitude_masuk'  => -6.2088000,
                        'longitude_masuk' => 106.8456000,
                        'latitude_keluar' => -6.2088000,
                        'longitude_keluar' => 106.8456000,
                    ]);

                    $totalSesi++;
                }

                $tanggal->addDay();
            }
        }

        $this->newLine();

        $this->info("Total Absensi : {$totalAbsensi}");
        $this->info("Total Sesi    : {$totalSesi}");
        $this->info('Generate absensi selesai.');

        return self::SUCCESS;
    }
}
