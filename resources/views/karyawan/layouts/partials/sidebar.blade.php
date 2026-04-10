<div id="layoutSidenav_nav">
    <nav class="sidenav shadow-right sidenav-light">
        <div class="sidenav-menu">
            <div class="nav accordion" id="accordionSidenav">

                <div class="sidenav-menu-heading">Portal Karyawan</div>

                {{-- Dashboard --}}
                <a class="nav-link {{ request()->is('dashboard*') ? 'active' : '' }}"
                    href="{{ route('dashboard.karyawan') }}">
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

                {{-- Absensi Saya --}}
                <a class="nav-link disabled" href="javascript:void(0);">
                    <div class="nav-link-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <path opacity="0.3"
                                d="M21 22H3C2.4 22 2 21.6 2 21V5C2 4.4 2.4 4 3 4H21C21.6 4 22 4.4 22 5V21C22 21.6 21.6 22 21 22Z"
                                fill="currentColor" />
                            <path d="M8 2C7.4 2 7 2.4 7 3V5C7 5.6 7.4 6 8 6C8.6 6 9 5.6 9 5V3C9 2.4 8.6 2 8 2Z"
                                fill="currentColor" />
                            <path d="M16 2C15.4 2 15 2.4 15 3V5C15 5.6 15.4 6 16 6C16.6 6 17 5.6 17 5V3C17 2.4 16.6 2 16 2Z"
                                fill="currentColor" />
                            <path d="M7 10H17V12H7V10ZM7 14H13V16H7V14Z" fill="currentColor" />
                        </svg>
                    </div>
                    Absensi Saya
                </a>

                {{-- Pengajuan Izin --}}
                <a class="nav-link disabled" href="javascript:void(0);">
                    <div class="nav-link-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <path opacity="0.3"
                                d="M19 22H5C4.4 22 4 21.6 4 21V3C4 2.4 4.4 2 5 2H14L20 8V21C20 21.6 19.6 22 19 22Z"
                                fill="currentColor" />
                            <path d="M15 8H20L14 2V7C14 7.6 14.4 8 15 8Z" fill="currentColor" />
                            <path d="M8 12H16V14H8V12ZM8 16H13V18H8V16Z" fill="currentColor" />
                        </svg>
                    </div>
                    Pengajuan Izin
                </a>

                {{-- Pengajuan Lembur --}}
                <a class="nav-link disabled" href="javascript:void(0);">
                    <div class="nav-link-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <path opacity="0.3"
                                d="M12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2C6.47715 2 2 6.47715 2 12C2 17.5228 6.47715 22 12 22Z"
                                fill="currentColor" />
                            <path d="M12 7V12L15 15" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </div>
                    Pengajuan Lembur
                </a>

                {{-- Slip Gaji --}}
                <a class="nav-link disabled" href="javascript:void(0);">
                    <div class="nav-link-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <path opacity="0.3"
                                d="M18 21H6C4.9 21 4 20.1 4 19V5C4 3.9 4.9 3 6 3H18C19.1 3 20 3.9 20 5V19C20 20.1 19.1 21 18 21Z"
                                fill="currentColor" />
                            <path d="M10 17H14V15H10V17ZM10 13H14V11H10V13ZM10 9H14V7H10V9Z"
                                fill="currentColor" />
                        </svg>
                    </div>
                    Slip Gaji
                </a>

            </div>
        </div>

        <div class="sidenav-footer">
            <div class="sidenav-footer-content">
                <div class="sidenav-footer-subtitle">Logged in as:</div>
                <div class="sidenav-footer-title">{{ auth()->user()->karyawan->nama ?? auth()->user()->nik }}</div>
            </div>
        </div>
    </nav>
</div>
