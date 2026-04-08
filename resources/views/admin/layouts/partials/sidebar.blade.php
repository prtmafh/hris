<div id="layoutSidenav_nav">
    <nav class="sidenav shadow-right sidenav-light">
        <div class="sidenav-menu">
            <div class="nav accordion" id="accordionSidenav">

                {{-- Logo / Brand --}}
                {{-- <div class="sidenav-menu-heading d-flex align-items-center py-3 px-4">
                    <a href="{{ route('dashboard') }}">
                        <img alt="Logo" src="{{ asset('') }}assets/img/tsilogo.svg" class="h-25px logo" />
                    </a>
                </div> --}}

                {{-- ======================== --}}
                {{-- SECTION: CORE / MENU --}}
                {{-- ======================== --}}
                <div class="sidenav-menu-heading"></div>

                {{-- Dashboard --}}
                <a class="nav-link {{ request()->is('admin/dashboard*') ? 'active' : '' }}"
                    href="{{ route('admin.dashboard') }}">
                    <div class="nav-link-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <rect x="2" y="2" width="9" height="9" rx="2" fill="currentColor" />
                            <rect opacity="0.3" x="13" y="2" width="9" height="9" rx="2" fill="currentColor" />
                            <rect opacity="0.3" x="13" y="13" width="9" height="9" rx="2" fill="currentColor" />
                            <rect opacity="0.3" x="2" y="13" width="9" height="9" rx="2" fill="currentColor" />
                        </svg>
                    </div>
                    Dashboard
                </a>
                <div class="sidenav-menu-heading">Menu</div>
                {{-- Data Karyawan --}}
                <a class="nav-link collapsed {{ request()->is('admin/daftar_karyawan*') || request()->is('admin/daftar_admin*') || request()->is('admin/jabatan*') ? '' : 'collapsed' }}"
                    href="javascript:void(0);" data-bs-toggle="collapse" data-bs-target="#collapseDataKaryawan"
                    aria-expanded="{{ request()->is('admin/daftar_karyawan*') || request()->is('admin/daftar_admin*') || request()->is('admin/jabatan*') ? 'true' : 'false' }}"
                    aria-controls="collapseDataKaryawan">
                    <div class="nav-link-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <path opacity="0.3"
                                d="M22 7H2V21H22V7ZM7 18C5.34315 18 4 16.6569 4 15C4 13.3431 5.34315 12 7 12C8.65685 12 10 13.3431 10 15C10 16.6569 8.65685 18 7 18ZM20 18H13V16H20V18Z"
                                fill="currentColor" />
                            <path d="M17 3H7C5.34315 3 4 4.34315 4 6V8H20V6C20 4.34315 18.6569 3 17 3Z"
                                fill="currentColor" />
                        </svg>
                    </div>
                    Data Karyawan
                    <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse {{ request()->is('admin/daftar_karyawan*') || request()->is('admin/daftar_admin*') || request()->is('admin/jabatan*') ? 'show' : '' }}"
                    id="collapseDataKaryawan" data-bs-parent="#accordionSidenav">
                    <nav class="sidenav-menu-nested nav">
                        <a class="nav-link {{ request()->is('admin/daftar_admin*') ? 'active' : '' }}"
                            href="{{ route('admin.daftar_admin') }}">Daftar Admin</a>
                        <a class="nav-link {{ request()->is('admin/daftar_karyawan*') ? 'active' : '' }}"
                            href="{{route('admin.daftar_karyawan')}}">Daftar Karyawan</a>
                        <a class="nav-link {{ request()->is('admin/jabatan*') ? 'active' : '' }}"
                            href="{{route('admin.jabatan')}}">Jabatan</a>
                    </nav>
                </div>

                {{-- Absensi --}}
                <a class="nav-link {{ request()->is('admin/absensi*') || request()->is('admin/rekap-tahunan*') ? '' : 'collapsed' }}"
                    href="javascript:void(0);" data-bs-toggle="collapse" data-bs-target="#collapseAbsensi"
                    aria-expanded="{{ request()->is('admin/absensi*') || request()->is('admin/rekap-tahunan*') ? 'true' : 'false' }}"
                    aria-controls="collapseAbsensi">
                    <div class="nav-link-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <path opacity="0.3"
                                d="M21 22H3C2.4 22 2 21.6 2 21V5C2 4.4 2.4 4 3 4H21C21.6 4 22 4.4 22 5V21C22 21.6 21.6 22 21 22Z"
                                fill="currentColor" />
                            <path d="M8 2C7.4 2 7 2.4 7 3V5C7 5.6 7.4 6 8 6C8.6 6 9 5.6 9 5V3C9 2.4 8.6 2 8 2Z"
                                fill="currentColor" />
                        </svg>
                    </div>
                    Absensi
                    <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse {{ request()->is('admin/absensi*') || request()->is('admin/rekap-tahunan*') ? 'show' : '' }}"
                    id="collapseAbsensi" data-bs-parent="#accordionSidenav">
                    <nav class="sidenav-menu-nested nav">
                        <a class="nav-link {{ request()->is('admin/absensi*') ? 'active' : '' }}"
                            href="{{ route('data_absen') }}">Data Absen</a>
                        <a class="nav-link {{ request()->is('admin/rekap-tahunan*') ? 'active' : '' }}"
                            href="{{ route('rekap.tahunan') }}">Rekap Absen</a>
                    </nav>
                </div>

                {{-- Pengajuan --}}
                <a class="nav-link {{ request()->is('admin/lembur*') || request()->is('admin/izin*') ? '' : 'collapsed' }}"
                    href="javascript:void(0);" data-bs-toggle="collapse" data-bs-target="#collapsePengajuan"
                    aria-expanded="{{ request()->is('admin/lembur*') || request()->is('admin/izin*') ? 'true' : 'false' }}"
                    aria-controls="collapsePengajuan">
                    <div class="nav-link-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <path opacity="0.3"
                                d="M19 22H5C4.4 22 4 21.6 4 21V3C4 2.4 4.4 2 5 2H14L20 8V21C20 21.6 19.6 22 19 22Z"
                                fill="currentColor" />
                            <path d="M15 8H20L14 2V7C14 7.6 14.4 8 15 8Z" fill="currentColor" />
                        </svg>
                    </div>
                    Pengajuan
                    <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse {{ request()->is('admin/lembur*') || request()->is('admin/izin*') ? 'show' : '' }}"
                    id="collapsePengajuan" data-bs-parent="#accordionSidenav">
                    <nav class="sidenav-menu-nested nav">
                        <a class="nav-link {{ request()->is('admin/izin*') ? 'active' : '' }}"
                            href="{{ route('admin.izin') }}">Izin Karyawan</a>
                        {{-- <a class="nav-link {{ request()->is('admin/lembur*') ? 'active' : '' }}"
                            href="{{ route('data_lembur') }}">Lembur Karyawan</a> --}}
                    </nav>
                </div>

                {{-- Gaji & Periode --}}
                {{-- <a
                    class="nav-link {{ request()->is('admin/jenis_gaji*') || request()->is('admin/gaji*') ? '' : 'collapsed' }}"
                    href="javascript:void(0);" data-bs-toggle="collapse" data-bs-target="#collapseGaji"
                    aria-expanded="{{ request()->is('admin/jenis_gaji*') || request()->is('admin/gaji*') ? 'true' : 'false' }}"
                    aria-controls="collapseGaji">
                    <div class="nav-link-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <path opacity="0.3"
                                d="M18 21H6C4.9 21 4 20.1 4 19V5C4 3.9 4.9 3 6 3H18C19.1 3 20 3.9 20 5V19C20 20.1 19.1 21 18 21Z"
                                fill="currentColor" />
                            <path d="M10 17H14V15H10V17ZM10 13H14V11H10V13ZM10 9H14V7H10V9Z" fill="currentColor" />
                        </svg>
                    </div>
                    Gaji &amp; Periode
                    <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse {{ request()->is('admin/jenis_gaji*') || request()->is('admin/gaji*') ? 'show' : '' }}"
                    id="collapseGaji" data-bs-parent="#accordionSidenav">
                    <nav class="sidenav-menu-nested nav">
                        <a class="nav-link {{ request()->is('admin/jenis_gaji') ? 'active' : '' }}"
                            href="{{ route('jenis_gaji') }}">Periode Gaji</a>
                        <a class="nav-link {{ request()->is('admin/gaji') ? 'active' : '' }}"
                            href="{{ route('gaji') }}">Data Gaji</a>
                    </nav>
                </div> --}}

                {{-- ======================== --}}
                {{-- SECTION: PENGATURAN --}}
                {{-- ======================== --}}
                <div class="sidenav-menu-heading">Pengaturan</div>

                {{-- Aplikasi --}}
                {{-- <a class="nav-link {{ request()->is('pengaturan*') ? 'active' : '' }}"
                    href="{{ route('pengaturan.index') }}">
                    <div class="nav-link-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <path opacity="0.3"
                                d="M22.1 11.5V12.6C22.1 13.2 21.7 13.6 21.2 13.7L19.9 13.9C19.7 14.7 19.4 15.5 18.9 16.2L19.7 17.2999C20 17.6999 20 18.3999 19.6 18.7999L18.8 19.6C18.4 20 17.8 20 17.3 19.7L16.2 18.9C15.5 19.3 14.7 19.7 13.9 19.9L13.7 21.2C13.6 21.7 13.1 22.1 12.6 22.1H11.5C10.9 22.1 10.5 21.7 10.4 21.2L10.2 19.9C9.4 19.7 8.6 19.4 7.9 18.9L6.8 19.7C6.4 20 5.7 20 5.3 19.6L4.5 18.7999C4.1 18.3999 4.1 17.7999 4.4 17.2999L5.2 16.2C4.8 15.5 4.4 14.7 4.2 13.9L2.9 13.7C2.4 13.6 2 13.1 2 12.6V11.5C2 10.9 2.4 10.5 2.9 10.4L4.2 10.2C4.4 9.39995 4.7 8.60002 5.2 7.90002L4.4 6.79993C4.1 6.39993 4.1 5.69993 4.5 5.29993L5.3 4.5C5.7 4.1 6.3 4.10002 6.8 4.40002L7.9 5.19995C8.6 4.79995 9.4 4.39995 10.2 4.19995L10.4 2.90002C10.5 2.40002 11 2 11.5 2H12.6C13.2 2 13.6 2.40002 13.7 2.90002L13.9 4.19995C14.7 4.39995 15.5 4.69995 16.2 5.19995L17.3 4.40002C17.7 4.10002 18.4 4.1 18.8 4.5L19.6 5.29993C20 5.69993 20 6.29993 19.7 6.79993L18.9 7.90002C19.3 8.60002 19.7 9.39995 19.9 10.2L21.2 10.4C21.7 10.5 22.1 11 22.1 11.5Z"
                                fill="currentColor" />
                            <path
                                d="M17.1 12.1C17.1 14.9 14.9 17.1 12.1 17.1C9.30001 17.1 7.10001 14.9 7.10001 12.1C7.10001 9.29998 9.30001 7.09998 12.1 7.09998C14.9 7.09998 17.1 9.29998 17.1 12.1ZM12.1 10.1C11 10.1 10.1 11 10.1 12.1C10.1 13.2 11 14.1 12.1 14.1C13.2 14.1 14.1 13.2 14.1 12.1C14.1 11 13.2 10.1 12.1 10.1Z"
                                fill="currentColor" />
                        </svg>
                    </div>
                    Aplikasi
                </a> --}}

                {{-- Libur Nasional --}}
                {{-- <a class="nav-link {{ request()->is('admin/libur-nasional*') ? 'active' : '' }}"
                    href="{{ route('admin.libur-nasional.index') }}">
                    <div class="nav-link-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <path opacity="0.3"
                                d="M21 22H3C2.4 22 2 21.6 2 21V5C2 4.4 2.4 4 3 4H21C21.6 4 22 4.4 22 5V21C22 21.6 21.6 22 21 22Z"
                                fill="currentColor" />
                            <path
                                d="M6 6C5.4 6 5 5.6 5 5V3C5 2.4 5.4 2 6 2C6.6 2 7 2.4 7 3V5C7 5.6 6.6 6 6 6ZM11 5V3C11 2.4 10.6 2 10 2C9.4 2 9 2.4 9 3V5C9 5.6 9.4 6 10 6C10.6 6 11 5.6 11 5ZM15 5V3C15 2.4 14.6 2 14 2C13.4 2 13 2.4 13 3V5C13 5.6 13.4 6 14 6C14.6 6 15 5.6 15 5ZM19 5V3C19 2.4 18.6 2 18 2C17.4 2 17 2.4 17 3V5C17 5.6 17.4 6 18 6C18.6 6 19 5.6 19 5Z"
                                fill="currentColor" />
                        </svg>
                    </div>
                    Libur Nasional
                </a> --}}

            </div>
        </div>

        {{-- Sidenav Footer --}}
        <div class="sidenav-footer">
            <div class="sidenav-footer-content">
                <div class="sidenav-footer-subtitle">Logged in as:</div>
                {{-- <div class="sidenav-footer-title">{{ auth()->user()->name ?? 'Admin' }}</div> --}}
            </div>
        </div>
    </nav>
</div>