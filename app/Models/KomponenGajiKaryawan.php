<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KomponenGajiKaryawan extends Model
{
    use HasFactory;

    protected $table = 'komponen_gaji_karyawan';

    protected $fillable = [
        'karyawan_id',
        'komponen_gaji_id',
        'metode',
        'nilai',
        'tanggal_mulai',
        'tanggal_selesai',
        'status',
        'keterangan',
    ];

    protected function casts(): array
    {
        return [
            'nilai' => 'decimal:2',
            'tanggal_mulai' => 'date',
            'tanggal_selesai' => 'date',
        ];
    }

    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class);
    }

    public function komponen()
    {
        return $this->belongsTo(KomponenGaji::class, 'komponen_gaji_id');
    }
}
