@php
$dataKaryawan = request()->is(['admin/daftar_karyawan*','admin/daftar_admin*','admin/jabatan*']);
$absensi = request()->is(['admin/absensi*','admin/rekap-tahunan*']);
$pengajuan = request()->is(['admin/lembur*','admin/izin*']);
$gaji = request()->is(['admin/penggajian*']);

$reimbursement = request()->is(['admin/kategori-reimbursement*','admin/reimbursement*']);
$rekrutmen = request()->is(['admin/lowongan*','admin/pelamar*']);

$pengaturan = request()->is(['admin/pengaturan*','admin/jadwal-kerja*','admin/hari-libur*']);
@endphp

<div id="layoutSidenav_nav">
    <nav class="sidenav shadow-right sidenav-light">
        <div class="sidenav-menu">
            <div class="nav accordion" id="accordionSidenav">

                <div class="sidenav-menu-heading">Core</div>

                <a class="nav-link {{ request()->is('admin/dashboard*') ? 'active' : '' }}"
                    href="{{ route('admin.dashboard') }}">
                    <div class="nav-link-icon"><i data-feather="home"></i></div>
                    Dashboard
                </a>

                <div class="sidenav-menu-heading">Menu</div>

                <a class="nav-link {{ $dataKaryawan ? '' : 'collapsed' }}" href="#" data-bs-toggle="collapse"
                    data-bs-target="#collapseKaryawan">
                    <div class="nav-link-icon"><i data-feather="users"></i></div>
                    Data Karyawan
                    <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>

                <div class="collapse {{ $dataKaryawan ? 'show' : '' }}" id="collapseKaryawan">
                    <nav class="sidenav-menu-nested nav">
                        <a class="nav-link {{ request()->is('admin/daftar_admin*') ? 'active' : '' }}"
                            href="{{ route('admin.daftar_admin') }}">Admin</a>

                        <a class="nav-link {{ request()->is('admin/daftar_karyawan*') ? 'active' : '' }}"
                            href="{{ route('admin.daftar_karyawan') }}">Karyawan</a>

                        <a class="nav-link {{ request()->is('admin/jabatan*') ? 'active' : '' }}"
                            href="{{ route('admin.jabatan') }}">Jabatan</a>
                    </nav>
                </div>

                <a class="nav-link {{ $absensi ? '' : 'collapsed' }}" href="#" data-bs-toggle="collapse"
                    data-bs-target="#collapseAbsensi">
                    <div class="nav-link-icon"><i data-feather="calendar"></i></div>
                    Absensi
                    <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>

                <div class="collapse {{ $absensi ? 'show' : '' }}" id="collapseAbsensi">
                    <nav class="sidenav-menu-nested nav">
                        <a class="nav-link {{ request()->is('admin/absensi*') ? 'active' : '' }}"
                            href="{{ route('data_absen') }}">Data Absen</a>

                        <a class="nav-link {{ request()->is('admin/rekap-tahunan*') ? 'active' : '' }}"
                            href="{{ route('rekap.tahunan') }}">Rekap</a>
                    </nav>
                </div>

                <a class="nav-link {{ $pengajuan ? '' : 'collapsed' }}" href="#" data-bs-toggle="collapse"
                    data-bs-target="#collapsePengajuan">
                    <div class="nav-link-icon"><i data-feather="file-text"></i></div>
                    Pengajuan
                    <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>

                <div class="collapse {{ $pengajuan ? 'show' : '' }}" id="collapsePengajuan">
                    <nav class="sidenav-menu-nested nav">
                        <a class="nav-link {{ request()->is('admin/izin*') ? 'active' : '' }}"
                            href="{{ route('admin.izin') }}">Izin</a>

                        <a class="nav-link {{ request()->is('admin/lembur*') ? 'active' : '' }}"
                            href="{{ route('admin.lembur') }}">Lembur</a>
                    </nav>
                </div>

                <a class="nav-link {{ $gaji ? '' : 'collapsed' }}" href="#" data-bs-toggle="collapse"
                    data-bs-target="#collapseGaji">
                    <div class="nav-link-icon"><i data-feather="dollar-sign"></i></div>
                    Penggajian
                    <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>

                <div class="collapse {{ $gaji ? 'show' : '' }}" id="collapseGaji">
                    <nav class="sidenav-menu-nested nav">
                        <a class="nav-link {{ request()->is('admin/penggajian*') ? 'active' : '' }}"
                            href="{{ route('admin.penggajian') }}">Data Gaji</a>
                    </nav>
                </div>

                <a class="nav-link {{ $reimbursement ? '' : 'collapsed' }}" href="#" data-bs-toggle="collapse"
                    data-bs-target="#collapseReimbursement">
                    <div class="nav-link-icon"><i data-feather="credit-card"></i></div>
                    Reimbursement
                    <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>

                <div class="collapse {{ $reimbursement ? 'show' : '' }}" id="collapseReimbursement">
                    <nav class="sidenav-menu-nested nav">
                        <a class="nav-link {{ request()->is('admin/kategori-reimbursement*') ? 'active' : '' }}"
                            href="{{ route('admin.kategori_reimbursement') }}">Kategori</a>

                        <a class="nav-link {{ request()->is('admin/reimbursement*') ? 'active' : '' }}"
                            href="{{ route('admin.reimbursement') }}">Data Reimbursement</a>
                    </nav>
                </div>

                <a class="nav-link {{ $rekrutmen ? '' : 'collapsed' }}" href="#" data-bs-toggle="collapse"
                    data-bs-target="#collapseRekrutmen">
                    <div class="nav-link-icon"><i data-feather="user-plus"></i></div>
                    Rekrutmen
                    <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>

                <div class="collapse {{ $rekrutmen ? 'show' : '' }}" id="collapseRekrutmen">
                    <nav class="sidenav-menu-nested nav">
                        <a class="nav-link {{ request()->is('admin/lowongan*') ? 'active' : '' }}"
                            href="{{ route('admin.lowongan') }}">Lowongan</a>
                        <a class="nav-link {{ request()->is('admin/pelamar*') ? 'active' : '' }}"
                            href="{{ route('admin.pelamar') }}">Pelamar</a>
                    </nav>
                </div>

                <div class="sidenav-menu-heading mt-4">Pengaturan</div>

                <a class="nav-link {{ $pengaturan ? '' : 'collapsed' }}" href="#" data-bs-toggle="collapse"
                    data-bs-target="#collapsePengaturan">
                    <div class="nav-link-icon"><i data-feather="settings"></i></div>
                    Pengaturan HR
                    <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>

                <div class="collapse {{ $pengaturan ? 'show' : '' }}" id="collapsePengaturan">
                    <nav class="sidenav-menu-nested nav">
                        <a class="nav-link {{ request()->is('admin/pengaturan*') ? 'active' : '' }}"
                            href="{{ route('admin.pengaturan') }}">Pengaturan</a>

                        <a class="nav-link {{ request()->is('admin/jadwal-kerja*') ? 'active' : '' }}"
                            href="{{ route('admin.jadwal_kerja') }}">Jadwal Kerja</a>

                        <a class="nav-link {{ request()->is('admin/hari-libur*') ? 'active' : '' }}"
                            href="{{ route('admin.hari_libur') }}">Hari Libur</a>
                    </nav>
                </div>

            </div>
        </div>
    </nav>
</div>
