@extends('admin.layouts.app')

@section('title', 'Jadwal Kerja')

@section('content')
<main>
    <header class="page-header page-header-dark bg-gradient-primary-to-secondary pb-10">
        <div class="container-xl px-4">
            <div class="page-header-content pt-4">
                <div class="row align-items-center justify-content-between">
                    <div class="col-auto mt-4">
                        <h1 class="page-header-title">
                            <div class="page-header-icon"><i data-feather="calendar"></i></div>
                            Jadwal Kerja
                        </h1>
                        <div class="page-header-subtitle">Ringkasan hari kerja, jam masuk, dan toleransi keterlambatan.</div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div class="container-xl px-4 mt-n10">
        <div class="card mb-4">
            <div class="card-header">Daftar Jadwal Kerja</div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="datatablesSimple" class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Hari</th>
                                <th>Status Hari</th>
                                <th>Jam Masuk</th>
                                <th>Jam Pulang</th>
                                <th>Toleransi Telat</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($jadwalKerja as $index => $item)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td class="text-capitalize fw-semibold">{{ $item->hari }}</td>
                                <td>
                                    <span class="badge bg-{{ $item->is_hari_kerja ? 'success' : 'secondary' }}">
                                        {{ $item->is_hari_kerja ? 'Hari Kerja' : 'Libur' }}
                                    </span>
                                </td>
                                <td>{{ $item->jam_masuk ?: '-' }}</td>
                                <td>{{ $item->jam_pulang ?: '-' }}</td>
                                <td>{{ $item->toleransi_telat_menit }} menit</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">Belum ada data jadwal kerja.</td>
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
