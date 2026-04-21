<?php

namespace App\Http\Controllers\Karyawan;

use App\Http\Controllers\Controller;
use App\Models\Lembur;
use App\Models\Pengaturan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LemburController extends Controller
{
    public function index(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();
        $karyawan = $user->karyawan()->firstOrFail();

        $lembur = Lembur::where('karyawan_id', $karyawan->id)
            ->orderByDesc('tanggal')
            ->get();

        return view('karyawan.lembur-saya', compact('lembur'));
    }


    public function store(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();
        $karyawan = $user->karyawan()->firstOrFail();

        $request->validate([
            'tanggal'     => 'required|date',
            'jam_mulai'   => 'required',
            'jam_selesai' => 'required|after:jam_mulai',
            'keterangan'  => 'required|string|max:500',
        ]);

        $jamMulai = Carbon::parse($request->jam_mulai);
        $jamSelesai = Carbon::parse($request->jam_selesai);

        if ($jamSelesai->lessThan($jamMulai)) {
            $jamSelesai->addDay();
        }

        $totalJam = $jamMulai->floatDiffInHours($jamSelesai);

        $upahPerJam = Pengaturan::getValue('tarif_lembur_per_jam') ?? 15000;

        $totalUpah = $totalJam * $upahPerJam;

        Lembur::create([
            'karyawan_id' => $karyawan->id,
            'tanggal' => $request->tanggal,
            'jam_mulai' => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,
            'total_jam' => $totalJam,
            'total_upah' => $totalUpah,
            'keterangan'  => $request->keterangan,
            'status' => 'pending',
        ]);

        return redirect()->back()->with('success', 'Pengajuan lembur berhasil dikirim dan menunggu persetujuan.');
    }
}
