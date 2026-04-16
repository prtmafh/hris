<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\Izin;
use App\Models\Jabatan;
use App\Models\Karyawan;
use App\Models\Lembur;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    public function index()
    {
        $today = Carbon::today();

        $totalKaryawan = Karyawan::where('status', 'aktif')->count();

        $absensiHariIni = Absensi::with(['karyawan.jabatan'])
            ->whereDate('tanggal', $today)
            ->orderByRaw("CASE
                WHEN status = 'terlambat' THEN 1
                WHEN status = 'hadir' THEN 2
                WHEN status = 'izin' THEN 3
                WHEN status = 'alpha' THEN 4
                ELSE 5
            END")
            ->orderBy('jam_masuk')
            ->get();

        $hadirHariIni = $absensiHariIni->where('status', 'hadir')->count();
        $terlambatHariIni = $absensiHariIni->where('status', 'terlambat')->count();
        $izinHariIni = $absensiHariIni->where('status', 'izin')->count();
        $tidakHadirHariIni = max($totalKaryawan - ($hadirHariIni + $terlambatHariIni + $izinHariIni), 0);

        $izinPending = Izin::where('status_approval', 'pending')->count();
        $lemburPending = Lembur::where('status', 'pending')->count();

        $absensiBulanIni = Absensi::whereMonth('tanggal', $today->month)
            ->whereYear('tanggal', $today->year);

        $ringkasanBulanIni = [
            'hadir' => (clone $absensiBulanIni)->where('status', 'hadir')->count(),
            'terlambat' => (clone $absensiBulanIni)->where('status', 'terlambat')->count(),
            'izin' => (clone $absensiBulanIni)->where('status', 'izin')->count(),
            'alpha' => (clone $absensiBulanIni)->where('status', 'alpha')->count(),
        ];

        return view('admin.dashboard', compact(
            'today',
            'totalKaryawan',
            'absensiHariIni',
            'hadirHariIni',
            'terlambatHariIni',
            'izinHariIni',
            'tidakHadirHariIni',
            'izinPending',
            'lemburPending',
            'ringkasanBulanIni'
        ));
    }

    public function daftarAdmin()
    {
        $admins = User::where('role', 'admin')->orderBy('created_at', 'desc')->get();
        return view('admin.data_karyawan.daftar_admin.index', compact('admins'));
    }

    public function storeAdmin(Request $request)
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

    public function updateAdmin(Request $request, $id)
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

    public function destroyAdmin($id)
    {
        $admin = User::where('role', 'admin')->findOrFail($id);

        if ($admin->id === Auth::id()) {
            return redirect()->back()->with('error', 'Tidak dapat menghapus akun Anda sendiri.');
        }

        $admin->delete();
        return redirect()->back()->with('success', 'Akun admin berhasil dihapus.');
    }
}
