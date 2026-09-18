<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penggajian extends Model
{
    use HasFactory;

    protected $table = 'penggajian';

    protected $fillable = [
        'karyawan_id',
        'perusahaan_id',
        'perusahaan_snapshot',
        'periode_bulan',
        'periode_tahun',
        'tanggal_mulai',
        'tanggal_selesai',
        'total_hadir',
        'total_lembur',
        'potongan',
        'total_gaji',
        'tgl_dibayar',
        'status',
    ];

    protected $casts = [
        'perusahaan_snapshot' => 'array',
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
        'tgl_dibayar' => 'date',
    ];

    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class);
    }

    public function perusahaan()
    {
        return $this->belongsTo(Perusahaan::class);
    }

    public function details()
    {
        return $this->hasMany(DetailPenggajian::class);
    }
}
