<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PenilaianKaryawan extends Model
{
    protected $table = 'penilaian_karyawan';

    protected $fillable = [
        'karyawan_id',
        'penilaian_oleh',
        'periode_bulan',
        'periode_tahun',
        'nilai_kehadiran',
        'nilai_kedisiplinan',
        'nilai_kinerja',
        'nilai_total',
        'grade',
        'catatan',
    ];

    protected $casts = [
        'nilai_kehadiran'  => 'decimal:2',
        'nilai_kedisiplinan' => 'decimal:2',
        'nilai_kinerja'    => 'decimal:2',
        'nilai_total'      => 'decimal:2',
    ];

    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class);
    }

    public function penilai()
    {
        return $this->belongsTo(User::class, 'penilaian_oleh');
    }

    public static function hitungNilaiTotal(float $kehadiran, float $kedisiplinan, float $kinerja): float
    {
        return round(($kehadiran * 0.4) + ($kedisiplinan * 0.3) + ($kinerja * 0.3), 2);
    }

    public static function hitungGrade(float $nilaiTotal): string
    {
        if ($nilaiTotal >= 90) return 'A';
        if ($nilaiTotal >= 75) return 'B';
        if ($nilaiTotal >= 60) return 'C';
        return 'D';
    }

    public function getNamaBulanAttribute(): string
    {
        $bulan = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret',
            4 => 'April', 5 => 'Mei', 6 => 'Juni',
            7 => 'Juli', 8 => 'Agustus', 9 => 'September',
            10 => 'Oktober', 11 => 'November', 12 => 'Desember',
        ];
        return $bulan[$this->periode_bulan] ?? '-';
    }
}
