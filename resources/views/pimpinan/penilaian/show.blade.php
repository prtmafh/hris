@extends('pimpinan.layouts.app')

@section('title', 'Detail Penilaian')

@section('content')
<main>
    <header class="page-header page-header-dark bg-gradient-primary-to-secondary pb-10">
        <div class="container-xl px-4">
            <div class="page-header-content pt-4">
                <div class="row align-items-center justify-content-between">
                    <div class="col-auto mt-4">
                        <h1 class="page-header-title">
                            <div class="page-header-icon"><i data-feather="star"></i></div>
                            Detail Penilaian
                        </h1>
                        <div class="page-header-subtitle">{{ $penilaian->karyawan->nama ?? '-' }} —
                            {{ $penilaian->nama_bulan }} {{ $penilaian->periode_tahun }}</div>
                    </div>
                    <div class="col-auto mt-4 d-flex gap-2">
                        <a href="{{ route('pimpinan.penilaian.edit', $penilaian->id) }}" class="btn btn-warning btn-sm">
                            <i data-feather="edit-2" style="width:16px;height:16px;"></i> Edit
                        </a>
                        <a href="{{ route('pimpinan.penilaian.index') }}" class="btn btn-light btn-sm">
                            <i data-feather="arrow-left" style="width:16px;height:16px;"></i> Kembali
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div class="container-xl px-4 mt-n10">
        <div class="row">
            <!-- Info Karyawan & Nilai -->
            <div class="col-xl-5 mb-4">
                <div class="card mb-4">
                    <div class="card-header"><i data-feather="user" class="me-2"></i> Informasi Karyawan</div>
                    <div class="card-body">
                        <table class="table table-borderless mb-0">
                            <tr>
                                <td class="text-muted small fw-semibold" style="width:40%">Nama</td>
                                <td class="text-capitalize fw-bold">{{ $penilaian->karyawan->nama ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted small fw-semibold">NIK</td>
                                <td>{{ $penilaian->karyawan->nik ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted small fw-semibold">Jabatan</td>
                                <td>{{ $penilaian->karyawan->jabatan->nama_jabatan ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted small fw-semibold">Periode</td>
                                <td>{{ $penilaian->nama_bulan }} {{ $penilaian->periode_tahun }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted small fw-semibold">Dinilai Oleh</td>
                                <td>{{ $penilaian->penilai->karyawan->nama ?? $penilaian->penilai->nik ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted small fw-semibold">Tanggal Penilaian</td>
                                <td>{{ $penilaian->created_at->locale('id')->isoFormat('D MMMM YYYY') }}</td>
                            </tr>
                        </table>
                    </div>
                </div>

                <!-- Rekap Absensi Periode -->
                <div class="card">
                    <div class="card-header"><i data-feather="calendar" class="me-2"></i> Rekap Absensi Periode</div>
                    <div class="card-body">
                        <div class="row text-center g-2">
                            <div class="col-6">
                                <div class="card bg-success-soft border-0 py-2">
                                    <div class="small text-muted">Hadir</div>
                                    <div class="h4 fw-bold text-success mb-0">{{ $rekapAbsensi['hadir'] }}</div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="card bg-warning-soft border-0 py-2">
                                    <div class="small text-muted">Terlambat</div>
                                    <div class="h4 fw-bold text-warning mb-0">{{ $rekapAbsensi['terlambat'] }}</div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="card bg-info-soft border-0 py-2">
                                    <div class="small text-muted">Izin</div>
                                    <div class="h4 fw-bold text-info mb-0">{{ $rekapAbsensi['izin'] }}</div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="card bg-danger-soft border-0 py-2">
                                    <div class="small text-muted">Alpha</div>
                                    <div class="h4 fw-bold text-danger mb-0">{{ $rekapAbsensi['alpha'] }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Nilai & Grade -->
            <div class="col-xl-7 mb-4">
                <!-- Grade Card -->
                @php
                    $gradeColor = ['A'=>'success','B'=>'primary','C'=>'warning','D'=>'danger'][$penilaian->grade] ?? 'secondary';
                    $gradeLabel = ['A'=>'Sangat Baik','B'=>'Baik','C'=>'Cukup','D'=>'Kurang'][$penilaian->grade] ?? '-';
                @endphp
                <div class="card mb-4 border-{{ $gradeColor }} border-2">
                    <div class="card-body text-center py-4">
                        <div class="text-muted small mb-1">Nilai Total</div>
                        <div class="display-4 fw-bold text-{{ $gradeColor }}">
                            {{ number_format($penilaian->nilai_total, 2) }}
                        </div>
                        <div class="progress mx-auto mt-3 mb-3" style="height:12px;max-width:300px;">
                            <div class="progress-bar bg-{{ $gradeColor }}"
                                style="width:{{ $penilaian->nilai_total }}%"></div>
                        </div>
                        <span class="badge bg-{{ $gradeColor }} fs-3 px-4 py-2">{{ $penilaian->grade }}</span>
                        <div class="mt-2 text-{{ $gradeColor }} fw-semibold">{{ $gradeLabel }}</div>
                    </div>
                </div>

                <!-- Rincian Nilai -->
                <div class="card mb-4">
                    <div class="card-header"><i data-feather="bar-chart" class="me-2"></i> Rincian Nilai</div>
                    <div class="card-body">
                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-1">
                                <span class="fw-semibold">Kehadiran</span>
                                <span class="fw-bold">{{ number_format($penilaian->nilai_kehadiran, 1) }} / 100
                                    <span class="text-muted small">(bobot 40%)</span>
                                </span>
                            </div>
                            <div class="progress" style="height:10px;">
                                <div class="progress-bar bg-success" style="width:{{ $penilaian->nilai_kehadiran }}%"></div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-1">
                                <span class="fw-semibold">Kedisiplinan</span>
                                <span class="fw-bold">{{ number_format($penilaian->nilai_kedisiplinan, 1) }} / 100
                                    <span class="text-muted small">(bobot 30%)</span>
                                </span>
                            </div>
                            <div class="progress" style="height:10px;">
                                <div class="progress-bar bg-primary" style="width:{{ $penilaian->nilai_kedisiplinan }}%"></div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-1">
                                <span class="fw-semibold">Kinerja</span>
                                <span class="fw-bold">{{ number_format($penilaian->nilai_kinerja, 1) }} / 100
                                    <span class="text-muted small">(bobot 30%)</span>
                                </span>
                            </div>
                            <div class="progress" style="height:10px;">
                                <div class="progress-bar bg-warning" style="width:{{ $penilaian->nilai_kinerja }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Catatan -->
                @if($penilaian->catatan)
                <div class="card">
                    <div class="card-header"><i data-feather="message-square" class="me-2"></i> Catatan</div>
                    <div class="card-body">
                        <p class="mb-0">{{ $penilaian->catatan }}</p>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</main>
@endsection
