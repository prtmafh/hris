@extends('karyawan.layouts.app')

@section('title', 'Slip Gaji')

@push('styles')
<style>
    .main-card {
        background: white;
        border-radius: 20px;
        box-shadow: 0 10px 15px -3px rgba(0,0,0,.1), 0 4px 6px -2px rgba(0,0,0,.05);
        border: none;
    }
    .table-history th { font-size: .8rem; text-transform: uppercase; color: #6b7280; font-weight: 600; }
    .table-history td { font-size: .9rem; vertical-align: middle; }
    .filter-bar { background: #f9fafb; border-radius: 12px; padding: 1rem 1.25rem; }
    .slip-card {
        border-radius: 14px;
        border: 1px solid #e5e7eb;
        padding: 1.25rem 1.5rem;
        transition: box-shadow .2s;
    }
    .slip-card:hover { box-shadow: 0 4px 12px rgba(0,0,0,.08); }
    .slip-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: .4rem 0;
        border-bottom: 1px dashed #f3f4f6;
        font-size: .9rem;
    }
    .slip-row:last-child { border-bottom: none; }
    .slip-total {
        background: linear-gradient(135deg, #3b82f6, #2563eb);
        color: white;
        border-radius: 10px;
        padding: .75rem 1rem;
        margin-top: .75rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-weight: 700;
    }

    @media print {
        .no-print { display: none !important; }
        .main-card { box-shadow: none !important; }
    }
</style>
@endpush

@section('content')
<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <div class="post d-flex flex-column-fluid" id="kt_post">
        <div id="kt_content_container" class="container-xxl px-3 px-md-4">

            {{-- PAGE HEADER --}}
            <div class="d-flex align-items-center justify-content-between mt-4 mb-4">
                <div>
                    <h2 class="fw-bolder text-dark mb-1" style="font-size: clamp(1.1rem,4vw,1.4rem);">Slip Gaji</h2>
                    <span class="text-muted fw-semibold" style="font-size:.9rem;">Riwayat penggajian Anda</span>
                </div>
            </div>

            {{-- FILTER --}}
            <div class="card main-card mb-4 no-print">
                <div class="card-body">
                    <form method="GET" action="{{ route('karyawan.slip_gaji') }}" class="filter-bar">
                        <div class="row g-3 align-items-end">
                            <div class="col-8 col-md-5">
                                <label class="form-label fw-semibold text-muted" style="font-size:.8rem;">Tahun</label>
                                <select name="tahun" class="form-select">
                                    @foreach($daftarTahun as $t)
                                        <option value="{{ $t }}" {{ $tahun == $t ? 'selected' : '' }}>{{ $t }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-4 col-md-3">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fas fa-search me-1"></i> Tampilkan
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            @if($penggajian->isEmpty())
            {{-- EMPTY STATE --}}
            <div class="card main-card mb-5">
                <div class="card-body text-center py-5">
                    <i class="fas fa-file-invoice-dollar fa-3x mb-3 text-gray-400"></i>
                    <p class="text-muted mb-0">Belum ada data slip gaji untuk tahun {{ $tahun }}.</p>
                </div>
            </div>

            @else
            {{-- SLIP CARDS --}}
            <div class="row g-4 mb-5">
                @foreach($penggajian as $gaji)
                @php
                    $namaBulan = \Carbon\Carbon::create()->month($gaji->periode_bulan)->translatedFormat('F');
                @endphp
                <div class="col-12 col-md-6 col-xl-4">
                    <div class="card main-card h-100">
                        {{-- HEADER SLIP --}}
                        <div class="card-header border-0 pt-4 px-4 pb-2 d-flex align-items-center justify-content-between">
                            <div>
                                <div class="fw-bolder text-dark" style="font-size:1rem;">{{ $namaBulan }} {{ $gaji->periode_tahun }}</div>
                                <div class="text-muted fw-semibold" style="font-size:.8rem;">{{ $karyawan->nama }}</div>
                            </div>
                            @if($gaji->status === 'dibayar')
                                <span class="badge badge-light-success">Dibayar</span>
                            @else
                                <span class="badge badge-light-warning">Proses</span>
                            @endif
                        </div>

                        <div class="card-body px-4 pt-2 pb-4">
                            <div class="slip-card">
                                <div class="slip-row">
                                    <span class="text-muted">Total Hadir</span>
                                    <span class="fw-semibold">{{ $gaji->total_hadir ?? 0 }} hari</span>
                                </div>
                                <div class="slip-row">
                                    <span class="text-muted">Total Lembur</span>
                                    <span class="fw-semibold">{{ $gaji->total_lembur ?? 0 }} jam</span>
                                </div>
                                <div class="slip-row">
                                    <span class="text-muted">Potongan</span>
                                    <span class="fw-semibold text-danger">
                                        - Rp {{ number_format($gaji->potongan ?? 0, 0, ',', '.') }}
                                    </span>
                                </div>
                                @if($gaji->tgl_dibayar)
                                <div class="slip-row">
                                    <span class="text-muted">Tgl Dibayar</span>
                                    <span class="fw-semibold">{{ \Carbon\Carbon::parse($gaji->tgl_dibayar)->translatedFormat('d M Y') }}</span>
                                </div>
                                @endif
                                <div class="slip-total">
                                    <span style="font-size:.9rem;">Total Gaji</span>
                                    <span style="font-size:1.05rem;">Rp {{ number_format($gaji->total_gaji ?? 0, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @endif

        </div>
    </div>
</div>
@endsection
