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
        Schema::dropIfExists('peserta_training');
        Schema::dropIfExists('training');
        Schema::dropIfExists('jadwal_kerja');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
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
                'minggu',
            ])->unique();
            $table->boolean('is_hari_kerja')->default(true);
            $table->time('jam_masuk')->nullable();
            $table->time('jam_pulang')->nullable();
            $table->integer('toleransi_telat_menit')->default(0);
            $table->timestamps();
        });

        Schema::create('training', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->text('deskripsi')->nullable();
            $table->enum('jenis', [
                'internal',
                'eksternal',
                'online',
            ])->default('internal');
            $table->string('penyelenggara')->nullable();
            $table->string('lokasi')->nullable();
            $table->date('tgl_mulai');
            $table->date('tgl_selesai');
            $table->integer('durasi_jam')->nullable();
            $table->decimal('biaya', 12, 2)->default(0);
            $table->integer('kuota_peserta')->nullable();
            $table->enum('status', [
                'rencana',
                'berlangsung',
                'selesai',
                'batal',
            ])->default('rencana');
            $table->timestamps();

            $table->index('status');
            $table->index('tgl_mulai');
        });

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
                'tidak_hadir',
            ])->default('terdaftar');
            $table->decimal('nilai', 5, 2)->nullable();
            $table->string('sertifikat')->nullable();
            $table->text('catatan')->nullable();

            $table->timestamps();

            $table->unique(['training_id', 'karyawan_id']);
        });
    }
};
