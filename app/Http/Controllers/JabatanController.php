<?php

namespace App\Http\Controllers;

use App\Models\Jabatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JabatanController extends Controller
{
    public function index()
    {
        $jabatan = Jabatan::all();
        $user = Auth::user();
        return view('admin.data_karyawan.jabatan', compact('jabatan', 'user'));
    }
    public function storeJabatan(Request $request)
    {
        $validated = $request->validate([

            'nama_jabatan' => 'required|string|max:100',

        ]);

        Jabatan::create($validated);

        return redirect()->back()->with('success', 'Data Jabatan berhasil ditambahkan.');
    }

    public function updateJabatan(Request $request, $id)
    {
        $jabatan = Jabatan::findOrFail($id);

        $validated = $request->validate([
            'nama_jabatan'   => 'required|string|max:100',
        ]);

        $jabatan->update($validated);

        return redirect()->back()->with('success', 'Data Jabatan berhasil diperbarui.');
    }

    public function destroyJabatan($id)
    {
        $jabatan = Jabatan::findOrFail($id);

        $jabatan->delete();

        return redirect()->back()->with('success', 'Data Jabatan berhasil dihapus.');
    }
}
