<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('role')->insert([
            [
                'nama_role' => 'admin',
                // 'keterangan' => 'Administrator sistem',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_role' => 'pimpinan',
                // 'keterangan' => 'Pimpinan perusahaan',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_role' => 'karyawan',
                // 'keterangan' => 'Karyawan perusahaan',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
