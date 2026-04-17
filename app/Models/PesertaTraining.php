<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PesertaTraining extends Model
{
    use HasFactory;

    protected $table = 'peserta_training';

    protected $fillable = [
        'training_id',
        'karyawan_id',
        'status_kehadiran',
        'nilai',
        'sertifikat',
        'catatan',
    ];

    public function training()
    {
        return $this->belongsTo(Training::class);
    }

    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class);
    }
}
