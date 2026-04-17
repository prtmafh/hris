<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Training extends Model
{
    use HasFactory;

    protected $table = 'training';

    protected $fillable = [
        'judul',
        'deskripsi',
        'jenis',
        'penyelenggara',
        'lokasi',
        'tgl_mulai',
        'tgl_selesai',
        'durasi_jam',
        'biaya',
        'kuota_peserta',
        'status',
    ];

    protected $casts = [
        'tgl_mulai' => 'date',
        'tgl_selesai' => 'date',
    ];

    public function peserta()
    {
        return $this->hasMany(PesertaTraining::class);
    }
}
