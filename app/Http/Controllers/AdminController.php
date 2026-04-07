<?php

namespace App\Http\Controllers;

use App\Models\Jabatan;
use App\Models\Karyawan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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
        $request->validate([
            'nama' => 'required|max:100',
            'nik' => 'required|unique:karyawan,nik',
            'tgl_lahir' => 'required|date',
            'tgl_masuk' => 'required|date',
            'jabatan_id' => 'required|exists:jabatan,id',
            'status_gaji' => 'required|in:harian,bulanan',
            'gaji_pokok' => 'nullable|integer',
            'gaji_per_hari' => 'nullable|integer',
            'status' => 'required|in:aktif,nonaktif',
        ]);

        // 🔥 1. BUAT USER (LOGIN NIK)
        $user = User::create([
            'nik' => $request->nik,
            // 'email' => $request->nik . '@company.com',
            'password' => Hash::make($request->tgl_lahir),
            'status' => 'aktif'
        ]);

        // 🔥 2. HANDLE FOTO
        $foto = null;
        if ($request->hasFile('foto')) {
            $foto = $request->file('foto')->store('karyawan', 'public');
        }

        // 🔥 3. SIMPAN KARYAWAN
        Karyawan::create([
            'user_id' => $user->id,
            'jabatan_id' => $request->jabatan_id,
            'nama' => $request->nama,
            'nik' => $request->nik,
            'tgl_lahir' => $request->tgl_lahir,
            'alamat' => $request->alamat,
            'no_hp' => $request->no_hp,
            'tgl_masuk' => $request->tgl_masuk,
            'status_gaji' => $request->status_gaji,
            'gaji_pokok' => $request->gaji_pokok,
            'gaji_per_hari' => $request->gaji_per_hari,
            'status' => $request->status,
            'foto' => $foto,
        ]);

        return redirect()->back()->with('success', 'Karyawan berhasil ditambahkan');
    }
    public function tambahKaryawan()
    {
        $jabatan = Jabatan::all();
        // $jenis_gaji = JenisGaji::all();
        return view('admin.data_karyawan.tambah_karyawan', compact('jabatan'));
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
