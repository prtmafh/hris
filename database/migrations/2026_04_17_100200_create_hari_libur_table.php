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
        Schema::create('hari_libur', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->string('nama');
            $table->enum('jenis', [
                'nasional',
                'cuti_bersama',
                'perusahaan'
            ])->default('nasional');
            $table->text('keterangan')->nullable();
            $table->boolean('berulang_tahunan')->default(false);
            $table->timestamps();

            $table->index('tanggal');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hari_libur');
    }
};
