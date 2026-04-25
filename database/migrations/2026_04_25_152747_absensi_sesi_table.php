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
        Schema::create('absensi_sesi', function (Blueprint $table) {
            $table->id();

            $table->foreignId('absensi_id')
                ->constrained('absensi')
                ->cascadeOnDelete();

            $table->unsignedTinyInteger('sesi_ke'); //1,2,3

            // actual attendance
            $table->time('jam_checkin')->nullable();
            $table->time('jam_checkout')->nullable();

            $table->enum('status', [
                'hadir',
                'terlambat',
                'izin',
                'alpha'
            ])->default('alpha');

            $table->decimal('latitude_masuk', 10, 7)->nullable();
            $table->decimal('longitude_masuk', 10, 7)->nullable();

            $table->decimal('latitude_keluar', 10, 7)->nullable();
            $table->decimal('longitude_keluar', 10, 7)->nullable();

            $table->string('foto_masuk')->nullable();
            $table->string('foto_keluar')->nullable();

            $table->text('keterangan')->nullable();

            $table->timestamps();

            $table->unique([
                'absensi_id',
                'sesi_ke'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('absensi_sesi');
    }
};
