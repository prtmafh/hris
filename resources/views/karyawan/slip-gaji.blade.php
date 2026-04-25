@extends('karyawan.layouts.app')

@section('title', 'Slip Gaji')

@section('content')
<main>
    <header class="page-header page-header-compact page-header-light border-bottom bg-white mb-4 py-2">
        <div class="container-xl px-4">
            <div class="page-header-content">
                <div class="row align-items-center justify-content-between pt-3">
                    <div class="col-auto mb-3">
                        <h1 class="page-header-title">
                            <div class="page-header-icon"><i data-feather="file-text"></i></div>
                            Slip Gaji
                        </h1>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div class="container-xl px-4">
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

        <div class="card">
            <div class="card-header d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2">
                <div>
                    <div class="fw-bold">Riwayat Slip Gaji</div>
                    <div class="small text-muted">Data penggajian {{ $karyawan->nama }} untuk tahun {{ $tahun }}.</div>
                </div>
                <span class="badge bg-primary">{{ $penggajian->count() }} data</span>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="datatablesSimple" data-simple-datatable class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Periode</th>
                                <th>Hadir</th>
                                <th>Lembur</th>
                                <th>Potongan</th>
                                <th>Total Gaji</th>
                                <th>Status</th>
                                <th>Tanggal Dibayar</th>
                                <th class="text-muted small text-uppercase text-center">Slip</th>
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
                                    @php $badgeGaji = $gaji->status === 'dibayar' ? 'green' : 'yellow'; @endphp
                                    <span class="badge bg-{{ $badgeGaji }}-soft text-{{ $badgeGaji }} text-capitalize">
                                        {{ $gaji->status }}
                                    </span>
                                </td>
                                <td>
                                    {{ $gaji->tgl_dibayar ?
                                    \Carbon\Carbon::parse($gaji->tgl_dibayar)->translatedFormat('d M Y') : '-' }}
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('karyawan.slip_gaji.show', $gaji->id) }}"
                                        class="btn btn-datatable btn-icon btn-transparent-dark" title="Lihat Slip">
                                        <i data-feather="eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted py-4">
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