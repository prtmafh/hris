@extends('admin.layouts.app')

@section('title', 'Data Penggajian')

@section('content')
<main>

    {{-- HEADER --}}
    <header class="page-header page-header-dark bg-gradient-primary-to-secondary pb-10">
        <div class="container-xl px-4">
            <div
                class="page-header-content pt-4 d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">
                <div>
                    <h1 class="page-header-title">
                        <div class="page-header-icon"><i data-feather="dollar-sign"></i></div>
                        Data Penggajian
                    </h1>
                    <div class="page-header-subtitle">
                        Kelola data gaji karyawan setiap periode.
                    </div>
                </div>
                <div>
                    <button type="button" class="btn btn-white btn-sm px-4" data-bs-toggle="modal"
                        data-bs-target="#modalGenerateGaji">
                        <i class="fas fa-magic me-2"></i> Generate Gaji
                    </button>
                </div>
            </div>
        </div>
    </header>

    <div class="container-xl px-4 mt-n10">

        {{-- FLASH MESSAGE --}}
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm mb-4" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        {{-- FILTER --}}
        <div class="card mb-4 shadow-sm">
            <div class="card-header d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2">
                <div>
                    <div class="fw-bold">Filter Data Gaji</div>
                    <div class="small text-muted">
                        Gunakan filter untuk menemukan data penggajian dengan cepat.
                    </div>
                </div>

                @if($hasFilter)
                <a href="{{ route('admin.penggajian') }}" class="btn btn-outline-secondary btn-sm">
                    <i data-feather="x"></i> Reset Filter
                </a>
                @endif
            </div>

            <div class="card-body">
                <form method="GET" action="{{ route('admin.penggajian') }}" class="row g-3 align-items-end">

                    {{-- BULAN --}}
                    <div class="col-md-2">
                        <label class="form-label small mb-1">Bulan</label>
                        <select name="bulan" class="form-select form-select-sm">
                            <option value="">Semua</option>
                            @for($i=1; $i<=12; $i++) <option value="{{ $i }}" {{ request('bulan')==$i?'selected':'' }}>
                                {{ $i }}
                                </option>
                                @endfor
                        </select>
                    </div>

                    {{-- TAHUN --}}
                    <div class="col-md-2">
                        <label class="form-label small mb-1">Tahun</label>
                        <input type="number" name="tahun" class="form-control form-control-sm"
                            value="{{ request('tahun') }}">
                    </div>

                    {{-- KARYAWAN --}}
                    <div class="col-md-4">
                        <label class="form-label small mb-1">Karyawan</label>
                        <select name="karyawan_id" class="form-select form-select-sm">
                            <option value="">Semua karyawan</option>
                            @foreach($karyawanList as $k)
                            <option value="{{ $k->id }}" {{ (string) request('karyawan_id')===(string) $k->id ?
                                'selected' : '' }}>
                                {{ $k->nama }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- STATUS --}}
                    <div class="col-md-2">
                        <label class="form-label small mb-1">Status</label>
                        <select name="status" class="form-select form-select-sm">
                            <option value="">Semua status</option>
                            <option value="proses" {{ request('status')==='proses' ? 'selected' : '' }}>Proses</option>
                            <option value="dibayar" {{ request('status')==='dibayar' ? 'selected' : '' }}>Dibayar
                            </option>
                        </select>
                    </div>

                    {{-- BUTTON --}}
                    <div class="col-md-2 d-flex gap-2">
                        <button type="submit" class="btn btn-primary btn-sm w-100">
                            <i data-feather="search"></i> Filter
                        </button>
                    </div>

                </form>
            </div>
        </div>

        {{-- TABLE --}}
        <div class="card shadow-sm">
            <div class="card-header d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2">
                <div>
                    <div class="fw-bold">Daftar Gaji Karyawan</div>
                </div>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table id="datatablesSimple" class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th class="">No</th>
                                <th class="">Karyawan</th>
                                <th class="">Periode</th>
                                {{-- <th class="">Hadir</th> --}}
                                {{-- <th class="">Lembur</th> --}}
                                {{-- <th class="">Potongan</th> --}}
                                {{-- <th class="">Total Gaji</th> --}}
                                <th class="">Status</th>
                                <th class=" text-center">Aksi</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($penggajian as $index => $g)
                            <tr>

                                {{-- NO --}}
                                <td class="text-muted">
                                    {{ $penggajian->firstItem() + $index }}
                                </td>

                                {{-- KARYAWAN --}}
                                <td>
                                    <div class="fw-semibold text-capitalize">
                                        {{ $g->karyawan->nama ?? '-' }}
                                    </div>
                                    <div class="small text-muted">
                                        Gaji #{{ $g->id }}
                                    </div>
                                </td>

                                {{-- PERIODE --}}
                                <td>
                                    <div class="fw-semibold">
                                        {{ $g->periode_bulan }}/{{ $g->periode_tahun }}
                                    </div>
                                </td>

                                {{-- HADIR --}}
                                {{-- <td>
                                    <span class="badge bg-light text-dark border">
                                        {{ $g->total_hadir }} hari
                                    </span>
                                </td> --}}

                                {{-- LEMBUR --}}
                                {{-- <td class="fw-semibold">
                                    Rp {{ number_format($g->total_lembur, 0, ',', '.') }}
                                </td> --}}

                                {{-- POTONGAN --}}
                                {{-- <td class="fw-semibold text-danger">
                                    Rp {{ number_format($g->potongan, 0, ',', '.') }}
                                </td> --}}

                                {{-- TOTAL GAJI --}}
                                {{-- <td class="fw-bold text-success">
                                    Rp {{ number_format($g->total_gaji, 0, ',', '.') }}
                                </td> --}}

                                {{-- STATUS --}}
                                <td>
                                    <span class="badge bg-{{ $g->status == 'dibayar' ? 'success' : 'warning' }}">
                                        {{ $g->status === 'dibayar' ? 'Dibayar' : 'Proses' }}
                                    </span>
                                </td>

                                {{-- AKSI --}}
                                <td class="text-center">
                                    <a href="{{ route('admin.penggajian.show', $g->id) }}"
                                        class="btn btn-sm btn-primary rounded-pill px-3">
                                        <i class="fas fa-eye fa-xs me-1"></i> Detail
                                    </a>
                                </td>

                            </tr>

                            @empty
                            {{-- <tr>
                                <td colspan="9" class="text-center text-muted py-5">
                                    <i class="fas fa-inbox fa-2x mb-2 d-block opacity-50"></i>
                                    Belum ada data penggajian.
                                </td>
                            </tr> --}}
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- PAGINATION --}}
                {{-- <div class="mt-4">
                    {{ $penggajian->withQueryString()->links() }}
                </div> --}}
            </div>
        </div>

    </div>
