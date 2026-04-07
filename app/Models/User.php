<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable([
    'nik',
    'password',
    'role',
    'status'
])]
#[Hidden([
    'password',
    'remember_token'
])]
class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Relasi ke karyawan (1:1)
     */
    public function karyawan()
    {
        return $this->hasOne(Karyawan::class);
    }

    /**
     * Cast attribute
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }
}
