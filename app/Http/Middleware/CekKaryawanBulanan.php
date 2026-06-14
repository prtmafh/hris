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
        $user = Auth::user();

        if (
            $user &&
            $user->role === 'karyawan' &&
            $user->karyawan &&
            $user->karyawan->status_gaji === 'harian'
        ) {
            abort(403, 'Fitur ini hanya tersedia untuk karyawan bulanan.');
        }

        return $next($request);
    }
}