</main>

{{-- Modal Generate Gaji --}}
<div class="modal fade" id="modalGenerateGaji" tabindex="-1" aria-labelledby="modalGenerateGajiLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form method="POST" action="{{ route('admin.penggajian.generate') }}">
            @csrf
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-gradient-primary-to-secondary text-white border-0 rounded-top">
                    <h5 class="modal-title fw-semibold" id="modalGenerateGajiLabel">
                        <i class="fas fa-magic me-2"></i> Generate Gaji Otomatis
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body p-4">
                    <div class="alert alert-info d-flex gap-2 align-items-start py-2 px-3 mb-4">
                        <i class="fas fa-info-circle mt-1 shrink-0"></i>
                        <small>
                            Sistem akan menghitung gaji semua karyawan aktif berdasarkan data absensi dan lembur
                            pada periode yang dipilih. Karyawan yang sudah memiliki data gaji pada periode tersebut
                            akan dilewati otomatis.
                        </small>
                    </div>

                    <div class="row g-3">
                        <div class="col-6">
                            <label class="form-label fw-semibold">Bulan <span class="text-danger">*</span></label>
                            <select name="bulan" class="form-select" required>
                                @php
                                $namaBulan = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                                'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                                @endphp
                                @for($i = 1; $i <= 12; $i++) <option value="{{ $i }}" {{ $i==now()->month ? 'selected' :
                                    '' }}>
                                    {{ $namaBulan[$i] }}
                                    </option>
                                    @endfor
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-semibold">Tahun <span class="text-danger">*</span></label>
                            <select name="tahun" class="form-select" required>
                                @for($y = now()->year; $y >= now()->year - 3; $y--)
                                <option value="{{ $y }}" {{ $y==now()->year ? 'selected' : '' }}>
                                    {{ $y }}
                                </option>
                                @endfor
                            </select>
                        </div>
                    </div>
                </div>

                <div class="modal-footer border-top bg-light rounded-bottom d-flex justify-content-between">
                    <button type="button" class="btn btn-outline-secondary btn-sm px-4" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i> Batal
                    </button>
                    <button type="submit" class="btn btn-primary btn-sm px-4">
                        <i class="fas fa-magic me-1"></i> Generate Sekarang
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection