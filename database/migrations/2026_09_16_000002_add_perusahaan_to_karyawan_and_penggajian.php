<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('karyawan', function (Blueprint $table) {
            $table->foreignId('perusahaan_id')->nullable()->after('jabatan_id')
                ->constrained('perusahaan')->nullOnDelete();
        });
        Schema::table('penggajian', function (Blueprint $table) {
            $table->foreignId('perusahaan_id')->nullable()->after('karyawan_id')
                ->constrained('perusahaan')->nullOnDelete();
            $table->json('perusahaan_snapshot')->nullable()->after('perusahaan_id');
        });
    }

    public function down(): void
    {
        Schema::table('penggajian', function (Blueprint $table) {
            $table->dropForeign(['perusahaan_id']);
            $table->dropColumn(['perusahaan_id', 'perusahaan_snapshot']);
        });
        Schema::table('karyawan', function (Blueprint $table) {
            $table->dropForeign(['perusahaan_id']);
            $table->dropColumn('perusahaan_id');
        });
    }
};
