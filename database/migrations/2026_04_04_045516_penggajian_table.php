<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('penggajian', function (Blueprint $table) {
            $table->id();

            $table->foreignId('karyawan_id')
                ->constrained('karyawan')
                ->cascadeOnDelete();
            $table->integer('periode_bulan');
            $table->integer('periode_tahun');
            $table->integer('total_hadir')->default(0);
            $table->decimal('total_lembur', 12, 2)->default(0);
            $table->decimal('potongan', 12, 2)->default(0);
            $table->decimal('total_gaji', 12, 2);
            $table->date('tgl_dibayar')->nullable();
            $table->enum('status', ['proses', 'dibayar'])
                ->default('proses');
            $table->timestamps();
            $table->unique(['karyawan_id', 'periode_bulan', 'periode_tahun']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penggajian');
    }
};
