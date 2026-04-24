@extends('admin.layouts.app')

@section('title', 'Data Izin')

@section('content')
<main>

    {{-- HEADER --}}
    <header class="page-header page-header-compact page-header-light border-bottom bg-white mb-4 py-2">
        <div class="container-xl px-4">
            <div class="page-header-content">
                <div class="row align-items-center justify-content-between pt-3">

                    <div class="col-auto mb-3">
                        <h1 class="page-header-title">
                            <div class="page-header-icon"><i data-feather="file-text"></i></div>
                            Data Izin
                        </h1>
                    </div>

                </div>
            </div>
        </div>
    </header>

    <div class="container-xl px-4">

        @php
        $hasFilter = request()->filled('tanggal') || request()->filled('karyawan_id') || request()->filled('status');
        @endphp

        {{-- FILTER --}}
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">

                <div>
                    <div class="fw-semibold">Filter Data</div>
                    <div class="small text-muted">Cari data izin dengan cepat</div>
                </div>

                @if($hasFilter)
                <a href="{{ route('admin.izin') }}" class="btn btn-light btn-sm">
                    <i data-feather="x"></i>
                </a>
                @endif

            </div>

            <div class="card-body">

                <form method="GET" action="{{ route('admin.izin') }}" class="row gx-2 gy-2 align-items-end">

                    <div class="col-md-4">
                        <label class="small mb-1">Tanggal</label>
                        <input type="date" name="tanggal" class="form-control form-control-sm"
                            value="{{ request('tanggal') }}">
                    </div>

                    <div class="col-md-4">
                        <label class="small mb-1">Karyawan</label>
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

                    <div class="col-md-2">
                        <label class="small mb-1">Status</label>
                        <select name="status" class="form-select form-select-sm">
                            <option value="">Semua</option>
                            <option value="pending" {{ request('status')==='pending' ? 'selected' : '' }}>Pending
                            </option>
                            <option value="disetujui" {{ request('status')==='disetujui' ? 'selected' : '' }}>Disetujui
                            </option>
                            <option value="ditolak" {{ request('status')==='ditolak' ? 'selected' : '' }}>Ditolak
                            </option>
                        </select>
                    </div>

                    <div class="col-md-2 d-flex gap-1">
                        <button type="submit" class="btn btn-primary btn-sm w-100">
                            <i data-feather="search"></i>
                        </button>
                    </div>

                </form>

            </div>
        </div>

        {{-- TABLE --}}
        <div class="card">

            <div class="card-header">
                Daftar Pengajuan Izin
            </div>

            <div class="card-body">

                <table id="datatablesSimple" class="table ">

                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Karyawan</th>
                            <th>Tanggal</th>
                            <th>Jenis</th>
                            <th>Keterangan</th>
                            <th>Status</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($izin as $index => $i)

                        @php
                        $badge = match($i->status_approval) {
                        'pending' => 'yellow',
                        'disetujui' => 'green',
                        'ditolak' => 'red',
                        default => 'secondary',
                        };
                        @endphp

                        <tr>

                            <td>{{ $izin->firstItem() + $index }}</td>

                            {{-- KARYAWAN --}}
                            <td>
                                <div class="fw-semibold text-capitalize">
                                    {{ $i->karyawan->nama ?? '-' }}
                                </div>
                                {{-- <div class="small text-muted">
                                    #{{ $i->id }}
                                </div> --}}
                            </td>

                            {{-- TANGGAL --}}
                            <td>
                                <div class="fw-semibold">
                                    {{ $i->tanggal->format('d M Y') }}
                                </div>
                                {{-- <div class="small text-muted">
                                    {{ $i->tanggal->translatedFormat('l') }}
                                </div> --}}
                            </td>

                            {{-- JENIS --}}
                            <td>
                                <span class="badge bg-light text-dark border text-capitalize">
                                    {{ $i->jenis_izin }}
                                </span>
                            </td>

                            {{-- KETERANGAN --}}
                            <td class="text-muted" style="min-width:200px;">
                                {{ $i->keterangan ?: '-' }}
                            </td>

                            {{-- STATUS --}}
                            <td>
                                <span class="badge bg-{{ $badge }}-soft text-{{ $badge }} text-capitalize">
                                    {{ $i->status_approval }}
                                </span>
                            </td>

                            {{-- AKSI --}}
                            <td class="text-center">

                                @if($i->status_approval === 'pending')

                                <form action="{{ route('izin.approve', $i->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit"
                                        class="btn btn-datatable btn-icon btn-transparent-dark text-success me-1">
                                        <i data-feather="check"></i>
                                    </button>
                                </form>

                                <form action="{{ route('izin.reject', $i->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit"
                                        class="btn btn-datatable btn-icon btn-transparent-dark text-danger">
                                        <i data-feather="x"></i>
                                    </button>
                                </form>

                                @else
                                <span class="small text-muted">Diproses</span>
                                @endif

                            </td>

                        </tr>

                        @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">
                                Tidak ada data izin
                            </td>
                        </tr>
                        @endforelse
                    </tbody>

                </table>

                <div class="mt-4">
                    {{ $izin->withQueryString()->links() }}
                </div>

            </div>
        </div>

    </div>
</main>
@endsection