@extends('pimpinan.layouts.app')

@section('title', 'Dashboard Pimpinan')

@section('content')
<main>
    <header class="page-header page-header-dark bg-gradient-primary-to-secondary pb-10">
        <div class="container-xl px-4">
            <div class="page-header-content pt-4">
                <div class="row align-items-center justify-content-between">
                    <div class="col-auto mt-4">
                        <h1 class="page-header-title">
                            <div class="page-header-icon"><i data-feather="activity"></i></div>
                            Dashboard Pimpinan
                        </h1>
                        <div class="page-header-subtitle">Ringkasan kinerja dan kehadiran karyawan</div>
                    </div>
                    <div class="col-12 col-xl-auto mt-4">
                        <div class="input-group input-group-joined border-0" style="width: 16.5rem">
                            <span class="input-group-text"><i class="text-primary" data-feather="calendar"></i></span>
                            <input class="form-control ps-0" value="{{ $now->locale('id')->isoFormat('D MMMM YYYY') }}" readonly />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div class="container-xl px-4 mt-n10">

        <!-- Statistik Cards -->
        <div class="row">
            <div class="col-lg-6 col-xl-3 mb-4">
                <div class="card bg-primary text-white h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="me-3">
                                <div class="text-white-75 small">Total Karyawan Aktif</div>
                                <div class="text-lg fw-bold">{{ $totalKaryawan }}</div>
                            </div>
                            <i class="feather-xl text-white-50" data-feather="users"></i>
                        </div>
                    </div>
                    <div class="card-footer d-flex align-items-center justify-content-between small">
                        <span class="text-white">Karyawan Aktif</span>
                        <div class="text-white"><i class="fas fa-angle-right"></i></div>
                    </div>
                </div>
            </div>

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
                        <a class="text-white stretched-link" href="{{ route('pimpinan.laporan.absensi') }}">Lihat Laporan</a>
                        <div class="text-white"><i class="fas fa-angle-right"></i></div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 col-xl-3 mb-4">
                <div class="card bg-warning text-white h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="me-3">
                                <div class="text-white-75 small">Terlambat Hari Ini</div>
                                <div class="text-lg fw-bold">{{ $terlambat }}</div>
                            </div>
                            <i class="feather-xl text-white-50" data-feather="clock"></i>
                        </div>
                    </div>
                    <div class="card-footer d-flex align-items-center justify-content-between small">
                        <a class="text-white stretched-link" href="{{ route('pimpinan.laporan.absensi') }}">Lihat Laporan</a>
                        <div class="text-white"><i class="fas fa-angle-right"></i></div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 col-xl-3 mb-4">
                <div class="card bg-danger text-white h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="me-3">
                                <div class="text-white-75 small">Tidak Hadir</div>
                                <div class="text-lg fw-bold">{{ $tidakHadir < 0 ? 0 : $tidakHadir }}</div>
                            </div>
                            <i class="feather-xl text-white-50" data-feather="x-circle"></i>
                        </div>
                    </div>
                    <div class="card-footer d-flex align-items-center justify-content-between small">
                        <a class="text-white stretched-link" href="{{ route('pimpinan.laporan.absensi') }}">Lihat Laporan</a>
                        <div class="text-white"><i class="fas fa-angle-right"></i></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Ringkasan Absensi Bulan Ini -->
            <div class="col-xl-4 mb-4">
                <div class="card mb-4">
                    <div class="card-header">
                        <i data-feather="bar-chart-2" class="me-2 text-primary"></i>
                        Absensi Bulan {{ $now->locale('id')->isoFormat('MMMM YYYY') }}
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="text-muted small">Hadir</span>
                            <span class="badge bg-success-soft text-success fw-bold">{{ $ringkasanAbsensi['hadir'] }}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="text-muted small">Terlambat</span>
                            <span class="badge bg-warning-soft text-warning fw-bold">{{ $ringkasanAbsensi['terlambat'] }}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="text-muted small">Izin</span>
                            <span class="badge bg-info-soft text-info fw-bold">{{ $ringkasanAbsensi['izin'] }}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="text-muted small">Alpha</span>
                            <span class="badge bg-danger-soft text-danger fw-bold">{{ $ringkasanAbsensi['alpha'] }}</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="text-muted small">Izin Pending</span>
                            <span class="badge bg-warning-soft text-warning fw-bold">{{ $izinPending }}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-muted small">Lembur Pending</span>
                            <span class="badge bg-warning-soft text-warning fw-bold">{{ $lemburPending }}</span>
                        </div>
                    </div>
                    <div class="card-footer text-end">
                        <a href="{{ route('pimpinan.laporan.absensi') }}" class="btn btn-sm btn-primary">Laporan Lengkap</a>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <i data-feather="dollar-sign" class="me-2 text-success"></i>
                        Info Penggajian Bulan Ini
                    </div>
                    <div class="card-body">
                        <div class="text-muted small mb-1">Total Gaji Dikeluarkan</div>
                        <div class="h4 text-success mb-0">Rp {{ number_format($totalGajiBulanIni, 0, ',', '.') }}</div>
                    </div>
                    <div class="card-footer text-end">
                        <a href="{{ route('pimpinan.laporan.penggajian') }}" class="btn btn-sm btn-primary">Laporan Gaji</a>
                    </div>
                </div>
            </div>

            <!-- Top Penilaian Karyawan -->
            <div class="col-xl-8 mb-4">
                <div class="card h-100">
                    <div class="card-header card-header-actions">
                        <div>
                            <i data-feather="star" class="me-2 text-warning"></i>
                            Top Penilaian Karyawan — {{ $now->locale('id')->isoFormat('MMMM YYYY') }}
                        </div>
                        <div>
                            <span class="badge bg-primary me-2">{{ $penilaianBulanIni }} karyawan dinilai</span>
                            <a href="{{ route('pimpinan.penilaian.create') }}" class="btn btn-sm btn-primary">
                                <i class="fas fa-plus me-1"></i> Beri Penilaian
                            </a>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        @if($penilaianTerakhir->isEmpty())
                        <div class="text-center text-muted py-5">
                            <i data-feather="inbox" style="width:40px;height:40px;" class="mb-2"></i>
                            <p>Belum ada penilaian bulan ini</p>
                            <a href="{{ route('pimpinan.penilaian.create') }}" class="btn btn-primary btn-sm">Mulai Menilai</a>
                        </div>
                        @else
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-4">#</th>
                                        <th>Nama Karyawan</th>
                                        <th>Jabatan</th>
                                        <th>Nilai Total</th>
                                        <th>Grade</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($penilaianTerakhir as $i => $p)
                                    <tr>
                                        <td class="ps-4 fw-bold">{{ $i + 1 }}</td>
                                        <td class="text-capitalize">{{ $p->karyawan->nama ?? '-' }}</td>
                                        <td class="text-muted small">{{ $p->karyawan->jabatan->nama_jabatan ?? '-' }}</td>
                                        <td>
                                            <div class="progress" style="height:6px;width:100px;">
                                                <div class="progress-bar
                                                    {{ $p->nilai_total >= 90 ? 'bg-success' : ($p->nilai_total >= 75 ? 'bg-primary' : ($p->nilai_total >= 60 ? 'bg-warning' : 'bg-danger')) }}"
                                                    style="width:{{ $p->nilai_total }}%"></div>
                                            </div>
                                            <small class="text-muted">{{ number_format($p->nilai_total, 1) }}</small>
                                        </td>
                                        <td>
                                            @php $gradeColor = ['A'=>'success','B'=>'primary','C'=>'warning','D'=>'danger'][$p->grade] ?? 'secondary'; @endphp
                                            <span class="badge bg-{{ $gradeColor }}-soft text-{{ $gradeColor }} fw-bold fs-6">{{ $p->grade }}</span>
                                        </td>
                                        <td>
                                            <a href="{{ route('pimpinan.penilaian.show', $p->id) }}" class="btn btn-sm btn-outline-primary">Detail</a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @endif
                    </div>
                    @if(!$penilaianTerakhir->isEmpty())
                    <div class="card-footer text-end">
                        <a href="{{ route('pimpinan.penilaian.index') }}" class="btn btn-sm btn-primary">Semua Penilaian</a>
                    </div>
                    @endif
                </div>
            </div>
        </div>

    </div>
</main>
@endsection
