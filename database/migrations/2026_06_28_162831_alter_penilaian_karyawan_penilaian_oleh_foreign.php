<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('penilaian_karyawan', function (Blueprint $table) {
            $table->dropForeign(['penilaian_oleh']);

            $table->foreign('penilaian_oleh')
                ->references('id')
                ->on('karyawan')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('penilaian_karyawan', function (Blueprint $table) {
            $table->dropForeign(['penilaian_oleh']);

            $table->foreign('penilaian_oleh')
                ->references('id')
                ->on('users')
                ->cascadeOnDelete();
        });
    }
};
