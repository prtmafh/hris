@extends('admin.layouts.app')

@section('title', 'Lowongan')

@section('content')
<main>
    <header class="page-header page-header-dark bg-gradient-primary-to-secondary pb-10">
        <div class="container-xl px-4">
            <div class="page-header-content pt-4">
                <div class="row align-items-center justify-content-between">
                    <div class="col-auto mt-4">
                        <h1 class="page-header-title">
                            <div class="page-header-icon"><i data-feather="user-plus"></i></div>
                            Lowongan
                        </h1>
                        <div class="page-header-subtitle">Kelola lowongan rekrutmen karyawan</div>
                    </div>
                    <div class="col-auto mt-4">
                        <button class="btn btn-white btn-sm" data-bs-toggle="modal" data-bs-target="#modalTambah">
                            <i data-feather="plus" class="me-1"></i> Tambah Lowongan
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div class="container-xl px-4 mt-n10">
        <div class="card mb-4">
            <div class="card-header">Daftar Lowongan</div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="datatablesSimple" class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Lowongan</th>
                                <th>Periode</th>
                                <th>Kuota</th>
                                <th>Pelamar</th>
                                <th>Status</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($lowongan as $index => $item)
                            @php
                            $statusClass = [
                                'draft' => 'secondary',
                                'aktif' => 'success',
                                'ditutup' => 'dark',
                            ][$item->status] ?? 'secondary';
                            @endphp
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    <div class="fw-bold">{{ $item->judul }}</div>
                                    <small class="text-muted">{{ $item->jabatan->nama_jabatan ?? '-' }}</small>
                                    <div class="mt-2 text-muted small">
                                        {{ \Illuminate\Support\Str::limit(strip_tags($item->deskripsi), 90) }}
                                    </div>
                                </td>
                                <td>
                                    <div>{{ optional($item->tanggal_buka)->format('d/m/Y') }}</div>
                                    <small class="text-muted">s.d. {{ optional($item->tanggal_tutup)->format('d/m/Y') }}</small>
                                </td>
                                <td>{{ $item->kuota }} orang</td>
                                <td>
                                    <span class="badge bg-info">{{ $item->pelamar_count }} pelamar</span>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $statusClass }}">{{ ucfirst($item->status) }}</span>
                                </td>
                                <td class="text-center">
                                    <form action="{{ route('admin.lowongan.toggle', $item->id) }}" method="POST"
                                        class="d-inline">
                                        @csrf
                                        <button type="submit"
                                            class="btn btn-datatable btn-icon btn-{{ $item->status === 'aktif' ? 'secondary' : 'success' }}"
                                            title="{{ $item->status === 'aktif' ? 'Tutup Lowongan' : 'Aktifkan Lowongan' }}">
                                            <i data-feather="{{ $item->status === 'aktif' ? 'pause-circle' : 'play-circle' }}"></i>
                                        </button>
                                    </form>

                                    <button class="btn btn-datatable btn-icon btn-warning mx-1" data-bs-toggle="modal"
                                        data-bs-target="#modalEdit{{ $item->id }}" title="Edit">
                                        <i data-feather="edit"></i>
                                    </button>

                                    <button class="btn btn-datatable btn-icon btn-danger"
                                        onclick="confirmDeleteLowongan({{ $item->id }})" title="Hapus">
                                        <i data-feather="trash-2"></i>
                                    </button>

                                    <form id="delete-lowongan-{{ $item->id }}"
                                        action="{{ route('admin.lowongan.destroy', $item->id) }}" method="POST"
                                        class="d-none">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </td>
                            </tr>

                            <div class="modal fade" id="modalEdit{{ $item->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-lg modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Edit Lowongan</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <form action="{{ route('admin.lowongan.update', $item->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-body">
                                                @include('admin.lowongan.partials.form', [
                                                'jabatan' => $jabatan,
                                                'lowongan' => $item,
                                                'submitLabel' => 'Simpan Perubahan'
                                                ])
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-light"
                                                    data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">Belum ada data lowongan.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</main>

<div class="modal fade" id="modalTambah" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Lowongan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.lowongan.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    @include('admin.lowongan.partials.form', [
                    'jabatan' => $jabatan,
                    'lowongan' => null,
                    'submitLabel' => 'Simpan'
                    ])
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function confirmDeleteLowongan(id) {
        Swal.fire({
            title: 'Hapus Lowongan?',
            text: 'Data lowongan yang sudah memiliki pelamar tidak dapat dihapus.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-lowongan-' + id).submit();
            }
        });
    }
</script>
@endpush
