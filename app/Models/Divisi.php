<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Divisi extends Model
{
    protected $table = 'divisi';

    protected $fillable = [
        'kode_divisi',
        'nama_divisi',
    ];

    public function jabatan()
    {
        return $this->hasMany(Jabatan::class);
    }
}
