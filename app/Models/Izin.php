<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Izin extends Model
{
    protected $table = 'izin';

    protected $fillable = [
        'karyawan_id',
        'tanggal',
        'jenis_pengajuan',
        'jenis_izin',
        'jenis_izin_khusus_id',
        'tanggal_mulai',
        'tanggal_selesai',
        'jumlah_hari',
        'keterangan',
        'bukti',
        'status_approval'
        ,'catatan_approval',
        'disetujui_oleh',
        'tanggal_approval',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
        'tanggal_approval' => 'datetime',
    ];

    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class);
    }

    public function approver()
    {
        return $this->belongsTo(Karyawan::class, 'disetujui_oleh');
    }

    public function jenisIzinKhusus()
    {
        return $this->belongsTo(JenisIzinKhusus::class);
    }
}
