@extends('admin.layouts.app')

@section('title', 'Peserta Training')

@section('content')
<main>
    <header class="page-header page-header-dark bg-gradient-primary-to-secondary pb-10">
        <div class="container-xl px-4">
            <div class="page-header-content pt-4">
                <div class="row align-items-center justify-content-between">
                    <div class="col-auto mt-4">
                        <h1 class="page-header-title">
                            <div class="page-header-icon"><i data-feather="users"></i></div>
                            Peserta Training
                        </h1>
                        <div class="page-header-subtitle">Daftar peserta training, kehadiran, nilai, dan kelengkapan sertifikat.</div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div class="container-xl px-4 mt-n10">
        <div class="card mb-4">
            <div class="card-header">Daftar Peserta Training</div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="datatablesSimple" class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Training</th>
                                <th>Karyawan</th>
                                <th>Status Kehadiran</th>
                                <th>Nilai</th>
                                <th>Sertifikat</th>
                                <th>Catatan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pesertaTraining as $index => $item)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    <div class="fw-semibold">{{ $item->judul_training ?: '-' }}</div>
                                    <div class="small text-muted">
                                        {{ $item->tgl_mulai ? \Carbon\Carbon::parse($item->tgl_mulai)->format('d/m/Y') : '-' }}
                                    </div>
                                </td>
                                <td>{{ $item->nama_karyawan ?: '-' }}</td>
                                <td>
                                    @php
                                        $badge = match($item->status_kehadiran) {
                                            'terdaftar' => 'secondary',
                                            'hadir' => 'success',
                                            'tidak_hadir' => 'danger',
                                            default => 'secondary',
                                        };
                                    @endphp
                                    <span class="badge bg-{{ $badge }}">{{ ucfirst(str_replace('_', ' ', $item->status_kehadiran)) }}</span>
                                </td>
                                <td>{{ $item->nilai !== null ? number_format($item->nilai, 2, ',', '.') : '-' }}</td>
                                <td>{{ $item->sertifikat ?: '-' }}</td>
                                <td>{{ $item->catatan ?: '-' }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">Belum ada data peserta training.</td>
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
