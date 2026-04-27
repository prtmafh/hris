@php
$laporan  = request()->is('pimpinan/laporan*');
$penilaian = request()->is('pimpinan/penilaian*');
@endphp

<div id="layoutSidenav_nav">
    <nav class="sidenav shadow-right sidenav-light">
        <div class="sidenav-menu">
            <div class="nav accordion" id="accordionSidenav">

                <div class="sidenav-menu-heading">Core</div>

                <a class="nav-link {{ request()->is('pimpinan/dashboard*') ? 'active' : '' }}"
                    href="{{ route('pimpinan.dashboard') }}">
                    <div class="nav-link-icon"><i data-feather="home"></i></div>
                    Dashboard
                </a>

                <div class="sidenav-menu-heading">Menu</div>

                <a class="nav-link {{ $laporan ? '' : 'collapsed' }}" href="#" data-bs-toggle="collapse"
                    data-bs-target="#collapseLaporan">
                    <div class="nav-link-icon"><i data-feather="bar-chart-2"></i></div>
                    Laporan
                    <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>

                <div class="collapse {{ $laporan ? 'show' : '' }}" id="collapseLaporan">
                    <nav class="sidenav-menu-nested nav">
                        <a class="nav-link {{ request()->is('pimpinan/laporan/absensi*') ? 'active' : '' }}"
                            href="{{ route('pimpinan.laporan.absensi') }}">Absensi</a>
                        <a class="nav-link {{ request()->is('pimpinan/laporan/penggajian*') ? 'active' : '' }}"
                            href="{{ route('pimpinan.laporan.penggajian') }}">Penggajian</a>
                        <a class="nav-link {{ request()->is('pimpinan/laporan/izin*') ? 'active' : '' }}"
                            href="{{ route('pimpinan.laporan.izin') }}">Izin</a>
                        <a class="nav-link {{ request()->is('pimpinan/laporan/lembur*') ? 'active' : '' }}"
                            href="{{ route('pimpinan.laporan.lembur') }}">Lembur</a>
                        <a class="nav-link {{ request()->is('pimpinan/laporan/reimbursement*') ? 'active' : '' }}"
                            href="{{ route('pimpinan.laporan.reimbursement') }}">Reimbursement</a>
                    </nav>
                </div>

                <a class="nav-link {{ $penilaian ? '' : 'collapsed' }}" href="#" data-bs-toggle="collapse"
                    data-bs-target="#collapsePenilaian">
                    <div class="nav-link-icon"><i data-feather="star"></i></div>
                    Penilaian Karyawan
                    <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>

                <div class="collapse {{ $penilaian ? 'show' : '' }}" id="collapsePenilaian">
                    <nav class="sidenav-menu-nested nav">
                        <a class="nav-link {{ request()->is('pimpinan/penilaian') ? 'active' : '' }}"
                            href="{{ route('pimpinan.penilaian.index') }}">Data Penilaian</a>
                        <a class="nav-link {{ request()->is('pimpinan/penilaian/tambah*') ? 'active' : '' }}"
                            href="{{ route('pimpinan.penilaian.create') }}">Beri Penilaian</a>
                    </nav>
                </div>

            </div>
        </div>
        <div class="sidenav-footer">
            <div class="sidenav-footer-content">
                <div class="sidenav-footer-subtitle">Login sebagai:</div>
                <div class="sidenav-footer-title">{{ Auth::user()->karyawan->nama ?? Auth::user()->nik }}</div>
            </div>
        </div>
    </nav>
</div>
