<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Izin extends Model
{
    protected $table = 'izin';

    protected $fillable = [
        'karyawan_id',
        'tanggal',
        'jenis_izin',
        'keterangan',
        'status_approval'
    ];

    protected $casts = [
        'tanggal' => 'date'
    ];

    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class);
    }
}
