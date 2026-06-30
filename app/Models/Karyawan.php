<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

class Karyawan extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'karyawan';

    protected $fillable = [
        'role_id',
        'jabatan_id',
        'nama',
        'nik',
        'password',
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

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            // 'tgl_lahir' => 'date',
            // 'tgl_masuk' => 'date',
        ];
    }

    /**
     * Username yang digunakan untuk login.
     */
    public function getAuthIdentifierName()
    {
        return 'nik';
    }

    /**
     * Relasi Role
     */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Relasi Jabatan
     */
    public function jabatan()
    {
        return $this->belongsTo(Jabatan::class);
    }

    /**
     * Relasi Absensi
     */
    public function absensi()
    {
        return $this->hasMany(Absensi::class);
    }
}
