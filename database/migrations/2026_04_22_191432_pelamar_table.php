<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pelamar', function (Blueprint $table) {
            $table->id();

            $table->foreignId('lowongan_id')
                ->constrained('lowongan')
                ->cascadeOnDelete();

            // DATA PERSONAL
            $table->string('nama');
            $table->string('email')->index();
            $table->string('no_hp', 20)->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->text('alamat')->nullable();

            // DOKUMEN
            $table->string('cv');
            $table->string('foto')->nullable();

            // STATUS REKRUTMEN
            $table->enum('status', [
                'pending',
                'screening',
                'interview',
                'offering',
                'diterima',
                'ditolak'
            ])->default('pending');

            // INTERVIEW
            $table->dateTime('jadwal_interview')->nullable();
            $table->text('catatan_hr')->nullable();

            // TRACKING
            $table->timestamp('applied_at')->useCurrent();
            $table->timestamp('processed_at')->nullable();

            $table->timestamps();

            $table->index(['status', 'lowongan_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pelamar');
    }
};
