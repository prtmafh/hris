<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KomponenGaji extends Model
{
    use HasFactory;

    protected $table = 'komponen_gaji';

    protected $fillable = [
        'nama',
        'tipe',
        'status',
        'keterangan',
    ];

    public function pengaturanKaryawan()
    {
        return $this->hasMany(KomponenGajiKaryawan::class);
    }
}
