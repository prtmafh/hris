<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'nik' => '0001',
                'password' => Hash::make('password123'),
                'role' => 'admin',
                'status' => 'aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nik' => '1001',
                'password' => Hash::make('password123'),
                'role' => 'karyawan',
                'status' => 'aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nik' => '1002',
                'password' => Hash::make('password123'),
                'role' => 'karyawan',
                'status' => 'nonaktif',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
