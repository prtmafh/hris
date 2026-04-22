<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lowongan', function (Blueprint $table) {
            $table->id();

            $table->foreignId('jabatan_id')
                ->constrained('jabatan')
                ->cascadeOnDelete();

            $table->string('judul');
            $table->text('deskripsi');
            $table->text('kualifikasi');
            $table->text('tanggung_jawab')->nullable();

            $table->integer('kuota')->default(1);

            $table->date('tanggal_buka');
            $table->date('tanggal_tutup');

            $table->enum('status', ['draft', 'aktif', 'ditutup'])
                ->default('draft');

            $table->timestamps();

            $table->index(['status', 'tanggal_tutup']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lowongan');
    }
};
