@extends('karyawan.layouts.app')

@section('title', 'Slip Gaji')

@section('content')
<main>
    <header class="page-header page-header-dark bg-gradient-primary-to-secondary pb-10">
        <div class="container-xl px-4">
            <div
                class="page-header-content pt-4 d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">
                <div>
                    <h1 class="page-header-title">
                        <div class="page-header-icon"><i data-feather="credit-card"></i></div>
                        Slip Gaji
                    </h1>
                    <div class="page-header-subtitle">Lihat riwayat penggajian Anda berdasarkan tahun yang dipilih.
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div class="container-xl px-4 mt-n10">
        {{-- <div class="card mb-4 shadow-sm">
            <div class="card-header">
                <div class="fw-bold">Filter Slip Gaji</div>
                <div class="small text-muted">Pilih tahun untuk menampilkan data slip gaji Anda.</div>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('karyawan.slip_gaji') }}" class="row g-3 align-items-end">
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
        </div> --}}

        <div class="card shadow-sm">
            <div class="card-header d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2">
                <div>
                    <div class="fw-bold">Riwayat Slip Gaji</div>
                    <div class="small text-muted">Data penggajian {{ $karyawan->nama }} untuk tahun {{ $tahun }}.</div>
                </div>
                <span class="badge bg-primary">{{ $penggajian->count() }} data</span>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="datatablesSimple" class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th class="text-muted small text-uppercase">Periode</th>
                                <th class="text-muted small text-uppercase">Hadir</th>
                                <th class="text-muted small text-uppercase">Lembur</th>
                                <th class="text-muted small text-uppercase">Potongan</th>
                                <th class="text-muted small text-uppercase">Total Gaji</th>
                                <th class="text-muted small text-uppercase">Status</th>
                                <th class="text-muted small text-uppercase">Tanggal Dibayar</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($penggajian as $gaji)
                            <tr>
                                <td>
                                    <div class="fw-semibold">
                                        {{ \Carbon\Carbon::create()->month($gaji->periode_bulan)->translatedFormat('F')
                                        }}
                                        {{ $gaji->periode_tahun }}
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border">{{ $gaji->total_hadir ?? 0 }}
                                        hari</span>
                                </td>
                                <td class="fw-semibold">
                                    Rp {{ number_format($gaji->total_lembur ?? 0, 0, ',', '.') }}
                                </td>
                                <td class="fw-semibold text-danger">
                                    Rp {{ number_format($gaji->potongan ?? 0, 0, ',', '.') }}
                                </td>
                                <td class="fw-bold text-success">
                                    Rp {{ number_format($gaji->total_gaji ?? 0, 0, ',', '.') }}
                                </td>
                                <td>
                                    <span class="badge bg-{{ $gaji->status === 'dibayar' ? 'success' : 'warning' }}">
                                        {{ $gaji->status }}
                                    </span>
                                </td>
                                <td>
                                    {{ $gaji->tgl_dibayar ?
                                    \Carbon\Carbon::parse($gaji->tgl_dibayar)->translatedFormat('d M Y') : '-' }}
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">
                                    Belum ada data slip gaji untuk tahun {{ $tahun }}.
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