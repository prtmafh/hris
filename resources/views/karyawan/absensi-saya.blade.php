@extends('karyawan.layouts.app')

@section('title', 'Absensi Saya')

@push('styles')
<style>
    .main-card {
        background: white;
        border-radius: 20px;
        box-shadow: 0 10px 15px -3px rgba(0,0,0,.1), 0 4px 6px -2px rgba(0,0,0,.05);
        border: none;
    }
    .stat-card {
        border-radius: 14px;
        padding: 1.25rem 1.5rem;
        border: none;
    }
    .table-history th { font-size: .8rem; text-transform: uppercase; color: #6b7280; font-weight: 600; }
    .table-history td { font-size: .9rem; vertical-align: middle; }
    .filter-bar { background: #f9fafb; border-radius: 12px; padding: 1rem 1.25rem; }
</style>
@endpush

@section('content')
<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <div class="post d-flex flex-column-fluid" id="kt_post">
        <div id="kt_content_container" class="container-xxl px-3 px-md-4">

            {{-- PAGE HEADER --}}
            <div class="d-flex align-items-center justify-content-between mt-4 mb-4">
                <div>
                    <h2 class="fw-bolder text-dark mb-1" style="font-size: clamp(1.1rem,4vw,1.4rem);">Absensi Saya</h2>
                    <span class="text-muted fw-semibold" style="font-size:.9rem;">Riwayat kehadiran Anda per bulan</span>
                </div>
            </div>

            {{-- FILTER --}}
            <div class="card main-card mb-4">
                <div class="card-body">
                    <form method="GET" action="{{ route('karyawan.absensi') }}" class="filter-bar">
                        <div class="row g-3 align-items-end">
                            <div class="col-6 col-md-4">
                                <label class="form-label fw-semibold text-muted" style="font-size:.8rem;">Bulan</label>
                                <select name="bulan" class="form-select">
                                    @foreach(range(1,12) as $b)
                                        <option value="{{ $b }}" {{ $bulan == $b ? 'selected' : '' }}>
                                            {{ \Carbon\Carbon::create()->month($b)->translatedFormat('F') }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-6 col-md-4">
                                <label class="form-label fw-semibold text-muted" style="font-size:.8rem;">Tahun</label>
                                <select name="tahun" class="form-select">
                                    @foreach($daftarTahun as $t)
                                        <option value="{{ $t }}" {{ $tahun == $t ? 'selected' : '' }}>{{ $t }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12 col-md-4">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fas fa-search me-1"></i> Tampilkan
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            {{-- STATISTIK --}}
            <div class="row g-3 mb-4">
                <div class="col-6 col-md-3">
                    <div class="card stat-card" style="background: linear-gradient(135deg, #d1fae5, #a7f3d0);">
                        <div class="fw-bold text-success" style="font-size:1.8rem;">{{ $totalHadir }}</div>
                        <div class="text-success fw-semibold" style="font-size:.85rem;">Hadir</div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="card stat-card" style="background: linear-gradient(135deg, #fef3c7, #fde68a);">
                        <div class="fw-bold text-warning" style="font-size:1.8rem;">{{ $totalTerlambat }}</div>
                        <div class="text-warning fw-semibold" style="font-size:.85rem;">Terlambat</div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="card stat-card" style="background: linear-gradient(135deg, #dbeafe, #bfdbfe);">
                        <div class="fw-bold text-primary" style="font-size:1.8rem;">{{ $totalIzin }}</div>
                        <div class="text-primary fw-semibold" style="font-size:.85rem;">Izin</div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="card stat-card" style="background: linear-gradient(135deg, #fee2e2, #fecaca);">
                        <div class="fw-bold text-danger" style="font-size:1.8rem;">{{ $totalAlpha }}</div>
                        <div class="text-danger fw-semibold" style="font-size:.85rem;">Alpha</div>
                    </div>
                </div>
            </div>

            {{-- TABEL --}}
            <div class="card main-card mb-5">
                <div class="card-header border-0 pt-4 px-4 d-flex align-items-center justify-content-between">
                    <h3 class="card-title fw-bolder text-dark mb-0" style="font-size:1.1rem;">
                        Rekap &mdash;
                        {{ \Carbon\Carbon::create()->month($bulan)->translatedFormat('F') }} {{ $tahun }}
                    </h3>
                    <span class="badge badge-light-primary">{{ $absensi->count() }} hari</span>
                </div>
                <div class="card-body px-4 pt-2">
                    <div class="table-responsive">
                        <table class="table table-hover table-history">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Tanggal</th>
                                    <th>Jam Masuk</th>
                                    <th>Jam Pulang</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($absensi as $i => $a)
                                <tr>
                                    <td>{{ $i + 1 }}</td>
                                    <td>{{ \Carbon\Carbon::parse($a->tanggal)->translatedFormat('d F Y') }}</td>
                                    <td>{{ $a->jam_masuk ? \Carbon\Carbon::parse($a->jam_masuk)->format('H:i') : '-' }}</td>
                                    <td>{{ $a->jam_keluar ? \Carbon\Carbon::parse($a->jam_keluar)->format('H:i') : '-' }}</td>
                                    <td>
                                        @switch($a->status)
                                            @case('hadir')
                                                <span class="badge badge-light-success">Hadir</span>
                                                @break
                                            @case('terlambat')
                                                <span class="badge badge-light-warning">Terlambat</span>
                                                @break
                                            @case('izin')
                                                <span class="badge badge-light-primary">Izin</span>
                                                @break
                                            @case('alpha')
                                                <span class="badge badge-light-danger">Alpha</span>
                                                @break
                                            @default
                                                <span class="badge badge-light-secondary text-capitalize">{{ $a->status }}</span>
                                        @endswitch
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-5">
                                        <i class="fas fa-calendar-times fa-2x mb-2 d-block text-gray-400"></i>
                                        Tidak ada data absensi untuk periode ini.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
