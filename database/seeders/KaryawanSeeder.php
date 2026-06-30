<?php

namespace Database\Seeders;

use App\Models\Karyawan;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class KaryawanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin
        Karyawan::create([
            'role_id' => 1,
            'jabatan_id' => 1,
            'nik' => '0002',
            'password' => Hash::make('admin123'),
            'nama' => 'Administrator',
            'tgl_lahir' => '1995-01-01',
            'alamat' => 'Bekasi',
            'no_hp' => '081234567890',
            'tgl_masuk' => now(),
            'status_gaji' => 'bulanan',
            'gaji_pokok' => 8000000,
            'gaji_per_hari' => null,
            'status' => 'aktif',
            'kuota_izin' => 12,
        ]);

        // Pimpinan
        Karyawan::create([
            'role_id' => 2,
            'jabatan_id' => 1,
            'nik' => '0001',
            'password' => Hash::make('pimpinan123'),
            'nama' => 'Pimpinan',
            'tgl_lahir' => '1990-01-01',
            'alamat' => 'Bekasi',
            'no_hp' => '081298765432',
            'tgl_masuk' => now(),
            'status_gaji' => 'bulanan',
            'gaji_pokok' => 10000000,
            'gaji_per_hari' => null,
            'status' => 'aktif',
            'kuota_izin' => 12,
        ]);
    }
}
