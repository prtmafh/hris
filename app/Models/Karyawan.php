<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// #[Fillable([
//     'user_id',
//     'jabatan_id',
//     'nama',
//     'alamat',
//     'no_hp',
//     'tgl_masuk',
//     'status_gaji',
//     'gaji_pokok',
//     'gaji_per_hari',
//     'status',
//     'kuota_izin',
//     'foto'
// ])]
class Karyawan extends Model
{
    use HasFactory;

    protected $table = 'karyawan';
    protected $fillable = [
        'user_id',
        'jabatan_id',
        'nama',
        'nik',
        'tgl_lahir',
        'alamat',
        'no_hp',
        'tgl_masuk',
        'status_gaji',
        'gaji_pokok',
        'gaji_per_hari',
        'status',
        'kuota_izin',
        'foto',
    ];
    /**
     * Relasi ke user (1:1)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke jabatan
     */
    public function jabatan()
    {
        return $this->belongsTo(Jabatan::class);
    }

    /**
     * Relasi ke absensi
     */
    public function absensi()
    {
        return $this->hasMany(Absensi::class);
    }

    /**
     * Relasi ke izin
     */
    // public function izin()
    // {
    //     return $this->hasMany(Izin::class);
    // }

    /**
     * Relasi ke lembur
     */
    // public function lembur()
    // {
    //     return $this->hasMany(Lembur::class);
    // }

    /**
     * Relasi ke penggajian
     */
    // public function penggajian()
    // {
    //     return $this->hasMany(Penggajian::class);
    // }
}
