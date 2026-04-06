<?php

namespace App\Http\Controllers;

use App\Models\Jabatan;
use App\Models\Karyawan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function index()
    {
        return view('admin.dashboard');
    }

    public function daftarKaryawan()
    {
        $jabatan = Jabatan::all();
        // $jenis_gaji = JenisGaji::all();
        $karyawan = Karyawan::with(['jabatan', 'user'])->orderBy('tgl_masuk', 'desc')->get();
        $user = Auth::user();
        // $karyawan = Karyawan::whereHas('user', function ($query) {
        //     $query->where('role_user', '!=', 'admin');
        // })->with(['jabatan', 'jenisGaji', 'user'])->orderBy('tgl_masuk', 'desc')->get();

        return view('admin.data_karyawan.daftar_karyawan', compact('jabatan', 'karyawan', 'user'));
    }


    public function storeDaftarKaryawan(Request $request)
    {
        $validated = $request->validate([
            'jabatan_id'     => 'required|exists:jabatan,id_jabatan',
            'nama'           => 'required|string|max:100',
            'alamat'         => 'required|string',
            'no_hp'          => 'nullable|string|max:20',
            'tgl_masuk'      => 'required|date',
            'jenis_gaji_id'  => 'required|exists:jenis_gaji,id_jenis_gaji',
            'foto'           => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);


        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('foto_karyawan', 'public');
            $validated['foto'] = $fotoPath;
        }

        $karyawan = Karyawan::create($validated);

        $user = User::where('karyawan_id', $karyawan->id_karyawan)->first();
        if ($user) {
            $karyawan->update(['status' => $user->status]);
        }

        return redirect()->back()->with('success', 'Data karyawan berhasil ditambahkan.');
    }


    public function updateDaftarKaryawan(Request $request, $id)
    {
        $karyawan = Karyawan::findOrFail($id);

        $validated = $request->validate([
            'jabatan_id'     => 'required|exists:jabatan,id_jabatan',
            'nama'           => 'required|string|max:100',
            'alamat'         => 'nullable|string',
            'no_hp'          => 'nullable|string|max:20',
            'tgl_masuk'      => 'required|date',
            'jenis_gaji_id'  => 'required|exists:jenis_gaji,id_jenis_gaji',
            'foto'           => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // if ($request->hasFile('foto')) {
        //     if ($karyawan->foto && Storage::disk('public')->exists($karyawan->foto)) {
        //         Storage::disk('public')->delete($karyawan->foto);
        //     }

        //     $fotoPath = $request->file('foto')->store('foto_karyawan', 'public');
        //     $validated['foto'] = $fotoPath;
        // }

        $karyawan->update($validated);

        $user = User::where('karyawan_id', $karyawan->id_karyawan)->first();

        if ($user) {
            $karyawan->update(['status' => $user->status]);
        } else {
            $karyawan->update(['status' => 'nonaktif']);
        }

        return redirect()->back()->with('success', 'Data karyawan berhasil diperbarui');
    }
}
