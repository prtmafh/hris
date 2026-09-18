<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Perusahaan extends Model
{
    use HasFactory;

    protected $table = 'perusahaan';
    protected $fillable = ['nama_perusahaan', 'nama_singkat', 'logo', 'nib', 'npwp', 'alamat', 'no_telepon', 'email', 'website', 'deskripsi', 'status'];

    public function karyawan() { return $this->hasMany(Karyawan::class); }
    public function penggajian() { return $this->hasMany(Penggajian::class); }
}
