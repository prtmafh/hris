<?php

namespace App\Http\Controllers\Karyawan;

use App\Http\Controllers\Controller;
use App\Models\Jabatan;
use App\Models\Karyawan;
use App\Models\PenilaianKaryawan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $user_id = $user->id;
        $karyawan = $user->karyawan()->with('jabatan')->firstOrFail();
        // $karyawan = Karyawan::with(['jabatan', 'user'])->findOrFail($id);
        $jabatan  = Jabatan::all();
        $penilaian = PenilaianKaryawan::with([
            'karyawan.jabatan',
            'penilai'
        ])->where('karyawan_id', $karyawan->id)
            ->latest()
            ->first();

        return view('karyawan.profile-saya', compact('karyawan', 'jabatan', 'penilaian'));
    }
    public function updatePassword(Request $request, $id)
    {
        $request->validate([
            'password' => 'required|min:6|confirmed',
        ]);

        $karyawan = Karyawan::with('user')->findOrFail($id);

        $karyawan->user->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with(
            'success',
            'Password berhasil diubah.'
        );
    }
}
