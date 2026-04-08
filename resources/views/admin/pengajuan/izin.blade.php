@extends('admin.layouts.app')

@section('title', 'Data Izin')

@section('content')
<main>
    <header class="page-header page-header-dark bg-gradient-primary-to-secondary pb-10">
        <div class="container-xl px-4">
            <div class="page-header-content pt-4 d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">
                <div>
                    <h1 class="page-header-title">
                        <div class="page-header-icon"><i data-feather="file-text"></i></div>
                        Data Izin
                    </h1>
                    <div class="page-header-subtitle">Kelola pengajuan izin karyawan dan tindak lanjuti persetujuannya.</div>
                </div>
                <a href="{{ route('admin.izin') }}" class="btn btn-white btn-sm">
                    <i data-feather="refresh-cw"></i> Muat Ulang
                </a>
            </div>
        </div>
    </header>

    <div class="container-xl px-4 mt-n10">
        @php
            $totalIzin = $izin->total();
            $pendingCount = $izin->getCollection()->where('status_approval', 'pending')->count();
            $approvedCount = $izin->getCollection()->where('status_approval', 'disetujui')->count();
            $rejectedCount = $izin->getCollection()->where('status_approval', 'ditolak')->count();
            $hasFilter = request()->filled('tanggal') || request()->filled('karyawan_id') || request()->filled('status');
        @endphp

        <div class="row g-4 mb-4">
            <div class="col-md-6 col-xl-3">
                <div class="card border-0 h-100 shadow-sm">
                    <div class="card-body">
                        <div class="small text-muted mb-1">Total pengajuan</div>
                        <div class="fs-3 fw-bold">{{ $totalIzin }}</div>
                        <div class="small text-muted">Seluruh data izin yang tersedia</div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xl-3">
                <div class="card border-start border-warning border-4 h-100 shadow-sm">
                    <div class="card-body">
                        <div class="small text-warning text-uppercase fw-bold mb-1">Pending</div>
                        <div class="fs-3 fw-bold">{{ $pendingCount }}</div>
                        <div class="small text-muted">Menunggu persetujuan pada halaman ini</div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xl-3">
                <div class="card border-start border-success border-4 h-100 shadow-sm">
                    <div class="card-body">
                        <div class="small text-success text-uppercase fw-bold mb-1">Disetujui</div>
                        <div class="fs-3 fw-bold">{{ $approvedCount }}</div>
                        <div class="small text-muted">Pengajuan yang sudah diproses</div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xl-3">
                <div class="card border-start border-danger border-4 h-100 shadow-sm">
                    <div class="card-body">
                        <div class="small text-danger text-uppercase fw-bold mb-1">Ditolak</div>
                        <div class="fs-3 fw-bold">{{ $rejectedCount }}</div>
                        <div class="small text-muted">Pengajuan yang tidak disetujui</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-4 shadow-sm">
            <div class="card-header d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2">
                <div>
                    <div class="fw-bold">Filter Data Izin</div>
                    <div class="small text-muted">Gunakan filter untuk menemukan pengajuan tertentu dengan lebih cepat.</div>
                </div>
                @if($hasFilter)
                <a href="{{ route('admin.izin') }}" class="btn btn-outline-secondary btn-sm">
                    <i data-feather="x"></i> Reset Filter
                </a>
                @endif
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('admin.izin') }}" class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label class="form-label small mb-1">Tanggal</label>
                        <input type="date" name="tanggal" class="form-control form-control-sm"
                            value="{{ request('tanggal') }}">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label small mb-1">Karyawan</label>
                        <select name="karyawan_id" class="form-select form-select-sm">
                            <option value="">Semua karyawan</option>
                            @foreach($karyawanList as $k)
                            <option value="{{ $k->id }}" {{ (string) request('karyawan_id') === (string) $k->id ? 'selected' : '' }}>
                                {{ $k->nama }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label class="form-label small mb-1">Status</label>
                        <select name="status" class="form-select form-select-sm">
                            <option value="">Semua status</option>
                            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="disetujui" {{ request('status') === 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                            <option value="ditolak" {{ request('status') === 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                        </select>
                    </div>

                    <div class="col-md-2 d-flex gap-2">
                        <button type="submit" class="btn btn-primary btn-sm w-100">
                            <i data-feather="search"></i> Filter
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-header d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2">
                <div>
                    <div class="fw-bold">Daftar Pengajuan Izin</div>
                    <div class="small text-muted">Menampilkan {{ $izin->count() }} dari {{ $izin->total() }} data izin.</div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th class="text-muted small text-uppercase">No</th>
                                <th class="text-muted small text-uppercase">Karyawan</th>
                                <th class="text-muted small text-uppercase">Tanggal</th>
                                <th class="text-muted small text-uppercase">Jenis</th>
                                <th class="text-muted small text-uppercase">Keterangan</th>
                                <th class="text-muted small text-uppercase">Status</th>
                                <th class="text-muted small text-uppercase text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($izin as $index => $i)
                            @php
                                $badge = match($i->status_approval) {
                                    'pending' => 'warning',
                                    'disetujui' => 'success',
                                    'ditolak' => 'danger',
                                    default => 'secondary',
                                };
                            @endphp
                            <tr>
                                <td class="text-muted">{{ $izin->firstItem() + $index }}</td>
                                <td>
                                    <div class="fw-semibold text-capitalize">{{ $i->karyawan->nama ?? '-' }}</div>
                                    <div class="small text-muted">Pengajuan #{{ $i->id }}</div>
                                </td>
                                <td>
                                    <div class="fw-semibold">{{ $i->tanggal->format('d/m/Y') }}</div>
                                    <div class="small text-muted">{{ $i->tanggal->translatedFormat('l') }}</div>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border text-capitalize">{{ $i->jenis_izin }}</span>
                                </td>
                                <td class="text-muted" style="min-width: 220px;">
                                    {{ $i->keterangan ?: 'Tidak ada keterangan tambahan.' }}
                                </td>
                                <td>
                                    <span class="badge bg-{{ $badge }} text-capitalize">{{ $i->status_approval }}</span>
                                </td>
                                <td class="text-center">
                                    @if($i->status_approval === 'pending')
                                    <div class="d-inline-flex gap-2">
                                        <form action="{{ route('izin.approve', $i->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-success btn-sm" title="Setujui">
                                                <i data-feather="check"></i>
                                            </button>
                                        </form>

                                        <form action="{{ route('izin.reject', $i->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-danger btn-sm" title="Tolak">
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
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <div class="mb-2 text-muted">
                                        <i data-feather="inbox"></i>
                                    </div>
                                    <div class="fw-semibold">Belum ada data izin</div>
                                    <div class="small text-muted">Coba ubah filter atau tambahkan data pengajuan baru.</div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $izin->withQueryString()->links() }}
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
