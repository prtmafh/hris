@extends('karyawan.layouts.app')

@section('title', 'Absensi Saya')

@section('content')
<main>
    <header class="page-header page-header-dark bg-gradient-primary-to-secondary pb-10">
        <div class="container-xl px-4">
            <div
                class="page-header-content pt-4 d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">
                <div>
                    <h1 class="page-header-title">
                        <div class="page-header-icon"><i data-feather="calendar"></i></div>
                        Absensi Saya
                    </h1>
                    <div class="page-header-subtitle">Lihat rekap kehadiran Anda berdasarkan periode yang dipilih.</div>
                </div>
            </div>
        </div>
    </header>

    <div class="container-xl px-4 mt-n10">
        <div class="card mb-4 shadow-sm">
            <div class="card-header">
                <div class="fw-bold">Filter Absensi</div>
                <div class="small text-muted">Pilih bulan dan tahun untuk melihat riwayat kehadiran Anda.</div>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('karyawan.absensi') }}" class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label class="form-label small mb-1">Bulan</label>
                        <select name="bulan" class="form-select form-select-sm">
                            @foreach(range(1, 12) as $b)
                            <option value="{{ $b }}" {{ $bulan==$b ? 'selected' : '' }}>
                                {{ \Carbon\Carbon::create()->month($b)->translatedFormat('F') }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small mb-1">Tahun</label>
                        <select name="tahun" class="form-select form-select-sm">
                            @foreach($daftarTahun as $t)
                            <option value="{{ $t }}" {{ $tahun==$t ? 'selected' : '' }}>{{ $t }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4 d-flex gap-2">
                        <button type="submit" class="btn btn-primary btn-sm w-100">
                            <i data-feather="search"></i> Tampilkan
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- <div class="row g-4 mb-4">
            <div class="col-md-6 col-xl-3">
                <div class="card border-start border-success border-4 h-100 shadow-sm">
                    <div class="card-body">
                        <div class="small text-success text-uppercase fw-bold mb-1">Hadir</div>
                        <div class="fs-3 fw-bold">{{ $totalHadir }}</div>
                        <div class="small text-muted">Jumlah kehadiran pada periode ini</div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xl-3">
                <div class="card border-start border-warning border-4 h-100 shadow-sm">
                    <div class="card-body">
                        <div class="small text-warning text-uppercase fw-bold mb-1">Terlambat</div>
                        <div class="fs-3 fw-bold">{{ $totalTerlambat }}</div>
                        <div class="small text-muted">Total keterlambatan yang tercatat</div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xl-3">
                <div class="card border-start border-primary border-4 h-100 shadow-sm">
                    <div class="card-body">
                        <div class="small text-primary text-uppercase fw-bold mb-1">Izin</div>
                        <div class="fs-3 fw-bold">{{ $totalIzin }}</div>
                        <div class="small text-muted">Absensi berstatus izin</div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xl-3">
                <div class="card border-start border-danger border-4 h-100 shadow-sm">
                    <div class="card-body">
                        <div class="small text-danger text-uppercase fw-bold mb-1">Alpha</div>
                        <div class="fs-3 fw-bold">{{ $totalAlpha }}</div>
                        <div class="small text-muted">Ketidakhadiran tanpa keterangan</div>
                    </div>
                </div>
            </div>
        </div> --}}

        <div class="card shadow-sm">
            <div class="card-header d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2">
                <div>
                    <div class="fw-bold">Riwayat Absensi</div>
                    <div class="small text-muted">
                        Periode {{ \Carbon\Carbon::create()->month($bulan)->translatedFormat('F') }} {{ $tahun }}
                    </div>
                </div>
                <span class="badge bg-primary">{{ $absensi->count() }} hari</span>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="datatablesSimple" class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th class="text-muted small text-uppercase">No</th>
                                <th class="text-muted small text-uppercase">Tanggal</th>
                                <th class="text-muted small text-uppercase">Jam Masuk</th>
                                <th class="text-muted small text-uppercase">Jam Pulang</th>
                                <th class="text-muted small text-uppercase">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($absensi as $i => $a)
                            @php
                            $badge = match($a->status) {
                            'hadir' => 'success',
                            'terlambat' => 'warning',
                            'izin' => 'primary',
                            'alpha' => 'danger',
                            default => 'secondary',
                            };
                            @endphp
                            <tr>
                                <td class="text-muted">{{ $i + 1 }}</td>
                                <td>
                                    <div class="fw-semibold">
                                        {{ \Carbon\Carbon::parse($a->tanggal)->translatedFormat('d F Y') }}
                                    </div>
                                </td>
                                <td>{{ $a->jam_masuk ? \Carbon\Carbon::parse($a->jam_masuk)->format('H:i') : '-' }}</td>
                                <td>{{ $a->jam_keluar ? \Carbon\Carbon::parse($a->jam_keluar)->format('H:i') : '-' }}
                                </td>
                                <td>
                                    <span class="badge bg-{{ $badge }} text-capitalize">{{ $a->status }}</span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">
                                    Belum ada data absensi untuk periode ini.
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