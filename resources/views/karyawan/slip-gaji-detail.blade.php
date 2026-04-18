@extends('karyawan.layouts.app')

@section('title', 'Slip Gaji')

@push('styles')
<style>
    @media print {
        #layoutSidenav_nav,
        .topnav,
        .footer-admin,
        .page-header,
        .btn-back,
        .btn-print {
            display: none !important;
        }

        #layoutSidenav_content {
            margin-left: 0 !important;
            padding: 0 !important;
        }

        .slip-wrapper {
            max-width: 100% !important;
            margin: 0 !important;
            padding: 0 !important;
            box-shadow: none !important;
            border: none !important;
        }

        .slip-card {
            box-shadow: none !important;
            border: 1px solid #dee2e6 !important;
        }

        body { background: white !important; }
    }
</style>
@endpush

@section('content')
@php
    $namaBulan = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                  'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
    $pemasukan = $penggajian->details->where('tipe', 'pemasukan');
    $potongan  = $penggajian->details->where('tipe', 'potongan');
    $k         = $penggajian->karyawan;
@endphp
<main>
    <header class="page-header page-header-dark bg-gradient-primary-to-secondary pb-10">
        <div class="container-xl px-4">
            <div class="page-header-content pt-4">
                <div class="row align-items-center justify-content-between">
                    <div class="col-auto mt-4">
                        <h1 class="page-header-title">
                            <div class="page-header-icon"><i data-feather="credit-card"></i></div>
                            Slip Gaji
                        </h1>
                        <div class="page-header-subtitle">
                            {{ $namaBulan[$penggajian->periode_bulan] }} {{ $penggajian->periode_tahun }}
                        </div>
                    </div>
                    <div class="col-auto mt-4 d-flex gap-2">
                        <a href="{{ route('karyawan.slip_gaji') }}" class="btn btn-white btn-sm btn-back">
                            <i class="fas fa-arrow-left me-1"></i> Kembali
                        </a>
                        <button onclick="window.print()" class="btn btn-white btn-sm btn-print">
                            <i class="fas fa-print me-1"></i> Cetak
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div class="container-xl px-4 mt-n10">
        <div class="slip-wrapper" style="max-width: 720px; margin: 0 auto;">
            <div class="card shadow slip-card">

                {{-- Header Slip --}}
                <div class="card-body border-bottom pb-4">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="fw-bold fs-5 text-primary">TSI GROUP</div>
                            <div class="small text-muted">Slip Gaji Karyawan</div>
                        </div>
                        <div class="col-auto text-end">
                            <div class="fw-semibold">
                                Periode: {{ $namaBulan[$penggajian->periode_bulan] }} {{ $penggajian->periode_tahun }}
                            </div>
                            <div>
                                <span class="badge bg-{{ $penggajian->status === 'dibayar' ? 'success' : 'warning' }} mt-1">
                                    {{ $penggajian->status === 'dibayar' ? 'Sudah Dibayar' : 'Dalam Proses' }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Info Karyawan --}}
                <div class="card-body border-bottom">
                    <div class="row g-3 small">
                        <div class="col-sm-6">
                            <div class="text-muted mb-1">Nama Karyawan</div>
                            <div class="fw-semibold text-capitalize">{{ $k->nama }}</div>
                        </div>
                        <div class="col-sm-6">
                            <div class="text-muted mb-1">NIK</div>
                            <div class="fw-semibold">{{ $k->nik ?? '-' }}</div>
                        </div>
                        <div class="col-sm-6">
                            <div class="text-muted mb-1">Jabatan</div>
                            <div class="fw-semibold">{{ $k->jabatan->nama ?? '-' }}</div>
                        </div>
                        <div class="col-sm-6">
                            <div class="text-muted mb-1">Status Gaji</div>
                            <div class="fw-semibold text-capitalize">{{ $k->status_gaji }}</div>
                        </div>
                        <div class="col-sm-6">
                            <div class="text-muted mb-1">Hari Hadir</div>
                            <div class="fw-semibold">{{ $penggajian->total_hadir }} hari</div>
                        </div>
                        @if($penggajian->tgl_dibayar)
                        <div class="col-sm-6">
                            <div class="text-muted mb-1">Tanggal Dibayar</div>
                            <div class="fw-semibold">
                                {{ \Carbon\Carbon::parse($penggajian->tgl_dibayar)->translatedFormat('d M Y') }}
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                {{-- Rincian Gaji --}}
                <div class="card-body">
                    <div class="row g-4">

                        {{-- Pemasukan --}}
                        <div class="col-12">
                            <div class="small fw-semibold text-uppercase text-success mb-2" style="letter-spacing:.05em;">
                                <i class="fas fa-plus-circle me-1"></i> Pemasukan
                            </div>
                            <table class="table table-sm mb-0">
                                @forelse($pemasukan as $d)
                                <tr>
                                    <td class="text-muted ps-0 border-0 py-1">{{ $d->keterangan }}</td>
                                    <td class="text-end fw-semibold text-success pe-0 border-0 py-1">
                                        Rp {{ number_format($d->jumlah, 0, ',', '.') }}
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td class="text-muted ps-0 border-0 py-1" colspan="2">Tidak ada komponen pemasukan.</td>
                                </tr>
                                @endforelse
                                <tr class="border-top">
                                    <td class="ps-0 fw-semibold py-2">Total Pemasukan</td>
                                    <td class="text-end fw-bold text-success pe-0 py-2">
                                        Rp {{ number_format($pemasukan->sum('jumlah'), 0, ',', '.') }}
                                    </td>
                                </tr>
                            </table>
                        </div>

                        {{-- Potongan --}}
                        <div class="col-12">
                            <div class="small fw-semibold text-uppercase text-danger mb-2" style="letter-spacing:.05em;">
                                <i class="fas fa-minus-circle me-1"></i> Potongan
                            </div>
                            <table class="table table-sm mb-0">
                                @forelse($potongan as $d)
                                <tr>
                                    <td class="text-muted ps-0 border-0 py-1">{{ $d->keterangan }}</td>
                                    <td class="text-end fw-semibold text-danger pe-0 border-0 py-1">
                                        Rp {{ number_format($d->jumlah, 0, ',', '.') }}
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td class="text-muted ps-0 border-0 py-1" colspan="2">Tidak ada potongan.</td>
                                </tr>
                                @endforelse
                                <tr class="border-top">
                                    <td class="ps-0 fw-semibold py-2">Total Potongan</td>
                                    <td class="text-end fw-bold text-danger pe-0 py-2">
                                        Rp {{ number_format($potongan->sum('jumlah'), 0, ',', '.') }}
                                    </td>
                                </tr>
                            </table>
                        </div>

                    </div>
                </div>

                {{-- Total Bersih --}}
                <div class="card-footer bg-gradient-primary-to-secondary text-white rounded-bottom">
                    <div class="d-flex justify-content-between align-items-center py-1">
                        <span class="fw-semibold fs-6">Gaji Bersih Diterima</span>
                        <span class="fw-bold fs-5">
                            Rp {{ number_format($penggajian->total_gaji, 0, ',', '.') }}
                        </span>
                    </div>
                </div>

            </div>

            <div class="text-center text-muted small mt-3 mb-4">
                Slip gaji ini diterbitkan oleh sistem secara otomatis dan sah tanpa tanda tangan.
            </div>
        </div>
    </div>
</main>
@endsection
