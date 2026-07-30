<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('komponen_gaji', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->enum('tipe', ['pemasukan', 'potongan']);
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });

        Schema::create('komponen_gaji_karyawan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('karyawan_id')
                ->constrained('karyawan')
                ->cascadeOnDelete();
            $table->foreignId('komponen_gaji_id')
                ->constrained('komponen_gaji')
                ->cascadeOnDelete();
            $table->enum('metode', ['nominal', 'persentase'])->default('nominal');
            $table->decimal('nilai', 15, 2);
            $table->date('tanggal_mulai')->nullable();
            $table->date('tanggal_selesai')->nullable();
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->text('keterangan')->nullable();
            $table->timestamps();

            $table->unique(['karyawan_id', 'komponen_gaji_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('komponen_gaji_karyawan');
        Schema::dropIfExists('komponen_gaji');
    }
};
