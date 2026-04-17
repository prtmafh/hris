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
        Schema::create('jadwal_kerja', function (Blueprint $table) {
            $table->id();
            $table->enum('hari', [
                'senin',
                'selasa',
                'rabu',
                'kamis',
                'jumat',
                'sabtu',
                'minggu'
            ])->unique();
            $table->boolean('is_hari_kerja')->default(true);
            $table->time('jam_masuk')->nullable();
            $table->time('jam_pulang')->nullable();
            $table->integer('toleransi_telat_menit')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jadwal_kerja');
    }
};
