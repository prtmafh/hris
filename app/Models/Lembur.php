<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Lembur extends Model
{
    use HasFactory;

    protected $table = 'lembur';

    protected $fillable = [
        'karyawan_id',
        'tanggal',
        'jam_mulai',
        'jam_selesai',
        'total_jam',
        'total_upah',
        'status',
        'keterangan'
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    // RELASI
    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class, 'karyawan_id');
    }
}
