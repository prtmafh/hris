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
        Schema::create('training', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->text('deskripsi')->nullable();
            $table->enum('jenis', [
                'internal',
                'eksternal',
                'online'
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
                'batal'
            ])->default('rencana');
            $table->timestamps();

            $table->index('status');
            $table->index('tgl_mulai');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('training');
    }
};
