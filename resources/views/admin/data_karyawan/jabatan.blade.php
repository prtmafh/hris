@extends('admin.layouts.app')

@section('title', 'Jabatan')

@section('content')

<main>
    <header class="page-header page-header-dark bg-gradient-primary-to-secondary pb-10">
        <div class="container-xl px-4">
            <div class="page-header-content pt-4">
                <div class="row align-items-center justify-content-between">
                    <div class="col-auto mt-4">
                        <h1 class="page-header-title">
                            <div class="page-header-icon"><i data-feather="briefcase"></i></div>
                            Jabatan
                        </h1>
                        <div class="page-header-subtitle">Kelola data jabatan karyawan</div>
                    </div>
                    {{-- <div class="col-auto mt-4">
                        <button class="btn btn-white btn-sm" data-bs-toggle="modal" data-bs-target="#modalTambah">
                            <i data-feather="plus" class="me-1"></i> Tambah Jabatan
                        </button>
                    </div> --}}
                </div>
            </div>
        </div>
    </header>

    <div class="container-xl px-4 mt-n10">
        <div class="row">
            <div class="col-lg-4">
                <div class="card mb-4">
                    <div class="card-header">Tambah Jabatan</div>
                    <div class="card-body">

                        <form action="{{ route('admin.jabatan.store') }}" method="POST">
                            @csrf

                            <div class="mb-3">
                                <label class="form-label fw-bold">
                                    Nama Jabatan <span class="text-danger">*</span>
                                </label>

                                <input type="text" class="form-control" name="nama_jabatan"
                                    placeholder="Masukkan nama jabatan" required>
                            </div>

                            <div class="d-flex justify-content-end gap-2">
                                <button type="reset" class="btn btn-light">
                                    Reset
                                </button>

                                <button type="submit" class="btn btn-primary">
                                    Simpan
                                </button>
                            </div>

                        </form>

                    </div>
                </div>
            </div>
            <div class="col-lg-8">
                <div class="card mb-4">
                    <div class="card-header">Daftar Jabatan</div>
                    <div class="card-body">
                        <table id="datatablesSimple">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Nama Jabatan</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            {{-- <tfoot>
                                <tr>
                                    <th>No.</th>
                                    <th>Nama Jabatan</th>
                                    <th>Aksi</th>
                                </tr>
                            </tfoot> --}}
                            <tbody>
                                @foreach($jabatan as $index => $j)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td class="fw-bold text-capitalize">{{ $j->nama_jabatan }}</td>
                                    <td>
                                        <!-- Edit -->
                                        <button class="btn btn-datatable btn-icon btn-success me-2"
                                            data-bs-toggle="modal" data-bs-target="#modalEdit" data-id="{{ $j->id}}"
                                            data-nama="{{ $j->nama_jabatan }}" title="Edit">
                                            <i data-feather="edit"></i>
                                        </button>
                                        <!-- Hapus -->
                                        <button class="btn btn-datatable btn-icon btn-danger"
                                            onclick="confirmDelete({{ $j->id }})" title="Hapus">
                                            <i data-feather="trash-2"></i>
                                        </button>

                                        <!-- Form hidden untuk delete -->
                                        <form id="delete-form-{{ $j->id }}"
                                            action="{{ route('admin.jabatan.destroy', $j->id) }}" method="POST"
                                            style="display: none;">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</main>

{{-- Modal Tambah --}}
{{-- <div class="modal fade" id="modalTambah" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Data Jabatan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="formTambah" action="{{ route('admin.jabatan.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-bold">Nama Jabatan <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="nama_jabatan" placeholder="Masukkan nama jabatan"
                            required>
                    </div>
                    <div class="d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div> --}}

{{-- Modal Edit --}}
<div class="modal fade" id="modalEdit" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Data Jabatan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="formEdit" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="id_jabatan" id="edit_id_jabatan">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Nama Jabatan <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="nama_jabatan" id="edit_nama_jabatan" required>
                    </div>
                    <div class="d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    // Modal Edit - populate data
    document.getElementById('modalEdit').addEventListener('show.bs.modal', function(event) {
        const button = event.relatedTarget;
        const id = button.getAttribute('data-id');
        const nama = button.getAttribute('data-nama');

        document.getElementById('edit_id_jabatan').value = id;
        document.getElementById('edit_nama_jabatan').value = nama;
        document.getElementById('formEdit').action = "{{ route('admin.jabatan.update', ':id') }}".replace(':id', id);
    });

    // Confirm Delete dengan SweetAlert
    function confirmDelete(id) {
        Swal.fire({
            title: 'Hapus Jabatan?',
            text: 'Data yang dihapus tidak dapat dikembalikan.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form-' + id).submit();
            }
        });
    }
</script>
@endpush