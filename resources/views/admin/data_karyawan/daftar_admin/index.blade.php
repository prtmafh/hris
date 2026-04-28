@extends('admin.layouts.app')

@section('title', 'Daftar Admin')

@section('content')
<main>

    {{-- HEADER --}}
    <header class="page-header page-header-compact page-header-light border-bottom bg-white mb-4">
        <div class="container-fluid px-4">
            <div class="page-header-content">
                <div class="row align-items-center justify-content-between pt-3">

                    <div class="col-auto mb-3">
                        <h1 class="page-header-title">
                            <div class="page-header-icon">
                                <i data-feather="shield"></i>
                            </div>
                            Daftar Admin
                        </h1>
                    </div>

                    <div class="col-12 col-xl-auto mb-3">
                        <button class="btn btn-sm btn-light text-primary" data-bs-toggle="modal"
                            data-bs-target="#modalTambahAdmin">
                            <i class="me-1" data-feather="user-plus"></i>
                            Tambah Admin
                        </button>
                    </div>

                </div>
            </div>
        </div>
    </header>

    {{-- CONTENT --}}
    <div class="container-fluid px-4">
        <div class="card">
            <div class="card-header">
                Daftar Admin
            </div>
            <div class="card-body">

                <table id="datatablesSimple">

                    <thead>
                        <tr>
                            <th>User</th>
                            <th>NIK</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>Joined Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    {{-- <tfoot>
                        <tr>
                            <th>User</th>
                            <th>NIK</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>Joined Date</th>
                            <th>Actions</th>
                        </tr>
                    </tfoot> --}}

                    <tbody>
                        @forelse($admins as $admin)
                        <tr>

                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar me-2">
                                        <img class="avatar-img img-fluid"
                                            src="https://ui-avatars.com/api/?name={{ urlencode($admin->nik) }}">
                                    </div>
                                    {{ $admin->nik }}
                                </div>
                            </td>

                            <td>{{ $admin->nik }}</td>

                            <td>
                                <span class="badge bg-purple-soft text-purple">Admin</span>
                            </td>

                            <td>
                                @if($admin->status === 'aktif')
                                <span class="badge bg-green-soft text-green">Aktif</span>
                                @else
                                <span class="badge bg-red-soft text-red">Nonaktif</span>
                                @endif
                            </td>

                            <td>{{ $admin->created_at->format('d M Y') }}</td>

                            <td>

                                {{-- EDIT --}}
                                <button class="btn btn-datatable btn-icon btn-transparent-dark me-2"
                                    onclick="openEditAdmin({{ $admin->id }}, '{{ $admin->nik }}')">
                                    <i data-feather="edit"></i>
                                </button>

                                {{-- TOGGLE --}}
                                @if($admin->id !== auth()->id())
                                <form action="{{ route('admin.daftar_admin.toggleStatus', $admin->id) }}" method="POST"
                                    class="d-inline toggle-form-{{ $admin->id }}">
                                    @csrf
                                    <button type="button" class="btn btn-datatable btn-icon btn-transparent-dark me-2"
                                        onclick="confirmToggleAdmin({{ $admin->id }}, '{{ $admin->nik }}', '{{ $admin->status === 'aktif' ? 'nonaktifkan' : 'aktifkan' }}')">
                                        <i data-feather="power"></i>
                                    </button>
                                </form>
                                @endif

                                {{-- DELETE --}}
                                @if($admin->id !== auth()->id())
                                <button class="btn btn-datatable btn-icon btn-transparent-dark text-danger"
                                    onclick="confirmDeleteAdmin({{ $admin->id }}, '{{ $admin->nik }}')">
                                    <i data-feather="trash-2"></i>
                                </button>

                                <form id="delete-admin-{{ $admin->id }}"
                                    action="{{ route('admin.daftar_admin.destroy', $admin->id) }}" method="POST"
                                    style="display:none;">
                                    @csrf
                                    @method('DELETE')
                                </form>
                                @endif

                            </td>

                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
                                Tidak ada data admin
                            </td>
                        </tr>
                        @endforelse
                    </tbody>

                </table>

            </div>
        </div>
    </div>

</main>

{{-- ================= MODAL TAMBAH ================= --}}
<div class="modal fade" id="modalTambahAdmin" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('admin.daftar_admin.store') }}" method="POST">
            @csrf
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title"><i data-feather="shield"></i> Tambah Admin</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <div class="mb-3">
                        <label class="form-label">NIK</label>
                        <input type="text" name="nik" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>

                    <div class="mb-0">
                        <label class="form-label">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" class="form-control" required>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>

            </div>
        </form>
    </div>
</div>

{{-- ================= MODAL EDIT ================= --}}
<div class="modal fade" id="modalEditAdmin" tabindex="-1">
    <div class="modal-dialog">
        <form id="formEditAdmin" method="POST">
            @csrf
            @method('PUT')
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title"><i data-feather="edit"></i> Edit Admin</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <div class="mb-3">
                        <label class="form-label">NIK</label>
                        <input type="text" id="edit_nik" name="nik" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Password Baru</label>
                        <input type="password" name="password" class="form-control">
                    </div>

                    <div class="mb-0">
                        <label class="form-label">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" class="form-control">
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>

            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
    function confirmToggleAdmin(id, nik, aksi) {
    Swal.fire({
        title: 'Konfirmasi',
        text: `Yakin ingin ${aksi} admin "${nik}"?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: aksi === 'nonaktifkan' ? '#dc3545' : '#198754',
        confirmButtonText: 'Ya',
    }).then((result) => {
        if (result.isConfirmed) {
            document.querySelector('.toggle-form-' + id).submit();
        }
    });
}

function confirmDeleteAdmin(id, nik) {
    Swal.fire({
        title: 'Hapus Admin?',
        text: `Admin "${nik}" akan dihapus.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        confirmButtonText: 'Hapus',
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('delete-admin-' + id).submit();
        }
    });
}

function openEditAdmin(id, nik) {
    document.getElementById('edit_nik').value = nik;
    document.getElementById('formEditAdmin').action = `/admin/daftar_admin/${id}`;
    new bootstrap.Modal(document.getElementById('modalEditAdmin')).show();
}

</script>
@endpush