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

    public function hasilTes()
    {
        return $this->hasMany(HasilTesRekrutmen::class, 'pelamar_id');
    }

    public function rekomendasiHasilTes(): string
    {
        $hasilTes = $this->relationLoaded('hasilTes')
            ? $this->hasilTes
            : $this->hasilTes()->get();

        if ($hasilTes->isEmpty()) {
            return 'belum_ada';
        }

        if ($hasilTes->contains('hasil', 'tidak_lulus')) {
            return 'tidak_direkomendasikan';
        }

        if ($hasilTes->contains('hasil', 'dipertimbangkan')) {
            return 'dipertimbangkan';
        }

        return 'direkomendasikan';
    }
}
