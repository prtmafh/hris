<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Foreign key karyawan_id sebelumnya bergantung pada prefix unique index lama.
        // Sediakan index khusus FK sebelum unique index tersebut dilepas.
        Schema::table('penggajian', function (Blueprint $table) {
            $table->index('karyawan_id', 'penggajian_karyawan_id_index');
        });

        Schema::table('penggajian', function (Blueprint $table) {
            $table->dropUnique('penggajian_karyawan_id_periode_bulan_periode_tahun_unique');
        });

        Schema::table('penggajian', function (Blueprint $table) {
            $table->unique(
                ['karyawan_id', 'tanggal_mulai', 'tanggal_selesai'],
                'penggajian_karyawan_rentang_harian_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::table('penggajian', function (Blueprint $table) {
            $table->dropUnique('penggajian_karyawan_rentang_harian_unique');
        });

        Schema::table('penggajian', function (Blueprint $table) {
            $table->unique(
                ['karyawan_id', 'periode_bulan', 'periode_tahun'],
                'penggajian_karyawan_id_periode_bulan_periode_tahun_unique'
            );
        });

        Schema::table('penggajian', function (Blueprint $table) {
            $table->dropIndex('penggajian_karyawan_id_index');
        });
    }
};
