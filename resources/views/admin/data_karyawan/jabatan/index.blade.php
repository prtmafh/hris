@extends('admin.layouts.app')

@section('title', 'Jabatan')

@section('content')

<main>

    {{-- HEADER SB ADMIN PRO --}}
    <header class="page-header page-header-compact page-header-light border-bottom bg-white mb-4">
        <div class="container-xl px-4">
            <div class="page-header-content">
                <div class="row align-items-center justify-content-between pt-3">

                    <div class="col-auto mb-3">
                        <h1 class="page-header-title">
                            <div class="page-header-icon"><i data-feather="briefcase"></i></div>
                            Jabatan
                        </h1>
                    </div>

                </div>
            </div>
        </div>
    </header>

    <div class="container-xl px-4 mt-4">

        <div class="row">

            {{-- FORM --}}
            <div class="col-xl-4">
                <div class="card mb-4 mb-xl-0">

                    <div class="card-header">Tambah Jabatan</div>

                    <div class="card-body">

                        <form action="{{ route('admin.jabatan.store') }}" method="POST">
                            @csrf

                            <div class="mb-3">
                                <label class="small mb-1">Nama Jabatan</label>
                                <input type="text" class="form-control" name="nama_jabatan"
                                    placeholder="Masukkan nama jabatan" required>
                            </div>

                            <div class="d-flex justify-content-end gap-2">
                                <button type="reset" class="btn btn-light">Reset</button>
                                <button type="submit" class="btn btn-primary">Simpan</button>
                            </div>

                        </form>

                    </div>
                </div>
            </div>

            {{-- TABLE --}}
            <div class="col-xl-8">

                <div class="card mb-4">

                    <div class="card-header">Daftar Jabatan</div>

                    <div class="card-body">

                        <table id="datatablesSimple" class="table responsive">

                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Nama Jabatan</th>
                                    <th>Jumlah Karyawan</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>

                            <tbody>
                                @php
                                $badgeClasses = ['bg-primary', 'bg-success', 'bg-info', 'bg-warning', 'bg-danger',
                                'bg-secondary', 'bg-dark'];
                                @endphp
                                @foreach($jabatan as $index => $j)
                                <tr>

                                    <td>{{ $index + 1 }}</td>

                                    <td>
                                        @php $badgeClass = $badgeClasses[$index % count($badgeClasses)]; @endphp
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="badge {{ $badgeClass }} text-capitalize jabatan-badge"
                                                data-id="{{ $j->id }}">
                                                {{ $j->nama_jabatan }}
                                            </span>
                                            <input type="text" id="inline-input-{{ $j->id }}"
                                                class="form-control form-control-sm d-none jabatan-input"
                                                value="{{ $j->nama_jabatan }}" aria-label="Edit nama jabatan">
                                        </div>
                                    </td>

                                    <td>
                                        <span class="badge bg-secondary text-white">
                                            {{ $j->karyawan_count ?? 0 }} Karyawan
                                        </span>
                                    </td>

                                    <td>

                                        {{-- EDIT --}}
                                        <button class="btn btn-datatable btn-icon btn-transparent-dark me-2"
                                            data-bs-toggle="modal" data-bs-target="#modalEdit" data-id="{{ $j->id}}"
                                            data-nama="{{ $j->nama_jabatan }}">
                                            <i data-feather="edit"></i>
                                        </button>

                                        {{-- DELETE --}}
                                        <button class="btn btn-datatable btn-icon btn-transparent-dark text-danger"
                                            onclick="confirmDelete({{ $j->id }})">
                                            <i data-feather="trash-2"></i>
                                        </button>

                                        <form id="delete-form-{{ $j->id }}"
                                            action="{{ route('admin.jabatan.destroy', $j->id) }}" method="POST"
                                            style="display:none;">
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
<div class="modal fade" id="modalEdit" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Edit Jabatan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <form id="formEdit" method="POST">
                    @csrf
                    @method('PUT')

                    <input type="hidden" name="id_jabatan" id="edit_id_jabatan">

                    <div class="mb-3">
                        <label class="small mb-1">Nama Jabatan</label>
                        <input type="text" class="form-control" name="nama_jabatan" id="edit_nama_jabatan" required>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>

                </form>

            </div>

        </div>
    </div>
</div>

@endsection
@push('scripts')
<script>
    function updateHiddenInput(id) {
        const inlineInput = document.getElementById(`inline-input-${id}`);
        const hiddenInput = document.getElementById(`hidden-input-${id}`);
        if (inlineInput && hiddenInput) {
            hiddenInput.value = inlineInput.value;
        }
    }

    function enterInlineEdit(id) {
        const badge = document.querySelector(`.jabatan-badge[data-id="${id}"]`);
        const inlineInput = document.getElementById(`inline-input-${id}`);
        const editButton = document.querySelector(`.btn-edit-row[data-id="${id}"]`);
        const saveButton = document.querySelector(`.btn-save-row[data-id="${id}"]`);
        const cancelButton = document.querySelector(`.btn-cancel-row[data-id="${id}"]`);
        const hiddenInput = document.getElementById(`hidden-input-${id}`);

        if (!badge || !inlineInput || !editButton || !saveButton || !cancelButton || !hiddenInput) return;

        badge.classList.add('d-none');
        inlineInput.classList.remove('d-none');
        editButton.classList.add('d-none');
        saveButton.classList.remove('d-none');
        cancelButton.classList.remove('d-none');
        inlineInput.focus();
        hiddenInput.value = inlineInput.value;
    }

    function cancelInlineEdit(id) {
        const badge = document.querySelector(`.jabatan-badge[data-id="${id}"]`);
        const inlineInput = document.getElementById(`inline-input-${id}`);
        const editButton = document.querySelector(`.btn-edit-row[data-id="${id}"]`);
        const saveButton = document.querySelector(`.btn-save-row[data-id="${id}"]`);
        const cancelButton = document.querySelector(`.btn-cancel-row[data-id="${id}"]`);
        const hiddenInput = document.getElementById(`hidden-input-${id}`);

        if (!badge || !inlineInput || !editButton || !saveButton || !cancelButton || !hiddenInput) return;

        inlineInput.classList.add('d-none');
        badge.classList.remove('d-none');
        editButton.classList.remove('d-none');
        saveButton.classList.add('d-none');
        cancelButton.classList.add('d-none');
        inlineInput.value = badge.textContent.trim();
        hiddenInput.value = inlineInput.value;
    }

    document.querySelectorAll('.btn-edit-row').forEach((button) => {
        button.addEventListener('click', function () {
            enterInlineEdit(this.dataset.id);
        });
    });

    document.querySelectorAll('.btn-cancel-row').forEach((button) => {
        button.addEventListener('click', function () {
            cancelInlineEdit(this.dataset.id);
        });
    });

    document.querySelectorAll('.jabatan-input').forEach((input) => {
        input.addEventListener('input', function () {
            const id = this.id.replace('inline-input-', '');
            const hiddenInput = document.getElementById(`hidden-input-${id}`);
            if (hiddenInput) {
                hiddenInput.value = this.value;
            }
        });
    });

    document.getElementById('modalEdit').addEventListener('show.bs.modal', function(event) {
    const button = event.relatedTarget;
    const id = button.getAttribute('data-id');
    const nama = button.getAttribute('data-nama');
    
    document.getElementById('edit_id_jabatan').value = id;
    document.getElementById('edit_nama_jabatan').value = nama;
    document.getElementById('formEdit').action =
    "{{ route('admin.jabatan.update', ':id') }}".replace(':id', id);
    });

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
{{-- @push('scripts')
<script>
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
@endpush --}}