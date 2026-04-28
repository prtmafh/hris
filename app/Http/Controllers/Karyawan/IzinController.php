<?php

namespace App\Http\Controllers\Karyawan;

use App\Http\Controllers\Controller;
use App\Models\Izin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IzinController extends Controller
{
    public function index(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();
        $karyawan = $user->karyawan()->firstOrFail();

        $izin = Izin::where('karyawan_id', $karyawan->id)
            ->orderByDesc('tanggal')
            ->get();

        $izinTerpakai = Izin::where('karyawan_id', $karyawan->id)
            ->whereIn('status_approval', ['pending', 'disetujui'])
            ->count();

        $kuotaIzin = $karyawan->kuota_izin ?? 12;
        $sisaIzin = max($kuotaIzin - $izinTerpakai, 0);

        return view('karyawan.izin-saya', compact(
            'izin',
            'kuotaIzin',
            'izinTerpakai',
            'sisaIzin'
        ));
    }

    public function store(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();
        $karyawan = $user->karyawan()->firstOrFail();

        $request->validate([
            'tanggal'    => 'required|date',
            'jenis_izin' => 'required|in:sakit,izin,cuti',
            'keterangan' => 'required|string|max:500',
        ]);

        $izinTerpakai = Izin::where('karyawan_id', $karyawan->id)
            ->whereIn('status_approval', ['pending', 'disetujui'])
            ->count();

        if ($izinTerpakai >= $karyawan->kuota_izin) {
            return back()
                ->with('error', 'Kuota izin tahunan Anda sudah habis (12 kali).');
        }
        $sudahAda = Izin::where('karyawan_id', $karyawan->id)
            ->whereDate('tanggal', $request->tanggal)
            ->exists();

        if ($sudahAda) {
            return back()->with(
                'error',
                'Pengajuan izin untuk tanggal tersebut sudah ada.'
            );
        }

        Izin::create([
            'karyawan_id'     => $karyawan->id,
            'tanggal'         => $request->tanggal,
            'jenis_izin'      => $request->jenis_izin,
            'keterangan'      => $request->keterangan,
            'status_approval' => 'pending',
        ]);

        return back()->with(
            'success',
            'Pengajuan izin berhasil dikirim.'
        );
    }
}
