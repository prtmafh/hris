<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Lowongan;
use Carbon\Carbon;

class LowonganController extends Controller
{
    public function index()
    {
        $today = Carbon::today();

        $lowongan = Lowongan::with('jabatan')
            ->where('status', 'aktif')
            ->whereDate('tanggal_buka', '<=', $today)
            ->whereDate('tanggal_tutup', '>=', $today)
            ->orderBy('tanggal_tutup')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($lowongan);
    }

    public function show($id)
    {
        $today = Carbon::today();

        $lowongan = Lowongan::with('jabatan')
            ->where('status', 'aktif')
            ->whereDate('tanggal_buka', '<=', $today)
            ->whereDate('tanggal_tutup', '>=', $today)
            ->findOrFail($id);

        return response()->json($lowongan);
    }
}
