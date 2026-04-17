<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KategoriReimbursement extends Model
{
    use HasFactory;

    protected $table = 'kategori_reimbursement';

    protected $fillable = [
        'nama',
        'deskripsi',
        'plafon_per_bulan',
        'plafon_per_pengajuan',
        'perlu_bukti',
        'status',
    ];

    protected $casts = [
        'perlu_bukti' => 'boolean',
    ];
}
