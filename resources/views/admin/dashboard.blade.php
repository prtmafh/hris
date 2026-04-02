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
                                placeholder="{{ now()->locale('id')->isoFormat('D MMMM YYYY') }}" readonly />
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
                                <div class="text-lg fw-bold">0</div>
                            </div>
                            <i class="feather-xl text-white-50" data-feather="users"></i>
                        </div>
                    </div>
                    <div class="card-footer d-flex align-items-center justify-content-between small">
                        <a class="text-white stretched-link" href="#">Lihat
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
                                <div class="text-lg fw-bold">0</div>
                            </div>
                            <i class="feather-xl text-white-50" data-feather="check-circle"></i>
                        </div>
                    </div>
                    <div class="card-footer d-flex align-items-center justify-content-between small">
                        <a class="text-white stretched-link" href="#">Lihat Absensi</a>
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
                                <div class="text-lg fw-bold">0</div>
                            </div>
                            <i class="feather-xl text-white-50" data-feather="clock"></i>
                        </div>
                    </div>
                    <div class="card-footer d-flex align-items-center justify-content-between small">
                        <a class="text-white stretched-link" href="#">Lihat Detail</a>
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
                                <div class="text-lg fw-bold">0</div>
                            </div>
                            <i class="feather-xl text-white-50" data-feather="x-circle"></i>
                        </div>
                    </div>
                    <div class="card-footer d-flex align-items-center justify-content-between small">
                        <a class="text-white stretched-link" href="#">Lihat Detail</a>
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
                        <a href="#" class="btn btn-sm btn-primary">
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
                                    {{-- @forelse($absensiHariIni as $a)
                                    <tr>
                                        <td class="ps-4 text-capitalize">{{ $a->karyawan->nama ?? '-' }}</td>
                                        <td>
                                            <span class="badge bg-light text-dark border">
                                                {{ $a->jam_masuk ?? 'Belum Absen' }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-dark border">
                                                {{ $a->jam_pulang ?? 'Belum Absen' }}
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
                                            @case('tidak hadir')
                                            <span class="badge bg-danger-soft text-danger">Tidak Hadir</span>
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
                                    @endforelse --}}
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Informasi Terbaru -->
            <div class="col-xl-4 mb-4">
                <!-- Slip Gaji -->
                <div class="card mb-4">
                    <div class="card-header">
                        <i data-feather="file-text" class="me-2 text-success"></i>
                        Slip Gaji Bulan Ini
                    </div>
                    <div class="card-body">
                        <p class="text-muted small mb-1">Periode:</p>
                        <p class="fw-bold mb-3">{{ now()->locale('id')->monthName }} {{ now()->year }}</p>
                        <a href="#" class="btn btn-success btn-sm w-100">
                            <i data-feather="eye" class="me-1" style="width:14px;height:14px"></i>
                            Lihat Slip Gaji
                        </a>
                    </div>
                </div>

                <!-- Pengumuman HRD -->
                <div class="card">
                    <div class="card-header">
                        <i data-feather="bell" class="me-2 text-warning"></i>
                        Pengumuman HRD
                    </div>
                    <div class="card-body">
                        <div class="d-flex align-items-start mb-3">
                            <div class="bg-warning-soft text-warning rounded p-2 me-3 flex-shrink-0">
                                <i data-feather="alert-circle" style="width:16px;height:16px"></i>
                            </div>
                            <p class="text-muted small mb-0">
                                Karyawan lembur wajib konfirmasi ke mandor sebelum pukul 10.00 setiap Sabtu.
                            </p>
                        </div>
                        <hr class="my-2">
                        <p class="text-muted small mb-0">
                            <i data-feather="info" class="me-1 text-primary" style="width:14px;height:14px"></i>
                            Pastikan absensi dilakukan sebelum jam kerja dimulai.
                        </p>
                    </div>
                </div>
            </div>
        </div>

    </div>
</main>


@endsection