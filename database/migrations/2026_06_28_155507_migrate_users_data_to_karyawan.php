<?php

use App\Models\Karyawan;
use App\Models\Role;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        $mapping = Role::pluck('id', 'nama_role');

        foreach (Karyawan::with('user')->get() as $karyawan) {
            if (!$karyawan->user) {
                continue;
            }

            $karyawan->update([
                'password' => $karyawan->user->password,
                'role_id' => $mapping[$karyawan->user->role] ?? null,
            ]);
        }
    }

    public function down(): void
    {
        //
    }
};
