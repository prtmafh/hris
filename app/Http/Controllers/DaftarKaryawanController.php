<?php

namespace App\Http\Controllers;

use App\Models\Jabatan;
use App\Models\Karyawan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class DaftarKaryawanController extends Controller
{
    public function index()
    {
        $jabatan = Jabatan::all();
        // $jenis_gaji = JenisGaji::all();
        // $karyawan = Karyawan::with(['jabatan', 'user'])->orderBy('tgl_masuk', 'desc')->get();
        $user = Auth::user();
        $karyawan = Karyawan::whereHas('user', function ($query) {
            $query->where('role', '!=', 'admin');
        })->with(['jabatan', 'user'])->orderBy('tgl_masuk', 'desc')->get();

        return view('admin.data_karyawan.daftar_karyawan.index', compact('jabatan', 'karyawan', 'user'));
    }


    public function store(Request $request)
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
            'role' => 'karyawan',
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

    public function tambah()
    {
        $jabatan = Jabatan::all();
        // $jenis_gaji = JenisGaji::all();
        return view('admin.data_karyawan.daftar_karyawan.tambah', compact('jabatan'));
    }

    public function detail($id)
    {
        $karyawan = Karyawan::with(['jabatan', 'user'])->findOrFail($id);
        $jabatan  = Jabatan::all();
        return view('admin.data_karyawan.daftar_karyawan.detail', compact('karyawan', 'jabatan'));
    }
    public function edit($id)
    {
        $karyawan = Karyawan::with(['jabatan', 'user'])->findOrFail($id);
        $jabatan  = Jabatan::all();
        return view('admin.data_karyawan.daftar_karyawan.edit', compact('karyawan', 'jabatan'));
    }
    public function update(Request $request, $id)
    {
        $karyawan = Karyawan::findOrFail($id);

        $request->validate([
            'nama'        => 'required|string|max:255',
            'nik'         => 'nullable|string|max:20|unique:karyawan,nik,' . $id,
            'tgl_lahir'   => 'nullable|date',
            'tgl_masuk'   => 'nullable|date',
            'alamat'      => 'nullable|string',
            'no_hp'       => 'nullable|string|max:20',
            'jabatan_id'  => 'required|exists:jabatan,id',
            'status_gaji' => 'required|in:bulanan,harian',
            'gaji_pokok'  => 'nullable|numeric',
            'gaji_per_hari' => 'nullable|numeric',
            'foto'        => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = $request->only([
            'nama',
            'nik',
            'tgl_lahir',
            'tgl_masuk',
            'alamat',
            'no_hp',
            'jabatan_id',
            'status_gaji',
            'gaji_pokok',
            'gaji_per_hari',
        ]);

        // Handle upload foto
        if ($request->hasFile('foto')) {
            // Hapus foto lama jika ada
            if ($karyawan->foto) {
                Storage::disk('public')->delete($karyawan->foto);
            }
            $data['foto'] = $request->file('foto')->store('foto_karyawan', 'public');
        }

        // Reset nominal gaji yang tidak dipakai
        if ($request->status_gaji === 'bulanan') {
            $data['gaji_per_hari'] = null;
        } else {
            $data['gaji_pokok'] = null;
        }

        $karyawan->update($data);

        return redirect()->route('admin.karyawan.show', $karyawan->id)
            ->with('success', 'Data karyawan berhasil diperbarui.');
    }
    public function toggleKaryawanStatus($id)
    {
        $karyawan = Karyawan::findOrFail($id);
        $karyawan->status = $karyawan->status === 'aktif' ? 'nonaktif' : 'aktif';
        $karyawan->save();

        return redirect()->back()->with('success', "Status karyawan {$karyawan->nama} berhasil diperbarui.");
    }

    public function toggleStatus($id)
    {
        $karyawan = Karyawan::with('user')->findOrFail($id);
        $user = $karyawan->user;

        $user->status = $user->status === 'aktif' ? 'nonaktif' : 'aktif';
        $user->save();

        $label = $user->status === 'aktif' ? 'diaktifkan' : 'dinonaktifkan';

        return redirect()->back()->with('success', "Akun {$karyawan->nama} berhasil {$label}.");
    }

    public function destroy($id)
    {
        $karyawan = Karyawan::with('user')->findOrFail($id);

        if ($karyawan->foto && Storage::disk('public')->exists($karyawan->foto)) {
            Storage::disk('public')->delete($karyawan->foto);
        }

        $user = $karyawan->user;
        $karyawan->delete();
        $user?->delete();

        return redirect()->back()->with('success', 'Data karyawan berhasil dihapus.');
    }
}
