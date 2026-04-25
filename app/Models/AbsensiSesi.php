<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AbsensiSesi extends Model
{
    protected $table = 'absensi_sesi';

    protected $fillable = [
        'absensi_id',
        'sesi_ke',
        'jam_checkin',
        'jam_checkout',
        'status',
        'latitude_masuk',
        'longitude_masuk',
        'latitude_keluar',
        'longitude_keluar',
        'foto_masuk',
        'foto_keluar',
        'keterangan',
    ];

    public function absensi()
    {
        return $this->belongsTo(Absensi::class);
    }
}
