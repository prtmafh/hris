@extends('pimpinan.layouts.app')

@section('title', 'Laporan Absensi')

@section('content')
<main>
    <header class="page-header page-header-compact page-header-light border-bottom bg-white mb-4 py-2">
        <div class="container-fluid px-4">
            <div class="page-header-content">
                <div class="row align-items-center justify-content-between pt-3">

                    <div class="col-auto mb-3">
                        <h1 class="page-header-title">
                            <div class="page-header-icon">
                                <i data-feather="calendar"></i>
                            </div>
                            Laporan Absensi
                        </h1>
                    </div>

                </div>
            </div>
        </div>
    </header>

    <div class="container-fluid px-4">

        <!-- Filter -->
        <div class="card mb-4">
            <div class="card-header"><i data-feather="filter" class="me-2"></i> Filter</div>
            <div class="card-body">
                <form method="GET" action="{{ route('pimpinan.laporan.absensi') }}">
                    <div class="row g-3">
                        {{-- <div class="col-md-3">
                            <label class="form-label">Bulan</label>
                            <select name="bulan" class="form-select">
                                @foreach(range(1,12) as $b)
                                <option value="{{ $b }}" {{ $bulan==$b ? 'selected' : '' }}>
                                    {{ \Carbon\Carbon::create()->month($b)->locale('id')->isoFormat('MMMM') }}
                                </option>
                                @endforeach
                            </select>
                        </div> --}}
                        <div class="col-md-2">
                            <label class="form-label">Tahun</label>
                            <select name="tahun" class="form-select">
                                @foreach($tahunList as $t)
                                <option value="{{ $t }}" {{ $tahun==$t ? 'selected' : '' }}>{{ $t }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Jabatan</label>
                            <select name="jabatan_id" class="form-select">
                                <option value="">Semua Jabatan</option>
                                @foreach($jabatan as $j)
                                <option value="{{ $j->id }}" {{ $jabatanId==$j->id ? 'selected' : '' }}>{{
                                    $j->nama_jabatan }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Karyawan</label>
                            <select name="karyawan_id" class="form-select">
                                <option value="">Semua Karyawan</option>
                                @foreach($karyawan as $k)
                                <option value="{{ $k->id }}" {{ $karyawanId==$k->id ? 'selected' : '' }}>{{ $k->nama }}
                                </option>
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
        {{-- <div class="row mb-4">
            <div class="col-6 col-xl-3 mb-3">
                <div class="card border-start-lg border-success h-100">
                    <div class="card-body">
                        <div class="small text-muted">Hadir</div>
                        <div class="h3 fw-bold text-success">{{ number_format($rekap['hadir'], 2) }}%</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-xl-3 mb-3">
                <div class="card border-start-lg border-warning h-100">
                    <div class="card-body">
                        <div class="small text-muted">Terlambat</div>
                        <div class="h3 fw-bold text-warning">{{ number_format($rekap['terlambat'], 2) }}%</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-xl-3 mb-3">
                <div class="card border-start-lg border-info h-100">
                    <div class="card-body">
                        <div class="small text-muted">Izin</div>
                        <div class="h3 fw-bold text-info">{{ number_format($rekap['izin'], 2) }}%</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-xl-3 mb-3">
                <div class="card border-start-lg border-danger h-100">
                    <div class="card-body">
                        <div class="small text-muted">Alpha</div>
                        <div class="h3 fw-bold text-danger">{{ number_format($rekap['alpha'], 2) }}%</div>
                    </div>
                </div>
            </div>
        </div> --}}

        <!-- Tabel -->
        <div class="card">
            <div class="card-header">
                Persentase Kehadiran -
                {{ $tahun }}
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped table-hover mb-0" id="datatablesSimple">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">No</th>
                                <th>Nama Karyawan</th>
                                {{-- <th>Jabatan</th> --}}
                                <th>Persentase Kehadiran</th>
                                <th class="text-center">
                                    Hadir
                                </th>
                                <th class="text-center">
                                    Terlambat
                                </th>
                                <th class="text-center">
                                    Izin
                                </th>
                                <th class="text-center">
                                    Alpha
                                </th>
                                <th class="text-center">
                                    Total Hari
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($rekapKaryawan as $i => $row)
                            <tr>
                                <td class="ps-4">{{ $i + 1 }}</td>
                                <td class="text-capitalize fw-semibold">{{ $row['nama'] }}</td>
                                {{-- <td class="text-muted small">{{ $row['jabatan'] }}</td> --}}
                                <td style="min-width: 260px;">
                                    <div class="d-flex flex-column">
                                        <div class="d-flex align-items-center mb-1">
                                            <span class="fw-bold me-2">{{ number_format($row['persentase'], 2)
                                                }}%</span>
                                        </div>
                                        <div class="progress" style="height: 8px; max-width: 320px;">
                                            <div class="progress-bar bg-success" role="progressbar"
                                                style="width: {{ $row['persentase'] }}%;"
                                                aria-valuenow="{{ $row['persentase'] }}" aria-valuemin="0"
                                                aria-valuemax="100">
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-green-soft text-green">
                                        {{ $row['jumlah_hadir'] }}
                                    </span>
                                </td>


                                <td class="text-center">
                                    <span class="badge bg-yellow-soft text-yellow">
                                        {{ $row['terlambat'] }}
                                    </span>
                                </td>


                                <td class="text-center">
                                    <span class="badge bg-blue-soft text-blue">
                                        {{ $row['izin'] }}
                                    </span>
                                </td>


                                <td class="text-center">
                                    <span class="badge bg-red-soft text-red">
                                        {{ $row['alpha'] }}
                                    </span>
                                </td>



                                <td class="text-center fw-bold">
                                    {{ $row['total'] }}
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-5">
                                    <i data-feather="inbox" class="mb-2"></i><br>Tidak ada data karyawan
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