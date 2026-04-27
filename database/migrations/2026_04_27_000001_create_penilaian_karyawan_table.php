<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('penilaian_karyawan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('karyawan_id')->constrained('karyawan')->onDelete('cascade');
            $table->foreignId('penilaian_oleh')->constrained('users')->onDelete('cascade');
            $table->unsignedTinyInteger('periode_bulan');
            $table->unsignedSmallInteger('periode_tahun');
            $table->decimal('nilai_kehadiran', 5, 2)->default(0);
            $table->decimal('nilai_kedisiplinan', 5, 2)->default(0);
            $table->decimal('nilai_kinerja', 5, 2)->default(0);
            $table->decimal('nilai_total', 5, 2)->default(0);
            $table->enum('grade', ['A', 'B', 'C', 'D'])->default('D');
            $table->text('catatan')->nullable();
            $table->timestamps();

            $table->unique(['karyawan_id', 'periode_bulan', 'periode_tahun'], 'unique_penilaian_periode');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penilaian_karyawan');
    }
};
