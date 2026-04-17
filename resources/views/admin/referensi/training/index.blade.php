@extends('admin.layouts.app')

@section('title', 'Training')

@section('content')
<main>
    <header class="page-header page-header-dark bg-gradient-primary-to-secondary pb-10">
        <div class="container-xl px-4">
            <div class="page-header-content pt-4">
                <div class="row align-items-center justify-content-between">
                    <div class="col-auto mt-4">
                        <h1 class="page-header-title">
                            <div class="page-header-icon"><i data-feather="book-open"></i></div>
                            Training
                        </h1>
                        <div class="page-header-subtitle">Daftar program training internal, eksternal, dan online.</div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div class="container-xl px-4 mt-n10">
        <div class="card mb-4">
            <div class="card-header">Daftar Training</div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="datatablesSimple" class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Judul</th>
                                <th>Jenis</th>
                                <th>Penyelenggara</th>
                                <th>Periode</th>
                                <th>Durasi</th>
                                <th>Biaya</th>
                                <th>Kuota</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($training as $index => $item)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    <div class="fw-semibold">{{ $item->judul }}</div>
                                    <div class="small text-muted">{{ $item->lokasi ?: 'Lokasi belum diisi' }}</div>
                                </td>
                                <td><span class="badge bg-primary-soft text-primary text-capitalize">{{ $item->jenis }}</span></td>
                                <td>{{ $item->penyelenggara ?: '-' }}</td>
                                <td>
                                    {{ \Carbon\Carbon::parse($item->tgl_mulai)->format('d/m/Y') }}
                                    -
                                    {{ \Carbon\Carbon::parse($item->tgl_selesai)->format('d/m/Y') }}
                                </td>
                                <td>{{ $item->durasi_jam ? $item->durasi_jam . ' jam' : '-' }}</td>
                                <td>Rp {{ number_format($item->biaya, 0, ',', '.') }}</td>
                                <td>{{ $item->kuota_peserta ?: '-' }}</td>
                                <td>
                                    @php
                                        $badge = match($item->status) {
                                            'rencana' => 'secondary',
                                            'berlangsung' => 'info',
                                            'selesai' => 'success',
                                            'batal' => 'danger',
                                            default => 'secondary',
                                        };
                                    @endphp
                                    <span class="badge bg-{{ $badge }} text-capitalize">{{ $item->status }}</span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted py-4">Belum ada data training.</td>
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
