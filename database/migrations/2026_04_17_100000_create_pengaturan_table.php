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
        Schema::create('pengaturan', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->enum('tipe', [
                'string',
                'integer',
                'decimal',
                'boolean',
                'json',
                'time',
                'date'
            ])->default('string');
            $table->string('grup')->default('umum');
            $table->string('label')->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();

            $table->index('grup');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengaturan');
    }
};
