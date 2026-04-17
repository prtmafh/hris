<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HariLibur extends Model
{
    use HasFactory;

    protected $table = 'hari_libur';

    protected $fillable = [
        'tanggal',
        'nama',
        'jenis',
        'keterangan',
        'berulang_tahunan',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'berulang_tahunan' => 'boolean',
    ];
}
