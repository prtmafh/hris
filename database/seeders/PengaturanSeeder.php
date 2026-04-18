<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pengaturan;

class PengaturanSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            [
                'key' => 'jam_masuk',
                'value' => '08:00',
                'tipe' => 'time',
                'grup' => 'absensi',
                'label' => 'Jam Masuk',
                'keterangan' => 'Jam mulai kerja karyawan',
            ],
            [
                'key' => 'jam_pulang',
                'value' => '17:00',
                'tipe' => 'time',
                'grup' => 'absensi',
                'label' => 'Jam Pulang',
            ],
            [
                'key' => 'toleransi_keterlambatan',
                'value' => '10',
                'tipe' => 'integer',
                'grup' => 'absensi',
                'label' => 'Toleransi Keterlambatan (menit)',
            ],
            [
                'key' => 'denda_keterlambatan',
                'value' => '5000',
                'tipe' => 'decimal',
                'grup' => 'absensi',
                'label' => 'Denda Keterlambatan',
                'keterangan' => 'Denda per kejadian atau per menit',
            ],
            [
                'key' => 'tarif_lembur_per_jam',
                'value' => '20000',
                'tipe' => 'decimal',
                'grup' => 'penggajian',
                'label' => 'Tarif Lembur per Jam',
            ],
            [
                'key' => 'kuota_izin',
                'value' => '12',
                'tipe' => 'integer',
                'grup' => 'izin',
                'label' => 'Kuota Izin Tahunan',
            ],
        ];

        foreach ($data as $item) {
            Pengaturan::updateOrCreate(
                ['key' => $item['key']],
                $item
            );
        }
    }
}
