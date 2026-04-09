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
            </div>
        </div>
    </header>

    <div class="container-xl px-4 mt-n10">

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
                                <th class="text-muted small text-uppercase">No</th>
                                <th class="text-muted small text-uppercase">Karyawan</th>
                                <th class="text-muted small text-uppercase">Periode</th>
                                <th class="text-muted small text-uppercase">Hadir</th>
                                <th class="text-muted small text-uppercase">Lembur</th>
                                <th class="text-muted small text-uppercase">Potongan</th>
                                <th class="text-muted small text-uppercase">Total Gaji</th>
                                <th class="text-muted small text-uppercase">Status</th>
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
                                <td>
                                    <span class="badge bg-light text-dark border">
                                        {{ $g->total_hadir }} hari
                                    </span>
                                </td>

                                {{-- LEMBUR --}}
                                <td class="fw-semibold">
                                    Rp {{ number_format($g->total_lembur, 0, ',', '.') }}
                                </td>

                                {{-- POTONGAN --}}
                                <td class="fw-semibold text-danger">
                                    Rp {{ number_format($g->potongan, 0, ',', '.') }}
                                </td>

                                {{-- TOTAL GAJI --}}
                                <td class="fw-bold text-success">
                                    Rp {{ number_format($g->total_gaji, 0, ',', '.') }}
                                </td>

                                {{-- STATUS --}}
                                <td>
                                    <span class="badge bg-{{ $g->status == 'dibayar' ? 'success' : 'warning' }}">
                                        {{ $g->status }}
                                    </span>
                                </td>

                            </tr>

                            @empty

                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- PAGINATION --}}
                <div class="mt-4">
                    {{ $penggajian->withQueryString()->links() }}
                </div>
            </div>
        </div>

    </div>
</main>
@endsection