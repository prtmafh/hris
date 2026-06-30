<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Jabatan;
use App\Models\Karyawan;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;

class DaftarAdminController extends Controller
{
    public function index()
    {
        $jabatan = Jabatan::all();
        $user = Auth::user();

        $karyawan = Karyawan::with(['jabatan', 'role'])
            ->whereHas('role', function ($query) {
                $query->where('nama_role', 'admin');
            })
            ->latest('tgl_masuk')
            ->get();

        return view(
            'admin.data_karyawan.daftar_admin.index',
            compact('jabatan', 'karyawan', 'user')
        );
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|max:100',
            'nik' => 'required|unique:karyawan,nik',
            'tgl_lahir' => 'required|date',
            'tgl_masuk' => 'required|date',
            // 'role_id' => 'required|exists:role,id',
            'jabatan_id' => 'required|exists:jabatan,id',
            'status_gaji' => 'required|in:harian,bulanan',
            'gaji_pokok' => 'nullable|integer',
            'gaji_per_hari' => 'nullable|integer',
            'status' => 'required|in:aktif,nonaktif',
        ], [
            'nik.required' => 'NIK wajib diisi',
            'nik.unique' => 'NIK sudah terpakai.',
        ]);

        $foto = null;

        if ($request->hasFile('foto')) {
            $foto = $request->file('foto')->store('karyawan', 'public');
        }
        $roleKaryawan = Role::where('nama_role', 'admin')->first();
        Karyawan::create([
            'role_id'        => $roleKaryawan->id,
            'jabatan_id'     => $request->jabatan_id,
            'nama'           => $request->nama,
            'nik'            => $request->nik,
            'password'       => Hash::make($request->tgl_lahir),
            'tgl_lahir'      => $request->tgl_lahir,
            'alamat'         => $request->alamat,
            'no_hp'          => $request->no_hp,
            'tgl_masuk'      => $request->tgl_masuk,
            'status_gaji'    => $request->status_gaji,
            'gaji_pokok'     => $request->gaji_pokok,
            'gaji_per_hari'  => $request->gaji_per_hari,
            'status'         => $request->status,
            'foto'           => $foto,
        ]);

        return redirect()
            ->route('admin.daftar_admin')
            ->with('success', 'Karyawan berhasil ditambahkan.');
    }
    public function storeView()
    {
        $jabatan = Jabatan::all();
        // $jenis_gaji = JenisGaji::all();
        return view('admin.data_karyawan.daftar_admin.tambah', compact('jabatan'));
    }

    public function detail($id)
    {
        $karyawan = Karyawan::with(['jabatan'])->findOrFail($id);
        $jabatan  = Jabatan::all();
        return view('admin.data_karyawan.daftar_admin.detail', compact('karyawan', 'jabatan'));
    }

    public function updateView($id)
    {
        $karyawan = Karyawan::with(['jabatan'])->findOrFail($id);
        $jabatan  = Jabatan::all();
        return view('admin.data_karyawan.daftar_admin.edit', compact('karyawan', 'jabatan'));
    }

    public function update(Request $request, $id)
    {
        $karyawan = Karyawan::findOrFail($id);

        $request->validate([
            'nama'           => 'required|string|max:255',
            'nik'            => 'required|string|max:20|unique:karyawan,nik,' . $id,
            'tgl_lahir'      => 'required|date',
            'tgl_masuk'      => 'required|date',
            'alamat'         => 'nullable|string',
            'no_hp'          => 'nullable|string|max:20',
            'jabatan_id'     => 'required|exists:jabatan,id',
            'status_gaji'    => 'required|in:bulanan,harian',
            'gaji_pokok'     => 'nullable|numeric',
            'gaji_per_hari'  => 'nullable|numeric',
            // 'status'         => 'required|in:aktif,nonaktif',
            'foto'           => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
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
            'status',
        ]);

        if ($request->hasFile('foto')) {
            if ($karyawan->foto) {
                Storage::disk('public')->delete($karyawan->foto);
            }

            $data['foto'] = $request->file('foto')->store('foto_karyawan', 'public');
        }

        if ($request->status_gaji === 'bulanan') {
            $data['gaji_per_hari'] = null;
        } else {
            $data['gaji_pokok'] = null;
        }

        $karyawan->update($data);

        return redirect()
            ->route('admin.daftar_admin.show', $karyawan->id)
            ->with('success', 'Data Admin berhasil diperbarui.');
    }
    public function toggleKaryawanStatus($id)
    {
        $karyawan = Karyawan::findOrFail($id);
        $karyawan->status = $karyawan->status === 'aktif' ? 'nonaktif' : 'aktif';
        $karyawan->save();

        return redirect()->back()->with('success', "Status karyawan {$karyawan->nama} berhasil diperbarui.");
    }
    public function resetPassword($id)
    {
        $karyawan = Karyawan::findOrFail($id);

        if (!$karyawan->tgl_lahir) {
            return redirect()->back()->with(
                'error',
                'Tanggal lahir karyawan belum tersedia, password tidak dapat direset.'
            );
        }

        $passwordDefault = $karyawan->tgl_lahir;

        $karyawan->update([
            'password' => Hash::make($passwordDefault),
        ]);

        return redirect()->back()->with(
            'success',
            "Password {$karyawan->nama} berhasil direset ke tanggal lahir ({$passwordDefault})."
        );
    }

    public function destroy($id)
    {
        $karyawan = Karyawan::findOrFail($id);

        if ($karyawan->foto && Storage::disk('public')->exists($karyawan->foto)) {
            Storage::disk('public')->delete($karyawan->foto);
        }

        $user = $karyawan->user;
        $karyawan->delete();
        $user?->delete();

        return redirect()->back()->with('success', 'Data karyawan berhasil dihapus.');
    }
}
