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
        Schema::create('peserta_training', function (Blueprint $table) {
            $table->id();
            $table->foreignId('training_id')
                ->constrained('training')
                ->cascadeOnDelete();
            $table->foreignId('karyawan_id')
                ->constrained('karyawan')
                ->cascadeOnDelete();

            $table->enum('status_kehadiran', [
                'terdaftar',
                'hadir',
                'tidak_hadir'
            ])->default('terdaftar');
            $table->decimal('nilai', 5, 2)->nullable();
            $table->string('sertifikat')->nullable();
            $table->text('catatan')->nullable();

            $table->timestamps();

            $table->unique(['training_id', 'karyawan_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('peserta_training');
    }
};
