<?php

namespace Tests\Feature;

use App\Models\Izin;
use App\Models\Karyawan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class IzinKuotaFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_sisa_kuota_cuti_menunjukkan_hasil_yang_benar_setelah_pengajuan_disetujui(): void
    {
        $karyawan = Karyawan::create([
            'perusahaan_id' => 1,
            'role_id' => 3,
            'jabatan_id' => 1,
            'nama' => 'Test Karyawan',
            'nik' => '123456',
            'password' => 'password',
            'status' => 'aktif',
            'status_gaji' => 'bulanan',
            'kuota_izin' => 12,
        ]);

        Izin::create([
            'karyawan_id' => $karyawan->id,
            'jenis_pengajuan' => 'cuti',
            'jenis_izin' => 'Cuti Tahunan',
            'tanggal_mulai' => '2026-09-01',
            'tanggal_selesai' => '2026-09-03',
            'jumlah_hari' => 3,
            'keterangan' => 'Test cuti',
            'status_approval' => 'disetujui',
        ]);

        $this->actingAs($karyawan);

        $response = $this->get('/karyawan/izin-saya');

        $response->assertOk();
        $response->assertViewHas('sisaIzin', 9);
    }
}
