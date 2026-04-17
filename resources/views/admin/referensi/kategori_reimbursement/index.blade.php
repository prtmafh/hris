@extends('admin.layouts.app')

@section('title', 'Kategori Reimbursement')

@section('content')
<main>
    <header class="page-header page-header-dark bg-gradient-primary-to-secondary pb-10">
        <div class="container-xl px-4">
            <div class="page-header-content pt-4">
                <div class="row align-items-center justify-content-between">
                    <div class="col-auto mt-4">
                        <h1 class="page-header-title">
                            <div class="page-header-icon"><i data-feather="tag"></i></div>
                            Kategori Reimbursement
                        </h1>
                        <div class="page-header-subtitle">Daftar kategori reimbursement beserta plafon dan status penggunaannya.</div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div class="container-xl px-4 mt-n10">
        <div class="card mb-4">
            <div class="card-header">Daftar Kategori Reimbursement</div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="datatablesSimple" class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>Deskripsi</th>
                                <th>Plafon/Bulan</th>
                                <th>Plafon/Pengajuan</th>
                                <th>Perlu Bukti</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($kategoriReimbursement as $index => $item)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td class="fw-semibold">{{ $item->nama }}</td>
                                <td>{{ $item->deskripsi ?: '-' }}</td>
                                <td>{{ $item->plafon_per_bulan !== null ? 'Rp ' . number_format($item->plafon_per_bulan, 0, ',', '.') : '-' }}</td>
                                <td>{{ $item->plafon_per_pengajuan !== null ? 'Rp ' . number_format($item->plafon_per_pengajuan, 0, ',', '.') : '-' }}</td>
                                <td>{{ $item->perlu_bukti ? 'Ya' : 'Tidak' }}</td>
                                <td>
                                    <span class="badge bg-{{ $item->status === 'aktif' ? 'success' : 'secondary' }} text-capitalize">
                                        {{ $item->status }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">Belum ada data kategori reimbursement.</td>
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
