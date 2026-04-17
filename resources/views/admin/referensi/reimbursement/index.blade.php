@extends('admin.layouts.app')

@section('title', 'Reimbursement')

@section('content')
<main>
    <header class="page-header page-header-dark bg-gradient-primary-to-secondary pb-10">
        <div class="container-xl px-4">
            <div class="page-header-content pt-4">
                <div class="row align-items-center justify-content-between">
                    <div class="col-auto mt-4">
                        <h1 class="page-header-title">
                            <div class="page-header-icon"><i data-feather="credit-card"></i></div>
                            Reimbursement
                        </h1>
                        <div class="page-header-subtitle">Ringkasan pengajuan reimbursement karyawan dan status approval-nya.</div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div class="container-xl px-4 mt-n10">
        <div class="card mb-4">
            <div class="card-header">Daftar Reimbursement</div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="datatablesSimple" class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Karyawan</th>
                                <th>Kategori</th>
                                <th>Judul</th>
                                <th>Tgl Pengajuan</th>
                                <th>Jumlah Diajukan</th>
                                <th>Jumlah Disetujui</th>
                                <th>Status</th>
                                <th>Disetujui Oleh</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($reimbursement as $index => $item)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $item->nama_karyawan ?: '-' }}</td>
                                <td>{{ $item->nama_kategori ?: '-' }}</td>
                                <td>
                                    <div class="fw-semibold">{{ $item->judul }}</div>
                                    <div class="small text-muted">{{ $item->deskripsi ?: 'Tanpa deskripsi' }}</div>
                                </td>
                                <td>{{ \Carbon\Carbon::parse($item->tanggal_pengajuan)->format('d/m/Y') }}</td>
                                <td>Rp {{ number_format($item->jumlah_diajukan, 0, ',', '.') }}</td>
                                <td>{{ $item->jumlah_disetujui !== null ? 'Rp ' . number_format($item->jumlah_disetujui, 0, ',', '.') : '-' }}</td>
                                <td>
                                    @php
                                        $badge = match($item->status) {
                                            'pending' => 'warning',
                                            'disetujui' => 'info',
                                            'ditolak' => 'danger',
                                            'dibayar' => 'success',
                                            default => 'secondary',
                                        };
                                    @endphp
                                    <span class="badge bg-{{ $badge }} text-capitalize">{{ $item->status }}</span>
                                </td>
                                <td>{{ $item->nik_penyetuju ?: '-' }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted py-4">Belum ada data reimbursement.</td>
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
