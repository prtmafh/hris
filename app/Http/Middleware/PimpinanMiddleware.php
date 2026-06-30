<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class PimpinanMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            abort(403, 'Akses ditolak.');
        }

        $user = Auth::user();

        if (!$user || $user->role_id !== 2) {
            abort(403, 'Akses ditolak.');
        }

        return $next($request);
    }
}
