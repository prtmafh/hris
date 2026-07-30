@extends('admin.layouts.app')

@section('title', 'Master Komponen Gaji')

@section('content')
    <main>
        <header class="page-header page-header-compact page-header-light border-bottom bg-white mb-4">
            <div class="container-xl px-4">
                <div class="page-header-content">
                    <div class="row align-items-center justify-content-between pt-3">
                        <div class="col-auto mb-3">
                            <h1 class="page-header-title">
                                <div class="page-header-icon">
                                    <i data-feather="list"></i>
                                </div>
                                Master Komponen Gaji
                            </h1>
                        </div>

                        <div class="col-auto mb-3">
                            <button type="button" class="btn btn-sm btn-light text-primary" data-bs-toggle="modal"
                                data-bs-target="#modalTambah">
                                <i data-feather="plus" class="me-1"></i>
                                Tambah Komponen
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <div class="container-xl px-4">
            <div class="alert alert-info">
                Master hanya mendefinisikan nama dan jenis komponen.
                Nominal atau persentasenya diatur berbeda untuk setiap karyawan melalui menu
                <a href="{{ route('admin.komponen-gaji.karyawan') }}">
                    Komponen Karyawan
                </a>.
            </div>

            <div class="card">
                <div class="card-header">
                    Daftar Komponen
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table id="datatablesSimple" class="table table-hover align-middle">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama</th>
                                    <th>Tipe</th>
                                    <th>Digunakan</th>
                                    <th>Status</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse ($komponen as $index => $item)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>

                                        <td>
                                            <div class="fw-semibold text-capitalize">
                                                {{ $item->nama }}
                                            </div>

                                            <div class="small text-muted">
                                                {{ $item->keterangan ?: 'Tanpa keterangan' }}
                                            </div>
                                        </td>

                                        <td>
                                            @if ($item->tipe === 'pemasukan')
                                                <span class="badge bg-green-soft text-green">
                                                    Pemasukan
                                                </span>
                                            @else
                                                <span class="badge bg-red-soft text-red">
                                                    Potongan
                                                </span>
                                            @endif
                                        </td>

                                        <td>
                                            {{ $item->pengaturan_karyawan_count }} karyawan
                                        </td>

                                        <td>
                                            <span
                                                class="badge {{ $item->status === 'aktif' ? 'bg-green-soft text-green' : 'bg-secondary-soft text-secondary' }}">
                                                {{ ucfirst($item->status) }}
                                            </span>
                                        </td>

                                        <td class="text-center">
                                            <form method="POST"
                                                action="{{ route('admin.komponen-gaji.toggle', $item->id) }}"
                                                class="d-inline">
                                                @csrf

                                                <button type="submit"
                                                    class="btn btn-datatable btn-icon btn-transparent-dark"
                                                    title="Ubah status">
                                                    <i data-feather="refresh-cw"></i>
                                                </button>
                                            </form>

                                            <button type="button" class="btn btn-datatable btn-icon btn-transparent-dark"
                                                data-bs-toggle="modal" data-bs-target="#modalEdit{{ $item->id }}"
                                                title="Edit">
                                                <i data-feather="edit"></i>
                                            </button>
                                            <button type="button"
                                                class="btn btn-datatable btn-icon btn-transparent-dark text-danger"
                                                onclick="confirmDelete({{ $item->id }})">
                                                <i data-feather="trash-2"></i>
                                            </button>

                                            <form id="delete-form-{{ $item->id }}"
                                                action="{{ route('admin.komponen-gaji.destroy', $item->id) }}"
                                                method="POST" class="d-none">
                                                @csrf
                                                @method('DELETE')

                                                {{-- <button type="submit"
                                                    class="btn btn-datatable btn-icon btn-transparent-dark text-danger"
                                                    title="Hapus">
                                                    <i data-feather="trash-2"></i>
                                                </button> --}}
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted py-4">
                                            Belum ada komponen gaji.
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

    {{-- Modal tambah --}}
    <div class="modal fade" id="modalTambah" tabindex="-1" aria-labelledby="modalTambahLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form method="POST" action="{{ route('admin.komponen-gaji.store') }}" class="modal-content">
                @csrf

                <div class="modal-header">
                    <h5 class="modal-title" id="modalTambahLabel">
                        Tambah Komponen Gaji
                    </h5>

                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>

                <div class="modal-body">
                    @include('admin.komponen_gaji.partials.form', [
                        'komponenItem' => null,
                    ])
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                        Batal
                    </button>

                    <button type="submit" class="btn btn-primary">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Semua modal edit dibuat di luar table --}}
    @foreach ($komponen as $item)
        <div class="modal fade" id="modalEdit{{ $item->id }}" tabindex="-1"
            aria-labelledby="modalEditLabel{{ $item->id }}" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <form method="POST" action="{{ route('admin.komponen-gaji.update', $item->id) }}" class="modal-content">
                    @csrf
                    @method('PUT')

                    <div class="modal-header">
                        <h5 class="modal-title" id="modalEditLabel{{ $item->id }}">
                            Edit Komponen Gaji
                        </h5>

                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>

                    <div class="modal-body">
                        @include('admin.komponen_gaji.partials.form', [
                            'komponenItem' => $item,
                        ])
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                            Batal
                        </button>

                        <button type="submit" class="btn btn-primary">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endforeach
@endsection
