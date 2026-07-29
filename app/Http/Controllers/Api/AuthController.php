<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Karyawan;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request): JsonResponse
    {
        $credentials = $request->validate([
            'nik' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $karyawan = Karyawan::with(['role', 'jabatan'])
            ->where('nik', $credentials['nik'])
            ->first();

        if (
            !$karyawan ||
            !Hash::check($credentials['password'], $karyawan->password)
        ) {
            return response()->json([
                'status' => false,
                'message' => 'NIK atau password salah.',
            ], 422);
        }

        if ($karyawan->status !== 'aktif') {
            return response()->json([
                'status' => false,
                'message' => 'Akun Anda tidak aktif.',
            ], 403);
        }

        // if (!in_array($karyawan->role?->nama_role, [
        //     'karyawan',
        //     'admin_kecil',
        // ], true)) {
        //     return response()->json([
        //         'status' => false,
        //         'message' => 'Akun ini tidak memiliki akses ke aplikasi mobile.',
        //     ], 403);
        // }

        $karyawan->tokens()->delete();

        $token = $karyawan
            ->createToken('hris-mobile')
            ->plainTextToken;

        return response()->json([
            'status' => true,
            'message' => 'Login berhasil.',
            'data' => [
                'token' => $token,
                'token_type' => 'Bearer',
                'karyawan' => [
                    'id' => $karyawan->id,
                    'nik' => $karyawan->nik,
                    'nama' => $karyawan->nama,
                    'role_id' => $karyawan->role_id,
                    'role' => $karyawan->role?->nama_role,
                    'jabatan' => $karyawan->jabatan?->nama_jabatan,
                    'status_gaji' => $karyawan->status_gaji,
                    'foto' => $karyawan->foto
                        ? asset('storage/' . $karyawan->foto)
                        : null,
                ],
            ],
        ]);
    }

    public function me(Request $request): JsonResponse
    {
        /** @var Karyawan $karyawan */
        $karyawan = $request->user();

        $karyawan->load(['role', 'jabatan']);

        return response()->json([
            'status' => true,
            'message' => 'Data pengguna berhasil diambil.',
            'data' => [
                'id' => $karyawan->id,
                'nik' => $karyawan->nik,
                'nama' => $karyawan->nama,
                'role_id' => $karyawan->role_id,
                'role' => $karyawan->role?->nama_role,
                'jabatan' => $karyawan->jabatan?->nama_jabatan,
                'tgl_lahir' => $karyawan->tgl_lahir?->format('Y-m-d'),
                'alamat' => $karyawan->alamat,
                'no_hp' => $karyawan->no_hp,
                'tgl_masuk' => $karyawan->tgl_masuk?->format('Y-m-d'),
                'status_gaji' => $karyawan->status_gaji,
                'status' => $karyawan->status,
                'kuota_izin' => $karyawan->kuota_izin,
                'foto' => $karyawan->foto
                    ? asset('storage/' . $karyawan->foto)
                    : null,
            ],
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()
            ->currentAccessToken()
            ?->delete();

        return response()->json([
            'status' => true,
            'message' => 'Logout berhasil.',
        ]);
    }
}
