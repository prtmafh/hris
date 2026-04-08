@extends('admin.layouts.app')

@section('title', 'Daftar Admin')

@section('content')
<main>
    <header class="page-header page-header-dark bg-gradient-primary-to-secondary pb-10">
        <div class="container-xl px-4">
            <div class="page-header-content pt-4 d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="page-header-title">
                        <div class="page-header-icon"><i data-feather="shield"></i></div>
                        Daftar Admin
                    </h1>
                    <div class="page-header-subtitle">Manajemen akun administrator</div>
                </div>
                <button class="btn btn-white btn-sm" data-bs-toggle="modal" data-bs-target="#modalTambahAdmin">
                    <i data-feather="plus"></i> Tambah Admin
                </button>
            </div>
        </div>
    </header>

    <div class="container-xl px-4 mt-n10">
        <div class="card mb-4">
            <div class="card-header">Data Admin</div>
            <div class="card-body">
                <table id="datatablesSimple" class="table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>NIK</th>
                            <th>Status Akun</th>
                            <th>Dibuat</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($admins as $index => $admin)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($admin->nik) }}&size=36&background=4e73df&color=fff"
                                        width="36" height="36" class="rounded-circle">
                                    <div>
                                        <div class="fw-bold">{{ $admin->nik }}</div>
                                        @if($admin->id === auth()->id())
                                        <div class="text-muted" style="font-size:.75rem;">Anda</div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>
                                @if($admin->id === auth()->id())
                                    <span class="badge bg-success">Aktif</span>
                                @else
                                    <form action="{{ route('admin.daftar_admin.toggleStatus', $admin->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @if($admin->status === 'aktif')
                                        <button type="button" class="badge bg-success border-0"
                                            style="cursor:pointer;"
                                            onclick="confirmToggleAdmin({{ $admin->id }}, '{{ $admin->nik }}', 'nonaktifkan')">
                                            Aktif
                                        </button>
                                        @else
                                        <button type="button" class="badge bg-danger border-0"
                                            style="cursor:pointer;"
                                            onclick="confirmToggleAdmin({{ $admin->id }}, '{{ $admin->nik }}', 'aktifkan')">
                                            Nonaktif
                                        </button>
                                        @endif
                                    </form>
                                @endif
                            </td>
                            <td>{{ $admin->created_at->format('d/m/Y') }}</td>
                            <td>
                                <button class="btn btn-sm btn-warning"
                                    onclick="openEditAdmin({{ $admin->id }}, '{{ $admin->nik }}')">
                                    <i data-feather="edit"></i>
                                </button>

                                @if($admin->id !== auth()->id())
                                <button class="btn btn-sm btn-danger ms-1" onclick="confirmDeleteAdmin({{ $admin->id }}, '{{ $admin->nik }}')">
                                    <i data-feather="trash-2"></i>
                                </button>
                                <form id="delete-admin-{{ $admin->id }}"
                                    action="{{ route('admin.daftar_admin.destroy', $admin->id) }}" method="POST"
                                    style="display:none;">
                                    @csrf
                                    @method('DELETE')
                                </form>
                                @else
                                <span class="text-muted small ms-1">—</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">Tidak ada data admin.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>

{{-- MODAL TAMBAH ADMIN --}}
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
                        <label class="form-label">NIK <span class="text-danger">*</span></label>
                        <input type="text" name="nik" class="form-control @error('nik') is-invalid @enderror"
                            value="{{ old('nik') }}" placeholder="Masukkan NIK admin">
                        @error('nik')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Password <span class="text-danger">*</span></label>
                        <input type="password" name="password"
                            class="form-control @error('password') is-invalid @enderror"
                            placeholder="Minimal 6 karakter">
                        @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-0">
                        <label class="form-label">Konfirmasi Password <span class="text-danger">*</span></label>
                        <input type="password" name="password_confirmation" class="form-control"
                            placeholder="Ulangi password">
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- MODAL EDIT ADMIN --}}
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
                        <label class="form-label">NIK <span class="text-danger">*</span></label>
                        <input type="text" id="edit_nik" name="nik"
                            class="form-control @error('nik') is-invalid @enderror"
                            placeholder="Masukkan NIK admin">
                        @error('nik')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Password Baru</label>
                        <input type="password" name="password"
                            class="form-control @error('password') is-invalid @enderror"
                            placeholder="Kosongkan jika tidak ingin mengubah">
                        @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-0">
                        <label class="form-label">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" class="form-control"
                            placeholder="Ulangi password baru">
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
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
            text: `Yakin ingin ${aksi} akun admin "${nik}"?`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: aksi === 'nonaktifkan' ? '#dc3545' : '#198754',
            cancelButtonColor: '#6c757d',
            confirmButtonText: `Ya, ${aksi}`,
            cancelButtonText: 'Batal',
        }).then((result) => {
            if (result.isConfirmed) {
                document.querySelector(`form[action*="toggle-status"][action*="${id}"]`).submit();
            }
        });
    }

    function confirmDeleteAdmin(id, nik) {
        Swal.fire({
            title: 'Hapus Admin?',
            text: `Akun admin "${nik}" akan dihapus permanen.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Hapus',
            cancelButtonText: 'Batal',
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

    @if($errors->has('nik') || $errors->has('password'))
        @if(session('edit_id'))
            openEditAdmin({{ session('edit_id') }}, '{{ old('nik') }}');
        @else
            const modalTambah = new bootstrap.Modal(document.getElementById('modalTambahAdmin'));
            modalTambah.show();
        @endif
    @endif
</script>
@endpush
