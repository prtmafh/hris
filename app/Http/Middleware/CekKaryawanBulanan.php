<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CekKaryawanBulanan
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            abort(403, 'Silakan login terlebih dahulu.');
        }

        $user = Auth::user();

        if (
            $user->role &&
            $user->role->nama_role === 'karyawan' &&
            $user->status_gaji === 'harian'
        ) {
            abort(403, 'Fitur ini hanya tersedia untuk karyawan bulanan.');
        }

        return $next($request);
    }
}
