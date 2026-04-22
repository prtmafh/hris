<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lowongan extends Model
{
    use HasFactory;

    protected $table = 'lowongan';

    protected $fillable = [
        'jabatan_id',
        'judul',
        'deskripsi',
        'kualifikasi',
        'tanggung_jawab',
        'kuota',
        'tanggal_buka',
        'tanggal_tutup',
        'status',
    ];

    protected $casts = [
        'tanggal_buka' => 'date',
        'tanggal_tutup' => 'date',
    ];

    public function jabatan()
    {
        return $this->belongsTo(Jabatan::class, 'jabatan_id');
    }

    public function pelamar()
    {
        return $this->hasMany(Pelamar::class, 'lowongan_id');
    }
}
