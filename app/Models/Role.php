<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Role extends Model
{
    use HasFactory;

    protected $table = 'role';

    protected $fillable = [
        'nama_role',
    ];

    public function karyawan()
    {
        return $this->hasMany(Karyawan::class);
    }
}
