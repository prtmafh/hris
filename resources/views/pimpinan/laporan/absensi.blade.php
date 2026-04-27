@extends('pimpinan.layouts.app')

@section('title', 'Laporan Absensi')

@section('content')
<main>
    <header class="page-header page-header-dark bg-gradient-primary-to-secondary pb-10">
        <div class="container-xl px-4">
            <div class="page-header-content pt-4">
                <div class="row align-items-center justify-content-between">
                    <div class="col-auto mt-4">
                        <h1 class="page-header-title">
                            <div class="page-header-icon"><i data-feather="calendar"></i></div>
                            Laporan Absensi
                        </h1>
                        <div class="page-header-subtitle">Data kehadiran karyawan per periode</div>
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
                <form method="GET" action="{{ route('pimpinan.laporan.absensi') }}">
                    <div class="row g-3">
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
                            <label class="form-label">Jabatan</label>
                            <select name="jabatan_id" class="form-select">
                                <option value="">Semua Jabatan</option>
                                @foreach($jabatan as $j)
                                    <option value="{{ $j->id }}" {{ $jabatanId == $j->id ? 'selected' : '' }}>{{ $j->nama_jabatan }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Karyawan</label>
                            <select name="karyawan_id" class="form-select">
                                <option value="">Semua Karyawan</option>
                                @foreach($karyawan as $k)
                                    <option value="{{ $k->id }}" {{ $karyawanId == $k->id ? 'selected' : '' }}>{{ $k->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-1 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100">Tampilkan</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Rekap Cards -->
        <div class="row mb-4">
            <div class="col-6 col-xl-3 mb-3">
                <div class="card border-start-lg border-success h-100">
                    <div class="card-body">
                        <div class="small text-muted">Hadir</div>
                        <div class="h3 fw-bold text-success">{{ $rekap['hadir'] }}</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-xl-3 mb-3">
                <div class="card border-start-lg border-warning h-100">
                    <div class="card-body">
                        <div class="small text-muted">Terlambat</div>
                        <div class="h3 fw-bold text-warning">{{ $rekap['terlambat'] }}</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-xl-3 mb-3">
                <div class="card border-start-lg border-info h-100">
                    <div class="card-body">
                        <div class="small text-muted">Izin</div>
                        <div class="h3 fw-bold text-info">{{ $rekap['izin'] }}</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-xl-3 mb-3">
                <div class="card border-start-lg border-danger h-100">
                    <div class="card-body">
                        <div class="small text-muted">Alpha</div>
                        <div class="h3 fw-bold text-danger">{{ $rekap['alpha'] }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabel -->
        <div class="card">
            <div class="card-header">
                Data Absensi —
                {{ \Carbon\Carbon::create()->month($bulan)->locale('id')->isoFormat('MMMM') }} {{ $tahun }}
                <span class="badge bg-primary ms-2">{{ $absensi->count() }} record</span>
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
                                <th>Jam Masuk</th>
                                <th>Jam Keluar</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($absensi as $i => $a)
                            <tr>
                                <td class="ps-4">{{ $i + 1 }}</td>
                                <td>{{ \Carbon\Carbon::parse($a->tanggal)->locale('id')->isoFormat('D MMM YYYY') }}</td>
                                <td class="text-capitalize fw-semibold">{{ $a->karyawan->nama ?? '-' }}</td>
                                <td class="text-muted small">{{ $a->karyawan->jabatan->nama_jabatan ?? '-' }}</td>
                                <td>
                                    <span class="badge bg-light text-dark border">
                                        {{ $a->jam_masuk ? \Illuminate\Support\Str::of($a->jam_masuk)->limit(5, '') : '-' }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border">
                                        {{ $a->jam_keluar ? \Illuminate\Support\Str::of($a->jam_keluar)->limit(5, '') : '-' }}
                                    </span>
                                </td>
                                <td>
                                    @switch($a->status)
                                        @case('hadir') <span class="badge bg-success-soft text-success">Hadir</span> @break
                                        @case('terlambat') <span class="badge bg-warning-soft text-warning">Terlambat</span> @break
                                        @case('izin') <span class="badge bg-info-soft text-info">Izin</span> @break
                                        @case('alpha') <span class="badge bg-danger-soft text-danger">Alpha</span> @break
                                        @default <span class="badge bg-secondary-soft text-secondary">-</span>
                                    @endswitch
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-5">
                                    <i data-feather="inbox" class="mb-2"></i><br>Tidak ada data absensi
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
