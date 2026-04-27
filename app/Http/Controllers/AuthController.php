<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function loginForm()
    {
        return view('auth.login');
    }
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'nik' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        // Login
        if (!Auth::attempt($credentials)) {
            return back()->withErrors([
                'error' => 'nik atau password salah!',
            ])->withInput();
        }

        $request->session()->regenerate();

        $user = Auth::user();

        if ($user->status !== 'aktif') {
            Auth::logout();
            return back()->withErrors([
                'error' => 'Akun Anda tidak aktif!',
            ])->withInput();
        }

        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        if ($user->role === 'karyawan') {
            return redirect()->route('dashboard.karyawan');
        }

        if ($user->role === 'pimpinan') {
            return redirect()->route('pimpinan.dashboard');
        }

        Auth::logout();
        return redirect()->route('login')->withErrors([
            'error' => 'Role tidak dikenali!',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Anda berhasil keluar.');
    }
}
