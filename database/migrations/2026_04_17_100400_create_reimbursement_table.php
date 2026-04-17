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
        Schema::create('reimbursement', function (Blueprint $table) {
            $table->id();

            $table->foreignId('karyawan_id')
                ->constrained('karyawan')
                ->cascadeOnDelete();
            $table->foreignId('kategori_reimbursement_id')
                ->constrained('kategori_reimbursement')
                ->restrictOnDelete();

            $table->date('tanggal_pengajuan');
            $table->date('tanggal_transaksi');
            $table->string('judul');
            $table->text('deskripsi')->nullable();
            $table->decimal('jumlah_diajukan', 12, 2);
            $table->decimal('jumlah_disetujui', 12, 2)->nullable();
            $table->string('bukti')->nullable();

            $table->enum('status', [
                'pending',
                'disetujui',
                'ditolak',
                'dibayar'
            ])->default('pending');

            $table->text('catatan_approval')->nullable();
            $table->foreignId('disetujui_oleh')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();
            $table->timestamp('tgl_disetujui')->nullable();
            $table->date('tgl_dibayar')->nullable();

            $table->timestamps();

            $table->index(['karyawan_id', 'status']);
            $table->index('tanggal_pengajuan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reimbursement');
    }
};
