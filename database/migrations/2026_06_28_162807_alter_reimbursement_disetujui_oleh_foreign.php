<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reimbursement', function (Blueprint $table) {
            $table->dropForeign(['disetujui_oleh']);

            $table->foreign('disetujui_oleh')
                ->references('id')
                ->on('karyawan')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('reimbursement', function (Blueprint $table) {
            $table->dropForeign(['disetujui_oleh']);

            $table->foreign('disetujui_oleh')
                ->references('id')
                ->on('users')
                ->nullOnDelete();
        });
    }
};
