<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HasilTesRekrutmen extends Model
{
    use HasFactory;

    protected $table = 'hasil_tes_rekrutmen';

    protected $fillable = [
        'pelamar_id',
        'jenis_tes',
        'tanggal_tes',
        'nilai',
        'hasil',
        'penguji',
        'catatan',
    ];

    protected $casts = [
        'tanggal_tes' => 'date',
        'nilai' => 'decimal:2',
    ];

    public function pelamar()
    {
        return $this->belongsTo(Pelamar::class, 'pelamar_id');
    }
}
