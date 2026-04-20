<div id="layoutSidenav_nav">
    <nav class="sidenav shadow-right sidenav-light">
        <div class="sidenav-menu">
            <div class="nav accordion" id="accordionSidenav">

                <div class="sidenav-menu-heading">Portal Karyawan</div>

                <a class="nav-link {{ request()->routeIs('dashboard.karyawan') ? 'active' : '' }}"
                    href="{{ route('dashboard.karyawan') }}">
                    <div class="nav-link-icon">
                        <i data-feather="home"></i>
                    </div>
                    Dashboard
                </a>

                <div class="sidenav-menu-heading">Menu</div>

                <a class="nav-link {{ request()->routeIs('karyawan.absensi') ? 'active' : '' }}"
                    href="{{ route('karyawan.absensi') }}">
                    <div class="nav-link-icon">
                        <i data-feather="calendar"></i>
                    </div>
                    Absensi Saya
                </a>

                <a class="nav-link {{ request()->routeIs('karyawan.izin*') ? 'active' : '' }}"
                    href="{{ route('karyawan.izin') }}">
                    <div class="nav-link-icon">
                        <i data-feather="file-text"></i>
                    </div>
                    Pengajuan Izin
                </a>

                <a class="nav-link {{ request()->routeIs('karyawan.lembur*') ? 'active' : '' }}"
                    href="{{ route('karyawan.lembur') }}">
                    <div class="nav-link-icon">
                        <i data-feather="clock"></i>
                    </div>
                    Pengajuan Lembur
                </a>

                <a class="nav-link {{ request()->routeIs('karyawan.slip_gaji*') ? 'active' : '' }}"
                    href="{{ route('karyawan.slip_gaji') }}">
                    <div class="nav-link-icon">
                        <i data-feather="dollar-sign"></i>
                    </div>
                    Slip Gaji
                </a>

            </div>
        </div>

        <div class="sidenav-footer">
            <div class="sidenav-footer-content">
                <div class="sidenav-footer-subtitle">Logged in as:</div>
                <div class="sidenav-footer-title text-capitalize">
                    {{ auth()->user()->karyawan->nama ?? auth()->user()->nik }}
                </div>
            </div>
        </div>

    </nav>
</div>