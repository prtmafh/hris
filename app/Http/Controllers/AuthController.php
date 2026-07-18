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

        if (!Auth::attempt($credentials)) {
            return back()->withErrors([
                'error' => 'NIK atau password salah!',
            ])->withInput();
        }

        $request->session()->regenerate();

        /** @var \App\Models\Karyawan $karyawan */
        $karyawan = Auth::user();

        $karyawan->load('role');

        if ($karyawan->status !== 'aktif') {
            Auth::logout();

            return back()->withErrors([
                'error' => 'Akun Anda tidak aktif!',
            ])->withInput();
        }

        switch ($karyawan->role->nama_role) {
            case 'admin':
                return redirect()->route('admin.dashboard');

            case 'karyawan':
                return redirect()->route('dashboard.karyawan');

            case 'pimpinan':
                return redirect()->route('pimpinan.dashboard');

            case 'admin_kecil':
                return redirect()->route('dashboard.karyawan');

            default:
                Auth::logout();

                return redirect()->route('login')->withErrors([
                    'error' => 'Role tidak dikenali!',
                ]);
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')
            ->with('success', 'Anda berhasil keluar.');
    }
}
