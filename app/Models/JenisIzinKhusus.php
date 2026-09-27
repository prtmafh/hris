<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JenisIzinKhusus extends Model
{
    protected $table = 'jenis_izin_khusus';
    protected $fillable = ['nama', 'aktif', 'wajib_bukti'];
    protected $casts = ['aktif' => 'boolean', 'wajib_bukti' => 'boolean'];
}
