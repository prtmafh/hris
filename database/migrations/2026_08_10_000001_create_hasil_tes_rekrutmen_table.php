<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hasil_tes_rekrutmen', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pelamar_id')->constrained('pelamar')->cascadeOnDelete();
            $table->string('jenis_tes', 100);
            $table->date('tanggal_tes');
            $table->decimal('nilai', 8, 2)->nullable();
            $table->enum('hasil', ['lulus', 'tidak_lulus', 'dipertimbangkan']);
            $table->string('penguji', 150)->nullable();
            $table->text('catatan')->nullable();
            $table->timestamps();

            $table->index(['pelamar_id', 'tanggal_tes']);
            $table->index(['jenis_tes', 'hasil']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hasil_tes_rekrutmen');
    }
};
