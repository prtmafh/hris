@extends('admin.layouts.app')

@section('title', 'Detail Penggajian')

@section('content')
@php
    $namaBulan = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                  'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
    $pemasukan = $penggajian->details->where('tipe', 'pemasukan');
    $potongan  = $penggajian->details->where('tipe', 'potongan');
@endphp
<main>
    <header class="page-header page-header-dark bg-gradient-primary-to-secondary pb-10">
        <div class="container-xl px-4">
            <div class="page-header-content pt-4">
                <div class="row align-items-center justify-content-between">
                    <div class="col-auto mt-4">
                        <h1 class="page-header-title">
                            <div class="page-header-icon"><i data-feather="file-text"></i></div>
                            Detail Penggajian
                        </h1>
                        <div class="page-header-subtitle">
                            {{ $penggajian->karyawan->nama }} &mdash;
                            {{ $namaBulan[$penggajian->periode_bulan] }} {{ $penggajian->periode_tahun }}
                        </div>
                    </div>
                    <div class="col-auto mt-4 d-flex gap-2">
                        <a href="{{ route('admin.penggajian') }}" class="btn btn-white btn-sm">
                            <i class="fas fa-arrow-left me-1"></i> Kembali
                        </a>
                        @if($penggajian->status === 'proses')
                        <form method="POST" action="{{ route('admin.penggajian.bayar', $penggajian->id) }}">
                            @csrf
                            <button type="submit" class="btn btn-success btn-sm"
                                onclick="return confirm('Tandai gaji ini sebagai sudah dibayar?')">
                                <i class="fas fa-check me-1"></i> Tandai Dibayar
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div class="container-xl px-4 mt-n10">

        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm mb-4" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        <div class="row g-4">

            {{-- Info Karyawan --}}
            <div class="col-lg-4">
                <div class="card shadow-sm h-100">
                    <div class="card-header d-flex align-items-center gap-2 py-3">
                        <i class="fas fa-user-circle text-primary"></i>
                        <span class="fw-semibold">Informasi Karyawan</span>
                    </div>
                    <div class="card-body">
                        <dl class="row mb-0 small">
                            <dt class="col-5 text-muted fw-normal">Nama</dt>
                            <dd class="col-7 fw-semibold mb-2">{{ $penggajian->karyawan->nama }}</dd>

                            <dt class="col-5 text-muted fw-normal">NIK</dt>
                            <dd class="col-7 mb-2">{{ $penggajian->karyawan->nik ?? '-' }}</dd>

                            <dt class="col-5 text-muted fw-normal">Jabatan</dt>
                            <dd class="col-7 mb-2">{{ $penggajian->karyawan->jabatan->nama ?? '-' }}</dd>

                            <dt class="col-5 text-muted fw-normal">Status Gaji</dt>
                            <dd class="col-7 mb-2 text-capitalize">{{ $penggajian->karyawan->status_gaji }}</dd>

                            <dt class="col-5 text-muted fw-normal">Periode</dt>
                            <dd class="col-7 mb-2 fw-semibold">
                                {{ $namaBulan[$penggajian->periode_bulan] }} {{ $penggajian->periode_tahun }}
                            </dd>

                            <dt class="col-5 text-muted fw-normal">Status</dt>
                            <dd class="col-7 mb-0">
                                <span class="badge bg-{{ $penggajian->status === 'dibayar' ? 'success' : 'warning' }}">
                                    {{ $penggajian->status === 'dibayar' ? 'Dibayar' : 'Proses' }}
                                </span>
                            </dd>

                            @if($penggajian->tgl_dibayar)
                            <dt class="col-5 text-muted fw-normal mt-2">Tgl Dibayar</dt>
                            <dd class="col-7 mb-0 mt-2">
                                {{ \Carbon\Carbon::parse($penggajian->tgl_dibayar)->translatedFormat('d M Y') }}
                            </dd>
                            @endif
                        </dl>
                    </div>
                </div>
            </div>

            {{-- Ringkasan --}}
            <div class="col-lg-8">
                <div class="row g-3 mb-4">
                    <div class="col-sm-4">
                        <div class="card shadow-sm text-center py-3">
                            <div class="text-muted small mb-1">Hari Hadir</div>
                            <div class="fs-4 fw-bold text-primary">{{ $penggajian->total_hadir }}</div>
                            <div class="text-muted small">hari</div>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="card shadow-sm text-center py-3">
                            <div class="text-muted small mb-1">Total Lembur</div>
                            <div class="fs-5 fw-bold text-success">
                                Rp {{ number_format($penggajian->total_lembur, 0, ',', '.') }}
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="card shadow-sm text-center py-3">
                            <div class="text-muted small mb-1">Total Potongan</div>
                            <div class="fs-5 fw-bold text-danger">
                                Rp {{ number_format($penggajian->potongan, 0, ',', '.') }}
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Tabel Detail --}}
                <div class="card shadow-sm">
                    <div class="card-header d-flex align-items-center gap-2 py-3">
                        <i class="fas fa-list text-primary"></i>
                        <span class="fw-semibold">Rincian Komponen Gaji</span>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4">Keterangan</th>
                                    <th class="text-end">Jumlah</th>
                                    <th class="text-center pe-4" style="width:110px;">Tipe</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($pemasukan->isNotEmpty())
                                <tr class="table-light">
                                    <td colspan="3" class="ps-4 py-2">
                                        <small class="text-muted fw-semibold text-uppercase" style="letter-spacing:.05em;">
                                            <i class="fas fa-plus-circle text-success me-1"></i> Pemasukan
                                        </small>
                                    </td>
                                </tr>
                                @foreach($pemasukan as $d)
                                <tr>
                                    <td class="ps-4">{{ $d->keterangan }}</td>
                                    <td class="text-end fw-semibold text-success">
                                        Rp {{ number_format($d->jumlah, 0, ',', '.') }}
                                    </td>
                                    <td class="text-center pe-4">
                                        <span class="badge bg-success-soft text-success">Pemasukan</span>
                                    </td>
                                </tr>
                                @endforeach
                                @endif

                                @if($potongan->isNotEmpty())
                                <tr class="table-light">
                                    <td colspan="3" class="ps-4 py-2">
                                        <small class="text-muted fw-semibold text-uppercase" style="letter-spacing:.05em;">
                                            <i class="fas fa-minus-circle text-danger me-1"></i> Potongan
                                        </small>
                                    </td>
                                </tr>
                                @foreach($potongan as $d)
                                <tr>
                                    <td class="ps-4">{{ $d->keterangan }}</td>
                                    <td class="text-end fw-semibold text-danger">
                                        Rp {{ number_format($d->jumlah, 0, ',', '.') }}
                                    </td>
                                    <td class="text-center pe-4">
                                        <span class="badge bg-danger-soft text-danger">Potongan</span>
                                    </td>
                                </tr>
                                @endforeach
                                @endif

                                @if($pemasukan->isEmpty() && $potongan->isEmpty())
                                <tr>
                                    <td colspan="3" class="text-center text-muted py-4">
                                        Belum ada rincian komponen gaji.
                                    </td>
                                </tr>
                                @endif
                            </tbody>
                            <tfoot class="table-light">
                                <tr>
                                    <th class="ps-4 py-3">Total Gaji Bersih</th>
                                    <th class="text-end py-3 text-success fs-6">
                                        Rp {{ number_format($penggajian->total_gaji, 0, ',', '.') }}
                                    </th>
                                    <th class="pe-4"></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</main>
@endsection
