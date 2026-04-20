<?php

namespace App\Models;

use Carbon\Carbon;
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
        'total_jam' => 'decimal:2',
        'total_upah' => 'decimal:2',
    ];

    // RELASI
    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class, 'karyawan_id');
    }
}
