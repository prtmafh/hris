@extends('pimpinan.layouts.app')

@section('title', 'Laporan Izin')

@section('content')
<main>
    <header class="page-header page-header-dark bg-gradient-primary-to-secondary pb-10">
        <div class="container-xl px-4">
            <div class="page-header-content pt-4">
                <div class="row align-items-center justify-content-between">
                    <div class="col-auto mt-4">
                        <h1 class="page-header-title">
                            <div class="page-header-icon"><i data-feather="file-text"></i></div>
                            Laporan Izin
                        </h1>
                        <div class="page-header-subtitle">Data pengajuan izin karyawan per periode</div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div class="container-xl px-4 mt-n10">

        <!-- Filter -->
        <div class="card mb-4">
            <div class="card-header"><i data-feather="filter" class="me-2"></i> Filter</div>
            <div class="card-body">
                <form method="GET" action="{{ route('pimpinan.laporan.izin') }}">
                    <div class="row g-3 align-items-end">
                        <div class="col-md-3">
                            <label class="form-label">Bulan</label>
                            <select name="bulan" class="form-select">
                                @foreach(range(1,12) as $b)
                                    <option value="{{ $b }}" {{ $bulan == $b ? 'selected' : '' }}>
                                        {{ \Carbon\Carbon::create()->month($b)->locale('id')->isoFormat('MMMM') }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Tahun</label>
                            <select name="tahun" class="form-select">
                                @foreach($tahunList as $t)
                                    <option value="{{ $t }}" {{ $tahun == $t ? 'selected' : '' }}>{{ $t }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select">
                                <option value="">Semua Status</option>
                                <option value="pending" {{ $status === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="disetujui" {{ $status === 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                                <option value="ditolak" {{ $status === 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary w-100">Tampilkan</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Rekap Cards -->
        <div class="row mb-4">
            <div class="col-md-4 mb-3">
                <div class="card border-start-lg border-warning h-100">
                    <div class="card-body">
                        <div class="small text-muted">Pending</div>
                        <div class="h3 fw-bold text-warning">{{ $rekap['pending'] }}</div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card border-start-lg border-success h-100">
                    <div class="card-body">
                        <div class="small text-muted">Disetujui</div>
                        <div class="h3 fw-bold text-success">{{ $rekap['disetujui'] }}</div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card border-start-lg border-danger h-100">
                    <div class="card-body">
                        <div class="small text-muted">Ditolak</div>
                        <div class="h3 fw-bold text-danger">{{ $rekap['ditolak'] }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabel -->
        <div class="card">
            <div class="card-header">
                Data Izin —
                {{ \Carbon\Carbon::create()->month($bulan)->locale('id')->isoFormat('MMMM') }} {{ $tahun }}
                <span class="badge bg-primary ms-2">{{ $izin->count() }} pengajuan</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped table-hover mb-0" id="datatablesSimple">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">No</th>
                                <th>Tanggal</th>
                                <th>Nama Karyawan</th>
                                <th>Jabatan</th>
                                <th>Jenis Izin</th>
                                <th>Keterangan</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($izin as $i => $iz)
                            <tr>
                                <td class="ps-4">{{ $i + 1 }}</td>
                                <td>{{ \Carbon\Carbon::parse($iz->tanggal)->locale('id')->isoFormat('D MMM YYYY') }}</td>
                                <td class="text-capitalize fw-semibold">{{ $iz->karyawan->nama ?? '-' }}</td>
                                <td class="text-muted small">{{ $iz->karyawan->jabatan->nama_jabatan ?? '-' }}</td>
                                <td class="text-capitalize">{{ $iz->jenis_izin ?? '-' }}</td>
                                <td class="text-muted small">{{ \Illuminate\Support\Str::limit($iz->keterangan, 50) }}</td>
                                <td>
                                    @switch($iz->status_approval)
                                        @case('pending') <span class="badge bg-warning-soft text-warning">Pending</span> @break
                                        @case('disetujui') <span class="badge bg-success-soft text-success">Disetujui</span> @break
                                        @case('ditolak') <span class="badge bg-danger-soft text-danger">Ditolak</span> @break
                                        @default <span class="badge bg-secondary-soft text-secondary">-</span>
                                    @endswitch
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-5">
                                    <i data-feather="inbox" class="mb-2"></i><br>Tidak ada data izin
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
