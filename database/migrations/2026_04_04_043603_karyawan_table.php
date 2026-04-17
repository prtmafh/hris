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
        Schema::create('karyawan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->foreignId('jabatan_id')
                ->constrained('jabatan')
                ->cascadeOnDelete();
            $table->string('nama', 100);
            $table->string('nik')->unique();
            $table->date('tgl_lahir');
            $table->text('alamat')->nullable();
            $table->string('no_hp', 20)->nullable();
            $table->date('tgl_masuk');
            $table->enum('status_gaji', ['harian', 'bulanan']);
            $table->integer('gaji_pokok')->nullable();
            $table->integer('gaji_per_hari')->nullable();
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->integer('kuota_izin')->default(12);
            $table->string('foto', 255)->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('karyawan');
    }
};
