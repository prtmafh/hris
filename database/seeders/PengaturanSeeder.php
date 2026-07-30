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
                'tipe' => 'currency',
                'grup' => 'absensi',
                'label' => 'Denda Keterlambatan',
                'keterangan' => 'Denda per kejadian atau per menit',
            ],
            [
                'key' => 'tarif_lembur',
                'value' => '20000',
                'tipe' => 'currency',
                'grup' => 'penggajian',
                'label' => 'Tarif Lembur',
            ],
            [
                'key' => 'kuota_izin',
                'value' => '12',
                'tipe' => 'integer',
                'grup' => 'izin',
                'label' => 'Kuota Izin Tahunan',
            ],
            [
                'key' => 'max_sesi_harian',
                'value' => '3',
                'tipe' => 'integer',
                'grup' => 'absensi',
                'label' => 'Jumlah Sesi',
                'keterangan' => 'Jumlah sesi kerja per hari',
            ],
            [
                'key' => 'sesi_1_mulai',
                'value' => '08:00',
                'tipe' => 'time',
                'grup' => 'absensi',
                'label' => 'Masuk Sesi Ke-1',
                'keterangan' => 'Absen Masuk Sesi Ke-1',
            ],
            [
                'key' => 'sesi_1_selesai',
                'value' => '17:00',
                'tipe' => 'time',
                'grup' => 'absensi',
                'label' => 'Pulang Sesi Ke-1',
                'keterangan' => 'Absen Pulang Sesi Ke-1',
            ],
            [
                'key' => 'sesi_2_mulai',
                'value' => '18:00',
                'tipe' => 'time',
                'grup' => 'absensi',
                'label' => 'Masuk Sesi Ke-2',
                'keterangan' => 'Absen Masuk Sesi Ke-2',
            ],
            [
                'key' => 'sesi_2_selesai',
                'value' => '23:00',
                'tipe' => 'time',
                'grup' => 'absensi',
                'label' => 'Pulang Sesi Ke-2',
                'keterangan' => 'Absen Pulang Sesi Ke-2',
            ],
            [
                'key' => 'sesi_3_mulai',
                'value' => '00:00',
                'tipe' => 'time',
                'grup' => 'absensi',
                'label' => 'Masuk Sesi Ke-3',
                'keterangan' => 'Absen Masuk Sesi Ke-3',
            ],
            [
                'key' => 'sesi_3_selesai',
                'value' => '04:00',
                'tipe' => 'time',
                'grup' => 'absensi',
                'label' => 'Pulang Sesi Ke-3',
                'keterangan' => 'Absen Pulang Sesi Ke-3',
            ],
            [
                'key' => 'upah_sesi',
                'value' => '100000',
                'tipe' => 'currency',
                'grup' => 'absensi',
                'label' => 'Upah Sesi',
                'keterangan' => 'Upah untuk setiap sesi kerja harian',
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
