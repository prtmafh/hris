<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPenggajian extends Model
{
    use HasFactory;

    protected $table = 'detail_penggajian';

    protected $fillable = [
        'penggajian_id',
        'keterangan',
        'jumlah',
        'tipe',
    ];

    public function penggajian()
    {
        return $this->belongsTo(Penggajian::class);
    }
}
