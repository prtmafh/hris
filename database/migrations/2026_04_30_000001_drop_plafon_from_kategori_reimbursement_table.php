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
        Schema::table('kategori_reimbursement', function (Blueprint $table) {
            if (Schema::hasColumn('kategori_reimbursement', 'plafon_per_bulan')) {
                $table->dropColumn('plafon_per_bulan');
            }

            if (Schema::hasColumn('kategori_reimbursement', 'plafon_per_pengajuan')) {
                $table->dropColumn('plafon_per_pengajuan');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kategori_reimbursement', function (Blueprint $table) {
            if (!Schema::hasColumn('kategori_reimbursement', 'plafon_per_bulan')) {
                $table->decimal('plafon_per_bulan', 12, 2)->nullable()->after('deskripsi');
            }

            if (!Schema::hasColumn('kategori_reimbursement', 'plafon_per_pengajuan')) {
                $table->decimal('plafon_per_pengajuan', 12, 2)->nullable()->after('plafon_per_bulan');
            }
        });
    }
};
