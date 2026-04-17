@extends('admin.layouts.app')

@section('title', 'Hari Libur')

@section('content')
<main>
    <header class="page-header page-header-dark bg-gradient-primary-to-secondary pb-10">
        <div class="container-xl px-4">
            <div class="page-header-content pt-4">
                <div class="row align-items-center justify-content-between">
                    <div class="col-auto mt-4">
                        <h1 class="page-header-title">
                            <div class="page-header-icon"><i data-feather="sun"></i></div>
                            Hari Libur
                        </h1>
                        <div class="page-header-subtitle">Daftar hari libur nasional, cuti bersama, dan libur perusahaan.</div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div class="container-xl px-4 mt-n10">
        <div class="card mb-4">
            <div class="card-header">Daftar Hari Libur</div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="datatablesSimple" class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>Nama</th>
                                <th>Jenis</th>
                                <th>Berulang Tahunan</th>
                                <th>Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($hariLibur as $index => $item)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}</td>
                                <td class="fw-semibold">{{ $item->nama }}</td>
                                <td><span class="badge bg-info-soft text-info text-capitalize">{{ str_replace('_', ' ', $item->jenis) }}</span></td>
                                <td>{{ $item->berulang_tahunan ? 'Ya' : 'Tidak' }}</td>
                                <td>{{ $item->keterangan ?: '-' }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">Belum ada data hari libur.</td>
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
