<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('jenis_izin_khusus')) {
            Schema::create('jenis_izin_khusus', function (Blueprint $table) {
                $table->id();
                $table->string('nama')->unique();
                $table->boolean('aktif')->default(true);
                $table->boolean('wajib_bukti')->default(false);
                $table->timestamps();
            });
        }

        $now = now();
        $defaultJenis = ['Keluarga inti meninggal dunia', 'Orang tua meninggal dunia', 'Pasangan meninggal dunia', 'Anak meninggal dunia', 'Melahirkan', 'Menikah', 'Menikahkan anak', 'Khitanan anak', 'Kelahiran anak', 'Keperluan khusus lainnya'];
        foreach ($defaultJenis as $nama) {
            DB::table('jenis_izin_khusus')->updateOrInsert(['nama' => $nama], ['aktif' => true, 'wajib_bukti' => false, 'updated_at' => $now]);
        }

        Schema::table('izin', function (Blueprint $table) {
            if (!Schema::hasColumn('izin', 'jenis_pengajuan')) {
                $table->string('jenis_pengajuan')->default('cuti')->after('karyawan_id');
            }
            if (!Schema::hasColumn('izin', 'tanggal_mulai')) {
                $table->date('tanggal_mulai')->nullable()->after('tanggal');
            }
            if (!Schema::hasColumn('izin', 'tanggal_selesai')) {
                $table->date('tanggal_selesai')->nullable()->after('tanggal_mulai');
            }
            if (!Schema::hasColumn('izin', 'jumlah_hari')) {
                $table->unsignedInteger('jumlah_hari')->default(1)->after('tanggal_selesai');
            }
            if (!Schema::hasColumn('izin', 'jenis_izin_khusus_id')) {
                $table->foreignId('jenis_izin_khusus_id')->nullable()->after('jenis_izin')->constrained('jenis_izin_khusus')->nullOnDelete();
            }
            if (!Schema::hasColumn('izin', 'bukti')) {
                $table->string('bukti')->nullable()->after('keterangan');
            }
            if (!Schema::hasColumn('izin', 'catatan_approval')) {
                $table->text('catatan_approval')->nullable()->after('status_approval');
            }
            if (!Schema::hasColumn('izin', 'disetujui_oleh')) {
                $table->foreignId('disetujui_oleh')->nullable()->after('catatan_approval')->constrained('karyawan')->nullOnDelete();
            }
            if (!Schema::hasColumn('izin', 'tanggal_approval')) {
                $table->timestamp('tanggal_approval')->nullable()->after('disetujui_oleh');
            }
            if (!Schema::hasColumn('izin', 'kuota_dikurangi')) {
                $table->boolean('kuota_dikurangi')->default(false)->after('tanggal_approval');
            }
        });

        DB::table('izin')->orderBy('id')->each(function ($izin) {
            $payload = [
                'jenis_pengajuan' => $izin->jenis_izin === 'cuti' ? 'cuti' : 'izin_khusus',
                'tanggal_mulai' => $izin->tanggal,
                'tanggal_selesai' => $izin->tanggal,
                'jumlah_hari' => 1,
            ];

            DB::table('izin')->where('id', $izin->id)->update($payload);
        });
    }

    public function down(): void
    {
        if (Schema::hasTable('izin')) {
            Schema::table('izin', function (Blueprint $table) {
                if (Schema::hasColumn('izin', 'jenis_izin_khusus_id')) {
                    $table->dropForeign(['jenis_izin_khusus_id']);
                }
                if (Schema::hasColumn('izin', 'disetujui_oleh')) {
                    $table->dropForeign(['disetujui_oleh']);
                }
            });

            Schema::table('izin', function (Blueprint $table) {
                $table->dropColumn(array_filter([
                    Schema::hasColumn('izin', 'jenis_pengajuan') ? 'jenis_pengajuan' : null,
                    Schema::hasColumn('izin', 'tanggal_mulai') ? 'tanggal_mulai' : null,
                    Schema::hasColumn('izin', 'tanggal_selesai') ? 'tanggal_selesai' : null,
                    Schema::hasColumn('izin', 'jumlah_hari') ? 'jumlah_hari' : null,
                    Schema::hasColumn('izin', 'jenis_izin_khusus_id') ? 'jenis_izin_khusus_id' : null,
                    Schema::hasColumn('izin', 'bukti') ? 'bukti' : null,
                    Schema::hasColumn('izin', 'catatan_approval') ? 'catatan_approval' : null,
                    Schema::hasColumn('izin', 'disetujui_oleh') ? 'disetujui_oleh' : null,
                    Schema::hasColumn('izin', 'tanggal_approval') ? 'tanggal_approval' : null,
                    Schema::hasColumn('izin', 'kuota_dikurangi') ? 'kuota_dikurangi' : null,
                ]));
            });
        }

        Schema::dropIfExists('jenis_izin_khusus');
    }
};
