@extends('pimpinan.layouts.app')

@section('title', 'Laporan Penggajian')

@section('content')
<main>
    <header class="page-header page-header-dark bg-gradient-primary-to-secondary pb-10">
        <div class="container-xl px-4">
            <div class="page-header-content pt-4">
                <div class="row align-items-center justify-content-between">
                    <div class="col-auto mt-4">
                        <h1 class="page-header-title">
                            <div class="page-header-icon"><i data-feather="dollar-sign"></i></div>
                            Laporan Penggajian
                        </h1>
                        <div class="page-header-subtitle">Rekap penggajian karyawan per periode</div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div class="container-xl px-4 mt-n10">

        <!-- Filter -->
        <div class="card mb-4">
            <div class="card-header"><i data-feather="filter" class="me-2"></i> Filter Periode</div>
            <div class="card-body">
                <form method="GET" action="{{ route('pimpinan.laporan.penggajian') }}">
                    <div class="row g-3 align-items-end">
                        <div class="col-md-4">
                            <label class="form-label">Bulan</label>
                            <select name="bulan" class="form-select">
                                @foreach(range(1,12) as $b)
                                    <option value="{{ $b }}" {{ $bulan == $b ? 'selected' : '' }}>
                                        {{ \Carbon\Carbon::create()->month($b)->locale('id')->isoFormat('MMMM') }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Tahun</label>
                            <select name="tahun" class="form-select">
                                @foreach($tahunList as $t)
                                    <option value="{{ $t }}" {{ $tahun == $t ? 'selected' : '' }}>{{ $t }}</option>
                                @endforeach
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
                <div class="card border-start-lg border-success h-100">
                    <div class="card-body">
                        <div class="small text-muted">Total Gaji Dikeluarkan</div>
                        <div class="h4 fw-bold text-success">Rp {{ number_format($totalGaji, 0, ',', '.') }}</div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card border-start-lg border-primary h-100">
                    <div class="card-body">
                        <div class="small text-muted">Sudah Dibayar</div>
                        <div class="h4 fw-bold text-primary">{{ $sudahBayar }} karyawan</div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card border-start-lg border-warning h-100">
                    <div class="card-body">
                        <div class="small text-muted">Belum Dibayar</div>
                        <div class="h4 fw-bold text-warning">{{ $belumBayar }} karyawan</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabel -->
        <div class="card">
            <div class="card-header">
                Data Penggajian —
                {{ \Carbon\Carbon::create()->month($bulan)->locale('id')->isoFormat('MMMM') }} {{ $tahun }}
                <span class="badge bg-primary ms-2">{{ $penggajian->count() }} karyawan</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped table-hover mb-0" id="datatablesSimple">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">No</th>
                                <th>Nama Karyawan</th>
                                <th>Jabatan</th>
                                <th>Total Hadir</th>
                                <th>Total Lembur</th>
                                <th>Potongan</th>
                                <th>Total Gaji</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($penggajian as $i => $p)
                            <tr>
                                <td class="ps-4">{{ $i + 1 }}</td>
                                <td class="text-capitalize fw-semibold">{{ $p->karyawan->nama ?? '-' }}</td>
                                <td class="text-muted small">{{ $p->karyawan->jabatan->nama_jabatan ?? '-' }}</td>
                                <td>{{ $p->total_hadir }} hari</td>
                                <td>{{ $p->total_lembur ?? 0 }} jam</td>
                                <td class="text-danger">Rp {{ number_format($p->potongan ?? 0, 0, ',', '.') }}</td>
                                <td class="fw-bold text-success">Rp {{ number_format($p->total_gaji, 0, ',', '.') }}</td>
                                <td>
                                    @if($p->status === 'dibayar')
                                        <span class="badge bg-success-soft text-success">Dibayar</span>
                                    @else
                                        <span class="badge bg-warning-soft text-warning">Proses</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted py-5">
                                    <i data-feather="inbox" class="mb-2"></i><br>Tidak ada data penggajian
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                        @if($penggajian->count() > 0)
                        <tfoot class="table-light fw-bold">
                            <tr>
                                <td colspan="6" class="text-end ps-4">Total Gaji:</td>
                                <td class="text-success">Rp {{ number_format($totalGaji, 0, ',', '.') }}</td>
                                <td></td>
                            </tr>
                        </tfoot>
                        @endif
                    </table>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
