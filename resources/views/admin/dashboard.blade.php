@extends('admin.layouts.app')

@section('title', 'Dashboard Absensi')

@section('content')

<main>
    <!-- Page Header -->
    <header class="page-header page-header-dark bg-gradient-primary-to-secondary pb-10">
        <div class="container-xl px-4">
            <div class="page-header-content pt-4">
                <div class="row align-items-center justify-content-between">
                    <div class="col-auto mt-4">
                        <h1 class="page-header-title">
                            <div class="page-header-icon"><i data-feather="activity"></i></div>
                            Dashboard
                        </h1>
                        <div class="page-header-subtitle">Statistik kehadiran karyawan hari ini</div>
                    </div>
                    <div class="col-12 col-xl-auto mt-4">
                        <div class="input-group input-group-joined border-0" style="width: 16.5rem">
                            <span class="input-group-text"><i class="text-primary" data-feather="calendar"></i></span>
                            <input class="form-control ps-0 pointer"
                                placeholder="{{ $today->locale('id')->isoFormat('D MMMM YYYY') }}" readonly />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Main page content -->
    <div class="container-xl px-4 mt-n10">

        <!-- Statistik Cards -->
        <div class="row">
            <!-- Total Karyawan -->
            <div class="col-lg-6 col-xl-3 mb-4">
                <div class="card bg-primary text-white h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="me-3">
                                <div class="text-white-75 small">Total Karyawan</div>
                                <div class="text-lg fw-bold">{{ $totalKaryawan }}</div>
                            </div>
                            <i class="feather-xl text-white-50" data-feather="users"></i>
                        </div>
                    </div>
                    <div class="card-footer d-flex align-items-center justify-content-between small">
                        <a class="text-white stretched-link" href="{{ route('admin.daftar_karyawan') }}">Lihat
                            Karyawan</a>
                        <div class="text-white"><i class="fas fa-angle-right"></i></div>
                    </div>
                </div>
            </div>

            <!-- Hadir Hari Ini -->
            <div class="col-lg-6 col-xl-3 mb-4">
                <div class="card bg-success text-white h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="me-3">
                                <div class="text-white-75 small">Hadir Hari Ini</div>
                                <div class="text-lg fw-bold">{{ $hadirHariIni }}</div>
                            </div>
                            <i class="feather-xl text-white-50" data-feather="check-circle"></i>
                        </div>
                    </div>
                    <div class="card-footer d-flex align-items-center justify-content-between small">
                        <a class="text-white stretched-link" href="{{ route('data_absen') }}">Lihat Absensi</a>
                        <div class="text-white"><i class="fas fa-angle-right"></i></div>
                    </div>
                </div>
            </div>

            <!-- Terlambat -->
            <div class="col-lg-6 col-xl-3 mb-4">
                <div class="card bg-warning text-white h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="me-3">
                                <div class="text-white-75 small">Terlambat</div>
                                <div class="text-lg fw-bold">{{ $terlambatHariIni }}</div>
                            </div>
                            <i class="feather-xl text-white-50" data-feather="clock"></i>
                        </div>
                    </div>
                    <div class="card-footer d-flex align-items-center justify-content-between small">
                        <a class="text-white stretched-link"
                            href="{{ route('data_absen', ['status' => 'terlambat']) }}">Lihat Detail</a>
                        <div class="text-white"><i class="fas fa-angle-right"></i></div>
                    </div>
                </div>
            </div>

            <!-- Tidak Hadir -->
            <div class="col-lg-6 col-xl-3 mb-4">
                <div class="card bg-danger text-white h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="me-3">
                                <div class="text-white-75 small">Tidak Hadir</div>
                                <div class="text-lg fw-bold">{{ $tidakHadirHariIni }}</div>
                            </div>
                            <i class="feather-xl text-white-50" data-feather="x-circle"></i>
                        </div>
                    </div>
                    <div class="card-footer d-flex align-items-center justify-content-between small">
                        <a class="text-white stretched-link"
                            href="{{ route('data_absen', ['status' => 'alpha']) }}">Lihat Detail</a>
                        <div class="text-white"><i class="fas fa-angle-right"></i></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabel Absensi + Informasi -->
        <div class="row">
            <!-- Tabel Absensi Hari Ini -->
            <div class="col-xl-8 mb-4">
                <div class="card card-header-actions h-100">
                    <div class="card-header">
                        Absensi Hari Ini
                        <a href="{{ route('data_absen') }}" class="btn btn-sm btn-primary">
                            Lihat Semua
                        </a>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-4">Nama</th>
                                        <th>Jam Masuk</th>
                                        <th>Jam Pulang</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($absensiHariIni as $a)
                                    <tr>
                                        <td class="ps-4 text-capitalize">{{ $a->karyawan->nama ?? '-' }}</td>
                                        <td>
                                            <span class="badge bg-light text-dark border">
                                                {{ $a->jam_masuk ? \Illuminate\Support\Str::of($a->jam_masuk)->limit(5,
                                                '') : 'Belum Absen' }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-dark border">
                                                {{ $a->jam_keluar ?
                                                \Illuminate\Support\Str::of($a->jam_keluar)->limit(5, '') : 'Belum
                                                Absen' }}
                                            </span>
                                        </td>
                                        <td>
                                            @switch($a->status)
                                            @case('hadir')
                                            <span class="badge bg-success-soft text-success">Hadir</span>
                                            @break
                                            @case('izin')
                                            <span class="badge bg-warning-soft text-warning">Izin</span>
                                            @break
                                            @case('alpha')
                                            <span class="badge bg-danger-soft text-danger">Alpha</span>
                                            @break
                                            @case('terlambat')
                                            <span class="badge bg-warning-soft text-warning">Terlambat</span>
                                            @break
                                            @default
                                            <span class="badge bg-secondary-soft text-secondary">Belum Absen</span>
                                            @endswitch
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-4">
                                            <i data-feather="inbox" class="mb-2"></i><br>
                                            Belum ada data absensi hari ini
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Ringkasan Admin -->
            <div class="col-xl-4 mb-4">
                <div class="card mb-4">
                    <div class="card-header">
                        <i data-feather="clipboard" class="me-2 text-primary"></i>
                        Persetujuan Pending
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <div class="small text-muted">Pengajuan Izin</div>
                                <div class="h4 mb-0">{{ $izinPending }}</div>
                            </div>
                            <a href="{{ route('admin.izin') }}" class="btn btn-sm btn-outline-primary">Lihat</a>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <div class="small text-muted">Pengajuan Lembur</div>
                                <div class="h4 mb-0">{{ $lemburPending }}</div>
                            </div>
                            <a href="{{ route('admin.lembur') }}" class="btn btn-sm btn-outline-primary">Lihat</a>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <i data-feather="bar-chart-2" class="me-2 text-success"></i>
                        Ringkasan Bulan Ini
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="text-muted small">Hadir</span>
                            <span class="badge bg-success-soft text-success">{{ $ringkasanBulanIni['hadir'] }}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="text-muted small">Terlambat</span>
                            <span class="badge bg-warning-soft text-warning">{{ $ringkasanBulanIni['terlambat']
                                }}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="text-muted small">Izin</span>
                            <span class="badge bg-info-soft text-info">{{ $ringkasanBulanIni['izin'] }}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-muted small">Alpha</span>
                            <span class="badge bg-danger-soft text-danger">{{ $ringkasanBulanIni['alpha'] }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</main>


@endsection