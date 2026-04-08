@extends('admin.layouts.app')

@section('title', 'Rekap Absensi')

@section('content')
<main>
    <header class="page-header page-header-dark bg-gradient-primary-to-secondary pb-10">
        <div class="container-xl px-4">
            <div class="page-header-content pt-4">
                <h1 class="page-header-title">
                    <div class="page-header-icon"><i data-feather="bar-chart-2"></i></div>
                    Rekap Absensi
                </h1>
                <div class="page-header-subtitle">Rekapitulasi kehadiran karyawan per periode</div>
            </div>
        </div>
    </header>

    <div class="container-xl px-4 mt-n10">

        {{-- FILTER --}}
        <div class="card mb-4">
            <div class="card-body">
                <form method="GET" action="{{ route('rekap.tahunan') }}" class="row g-2 align-items-end">
                    <div class="col-md-3">
                        <label class="form-label small mb-1">Tahun</label>
                        <select name="tahun" class="form-select form-select-sm">
                            @foreach($tahunList as $t)
                            <option value="{{ $t }}" {{ $tahun == $t ? 'selected' : '' }}>{{ $t }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small mb-1">Bulan (Opsional)</label>
                        <select name="bulan" class="form-select form-select-sm">
                            <option value="">Semua Bulan</option>
                            @foreach(['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'] as $i => $namaBulan)
                            <option value="{{ $i + 1 }}" {{ $bulan == ($i + 1) ? 'selected' : '' }}>{{ $namaBulan }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary btn-sm w-100">
                            <i data-feather="search"></i> Tampilkan
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- TABLE REKAP --}}
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>
                    Rekap Tahun {{ $tahun }}
                    @if($bulan)
                    &mdash;
                    {{ ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'][$bulan - 1] }}
                    @endif
                </span>
                <span class="text-muted small">{{ count($rekap) }} karyawan</span>
            </div>
            <div class="card-body">
                <table id="datatablesSimple" class="table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Karyawan</th>
                            <th>Jabatan</th>
                            <th class="text-center text-success">Hadir</th>
                            <th class="text-center text-warning">Terlambat</th>
                            <th class="text-center text-info">Izin</th>
                            <th class="text-center text-danger">Alpha</th>
                            <th class="text-center">Total Hari</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($rekap as $index => $r)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <img src="{{ $r['karyawan']->foto ? asset('storage/'.$r['karyawan']->foto) : 'https://ui-avatars.com/api/?name='.urlencode($r['karyawan']->nama) }}"
                                        width="36" height="36" class="rounded-circle me-2">
                                    <span class="fw-bold text-capitalize">{{ $r['karyawan']->nama }}</span>
                                </div>
                            </td>
                            <td class="text-capitalize">{{ optional($r['karyawan']->jabatan)->nama_jabatan ?? '-' }}</td>
                            <td class="text-center">
                                <span class="badge bg-success">{{ $r['hadir'] }}</span>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-warning text-dark">{{ $r['terlambat'] }}</span>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-info">{{ $r['izin'] }}</span>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-danger">{{ $r['alpha'] }}</span>
                            </td>
                            <td class="text-center fw-bold">{{ $r['total'] }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">Tidak ada data untuk periode ini.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</main>
@endsection
