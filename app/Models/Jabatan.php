<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Karyawan;

#[Fillable([
    'id',
    'nama_jabatan',

])]

class Jabatan extends Model
{
    use HasFactory;

    protected $table = 'jabatan';

    public function karyawan()
    {
        return $this->hasMany(Karyawan::class);
    }
}
