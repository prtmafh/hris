<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JadwalKerja extends Model
{
    use HasFactory;

    protected $table = 'jadwal_kerja';

    protected $fillable = [
        'hari',
        'is_hari_kerja',
        'jam_masuk',
        'jam_pulang',
        'toleransi_telat_menit',
    ];

    protected $casts = [
        'is_hari_kerja' => 'boolean',
    ];
}
