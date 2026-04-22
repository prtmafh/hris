<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pelamar extends Model
{
    use HasFactory;

    protected $table = 'pelamar';

    protected $fillable = [
        'lowongan_id',
        'nama',
        'email',
        'no_hp',
        'tanggal_lahir',
        'alamat',
        'cv',
        'foto',
        'status',
        'jadwal_interview',
        'catatan_hr',
        'applied_at',
        'processed_at',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'jadwal_interview' => 'datetime',
        'applied_at' => 'datetime',
        'processed_at' => 'datetime',
    ];

    public function lowongan()
    {
        return $this->belongsTo(Lowongan::class, 'lowongan_id');
    }
}
