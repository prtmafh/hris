@extends('admin.layouts.app')

@section('title', 'Data Lembur')

@section('content')
<main>
    <header class="page-header page-header-dark bg-gradient-primary-to-secondary pb-10">
        <div class="container-xl px-4">
            <div class="page-header-content pt-4 d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="page-header-title">
                        <div class="page-header-icon"><i data-feather="clock"></i></div>
                        Data Lembur
                    </h1>
                    <div class="page-header-subtitle">Kelola data lembur karyawan</div>
                </div>
            </div>
        </div>
    </header>

    <div class="container-xl px-4 mt-n10">

        {{-- FILTER --}}
        <div class="card mb-4 shadow-sm">
            <div class="card-header d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2">
                <div>
                    <div class="fw-bold">Filter Data Lembur</div>
                    <div class="small text-muted">
                        Gunakan filter untuk menemukan data lembur dengan lebih cepat.
                    </div>
                </div>

                @if($hasFilter)
                <a href="{{ route('admin.lembur') }}" class="btn btn-outline-secondary btn-sm">
                    <i data-feather="x"></i> Reset Filter
                </a>
                @endif
            </div>

            <div class="card-body">
                <form method="GET" action="{{ route('admin.lembur') }}" class="row g-3 align-items-end">

                    {{-- TANGGAL --}}
                    <div class="col-md-4">
                        <label class="form-label small mb-1">Tanggal</label>
                        <input type="date" name="tanggal" class="form-control form-control-sm"
                            value="{{ request('tanggal') }}">
                    </div>

                    {{-- KARYAWAN --}}
                    <div class="col-md-4">
                        <label class="form-label small mb-1">Karyawan</label>
                        <select name="karyawan_id" class="form-select form-select-sm">
                            <option value="">Semua karyawan</option>
                            @foreach($karyawanList as $k)
                            <option value="{{ $k->id }}" {{ (string) request('karyawan_id')===(string) $k->id ?
                                'selected' : ''
                                }}>
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
                            <option value="pending" {{ request('status')==='pending' ? 'selected' : '' }}>Pending
                            </option>
                            <option value="disetujui" {{ request('status')==='disetujui' ? 'selected' : '' }}>Disetujui
                            </option>
                            <option value="ditolak" {{ request('status')==='ditolak' ? 'selected' : '' }}>Ditolak
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
                    <div class="fw-bold">Daftar Data Lembur</div>
                </div>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table id="datatablesSimple" class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th class="text-muted small text-uppercase">No</th>
                                <th class="text-muted small text-uppercase">Karyawan</th>
                                <th class="text-muted small text-uppercase">Tanggal</th>
                                <th class="text-muted small text-uppercase">Jam Mulai</th>
                                <th class="text-muted small text-uppercase">Jam Selesai</th>
                                <th class="text-muted small text-uppercase">Total Jam</th>
                                <th class="text-muted small text-uppercase">Upah</th>
                                <th class="text-muted small text-uppercase">Status</th>
                                <th class="text-muted small text-uppercase text-center">Aksi</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($lembur as $index => $l)

                            @php
                            $badge = match($l->status) {
                            'pending' => 'warning',
                            'disetujui' => 'success',
                            'ditolak' => 'danger',
                            default => 'secondary',
                            };
                            @endphp

                            <tr>
                                <td class="text-muted">
                                    {{ $lembur->firstItem() + $index }}
                                </td>

                                <td>
                                    <div class="fw-semibold text-capitalize">
                                        {{ $l->karyawan->nama ?? '-' }}
                                    </div>
                                    <div class="small text-muted">
                                        Lembur #{{ $l->id }}
                                    </div>
                                </td>

                                <td>
                                    <div class="fw-semibold">
                                        {{ $l->tanggal->format('d/m/Y') }}
                                    </div>
                                    <div class="small text-muted">
                                        {{ $l->tanggal->translatedFormat('l') }}
                                    </div>
                                </td>

                                {{-- JAM MULAI --}}
                                <td class="fw-semibold">
                                    {{ $l->jam_mulai }}
                                </td>

                                {{-- JAM SELESAI --}}
                                <td class="fw-semibold">
                                    {{ $l->jam_selesai }}
                                </td>

                                <td>
                                    <span class="badge bg-light text-dark border">
                                        {{ $l->total_jam }} jam
                                    </span>
                                </td>

                                <td class="fw-semibold">
                                    Rp {{ number_format($l->total_upah, 0, ',', '.') }}
                                </td>

                                <td>
                                    <span class="badge bg-{{ $badge }} text-capitalize">
                                        {{ $l->status }}
                                    </span>
                                </td>

                                <td class="text-center">
                                    @if($l->status === 'pending')
                                    <div class="d-inline-flex gap-2">

                                        <form action="{{ route('lembur.approve', $l->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-success btn-sm">
                                                <i data-feather="check"></i>
                                            </button>
                                        </form>

                                        <form action="{{ route('lembur.reject', $l->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-danger btn-sm">
                                                <i data-feather="x"></i>
                                            </button>
                                        </form>

                                    </div>
                                    @else
                                    <span class="small text-muted">Sudah diproses</span>
                                    @endif
                                </td>
                            </tr>

                            @empty
                            {{-- <tr>
                                <td colspan="9" class="text-center text-muted">
                                    Tidak ada data lembur
                                </td>
                            </tr> --}}
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- PAGINATION --}}
                <div class="mt-4">
                    {{ $lembur->withQueryString()->links() }}
                </div>
            </div>
        </div>

    </div>
</main>
@endsection