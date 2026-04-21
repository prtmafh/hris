<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class DaftarAdminController extends Controller
{
    public function index()
    {
        $admins = User::where('role', 'admin')->orderBy('created_at', 'desc')->get();
        return view('admin.data_karyawan.daftar_admin.index', compact('admins'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nik'              => 'required|string|max:20|unique:users,nik',
            'password'         => 'required|string|min:6|confirmed',
        ]);

        User::create([
            'nik'      => $request->nik,
            'password' => Hash::make($request->password),
            'role'     => 'admin',
            'status'   => 'aktif',
        ]);

        return redirect()->route('admin.daftar_admin')->with('success', 'Akun admin berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $admin = User::where('role', 'admin')->findOrFail($id);

        session()->flash('edit_id', $id);

        $request->validate([
            'nik'      => 'required|string|max:20|unique:users,nik,' . $id,
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        $admin->nik = $request->nik;

        if ($request->filled('password')) {
            $admin->password = Hash::make($request->password);
        }

        $admin->save();

        return redirect()->route('admin.daftar_admin')->with('success', 'Akun admin berhasil diperbarui.');
    }

    public function toggleAdminStatus($id)
    {
        $admin = User::where('role', 'admin')->findOrFail($id);

        if ($admin->id === Auth::id()) {
            return redirect()->back()->with('error', 'Tidak dapat mengubah status akun Anda sendiri.');
        }

        $admin->status = $admin->status === 'aktif' ? 'nonaktif' : 'aktif';
        $admin->save();

        $label = $admin->status === 'aktif' ? 'diaktifkan' : 'dinonaktifkan';
        return redirect()->back()->with('success', "Akun admin ({$admin->nik}) berhasil {$label}.");
    }

    public function destroy($id)
    {
        $admin = User::where('role', 'admin')->findOrFail($id);

        if ($admin->id === Auth::id()) {
            return redirect()->back()->with('error', 'Tidak dapat menghapus akun Anda sendiri.');
        }

        $admin->delete();
        return redirect()->back()->with('success', 'Akun admin berhasil dihapus.');
    }
}
