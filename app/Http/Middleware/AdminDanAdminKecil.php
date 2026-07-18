<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminDanAdminKecil
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            abort(403, 'Akses ditolak.');
        }

        $user = Auth::user();

        if (!$user || !in_array($user->role_id, [1, 4])) {
            abort(403, 'Akses ditolak.');
        }

        return $next($request);
    }
}
