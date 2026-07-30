<?php

namespace Tests\Feature;

use App\Http\Controllers\Admin\DataGajiController;
use App\Models\Karyawan;
use App\Models\KomponenGaji;
use App\Models\KomponenGajiKaryawan;
use App\Models\Penggajian;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class KomponenGajiPenggajianTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        if (! extension_loaded('pdo_sqlite')) {
            $this->markTestSkipped('Driver pdo_sqlite diperlukan untuk database PHPUnit in-memory.');
        }

        parent::setUp();
    }

    public function test_generate_gaji_memasukkan_komponen_khusus_karyawan_ke_snapshot(): void
    {
        $jabatanId = DB::table('jabatan')->insertGetId([
            'nama_jabatan' => 'Staff',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $roleId = DB::table('role')->insertGetId([
            'nama_role' => 'karyawan',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $karyawan = Karyawan::create([
            'role_id' => $roleId,
            'jabatan_id' => $jabatanId,
            'nama' => 'Karyawan Uji',
            'nik' => 'TEST-001',
            'password' => 'password',
            'tgl_lahir' => '1995-01-01',
            'tgl_masuk' => '2020-01-01',
            'status_gaji' => 'bulanan',
            'gaji_pokok' => 10_000_000,
            'status' => 'aktif',
        ]);

        $transportasi = KomponenGaji::create([
            'nama' => 'Tunjangan Transportasi',
            'tipe' => 'pemasukan',
            'status' => 'aktif',
        ]);
        $bpjsPerusahaan = KomponenGaji::create([
            'nama' => 'Tunjangan BPJS',
            'tipe' => 'pemasukan',
            'status' => 'aktif',
        ]);
        $pph21 = KomponenGaji::create([
            'nama' => 'PPh Pasal 21',
            'tipe' => 'potongan',
            'status' => 'aktif',
        ]);

        KomponenGajiKaryawan::create([
            'karyawan_id' => $karyawan->id,
            'komponen_gaji_id' => $transportasi->id,
            'metode' => 'nominal',
            'nilai' => 500_000,
            'status' => 'aktif',
        ]);
        KomponenGajiKaryawan::create([
            'karyawan_id' => $karyawan->id,
            'komponen_gaji_id' => $bpjsPerusahaan->id,
            'metode' => 'persentase',
            'nilai' => 10,
            'status' => 'aktif',
        ]);
        KomponenGajiKaryawan::create([
            'karyawan_id' => $karyawan->id,
            'komponen_gaji_id' => $pph21->id,
            'metode' => 'persentase',
            'nilai' => 2,
            'status' => 'aktif',
        ]);

        $request = Request::create('/admin/penggajian/generate', 'POST', [
            'bulan' => 7,
            'tahun' => 2026,
        ]);
        app(DataGajiController::class)->generate($request);

        $penggajian = Penggajian::with('details')->sole();

        $this->assertSame(200_000.0, (float) $penggajian->potongan);
        $this->assertSame(11_300_000.0, (float) $penggajian->total_gaji);
        $this->assertSame(
            500_000.0,
            (float) $penggajian->details->firstWhere('keterangan', 'Tunjangan Transportasi')->jumlah
        );
        $this->assertSame(
            1_000_000.0,
            (float) $penggajian->details->firstWhere('keterangan', 'Tunjangan BPJS (10% dari gaji dasar)')->jumlah
        );
        $this->assertSame(
            200_000.0,
            (float) $penggajian->details->firstWhere('keterangan', 'PPh Pasal 21 (2% dari gaji dasar)')->jumlah
        );
    }
}
