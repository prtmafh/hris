<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reimbursement extends Model
{
    use HasFactory;

    protected $table = 'reimbursement';

    protected $fillable = [
        'karyawan_id',
        'kategori_reimbursement_id',
        'tanggal_pengajuan',
        'tanggal_transaksi',
        'judul',
        'deskripsi',
        'jumlah_diajukan',
        'jumlah_disetujui',
        'bukti',
        'status',
        'catatan_approval',
        'disetujui_oleh',
        'tgl_disetujui',
        'tgl_dibayar',
    ];

    protected $casts = [
        'tanggal_pengajuan' => 'date',
        'tanggal_transaksi' => 'date',
        'tgl_disetujui' => 'datetime',
        'tgl_dibayar' => 'date',
    ];

    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class);
    }

    public function kategoriReimbursement()
    {
        return $this->belongsTo(KategoriReimbursement::class, 'kategori_reimbursement_id');
    }

    public function penyetuju()
    {
        return $this->belongsTo(User::class, 'disetujui_oleh');
    }
}
