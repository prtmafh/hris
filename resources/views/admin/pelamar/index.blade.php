@extends('admin.layouts.app')

@section('title', 'Pelamar')

@section('content')
<main>

    <header class="page-header page-header-compact page-header-light border-bottom bg-white mb-4">
        <div class="container-xl px-4">
            <div class="page-header-content">
                <div class="row align-items-center justify-content-between pt-3">
                    <div class="col-auto mb-3">
                        <h1 class="page-header-title">
                            <div class="page-header-icon"><i data-feather="users"></i></div>
                            Pelamar
                        </h1>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div class="container-xl px-4">

        {{-- FILTER --}}
        <div class="card mb-4">
            <div class="card-header">Filter Pelamar</div>
            <div class="card-body">
                <form method="GET" action="{{ route('admin.pelamar') }}" class="row g-2 align-items-end">
                    <div class="col-md-5">
                        <label class="small mb-1">Lowongan</label>
                        <select name="lowongan_id" class="form-select form-select-sm">
                            <option value="">Semua lowongan</option>
                            @foreach($lowongan as $item)
                            <option value="{{ $item->id }}" {{ request('lowongan_id')==$item->id ? 'selected' : '' }}>
                                {{ $item->judul }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="small mb-1">Status</label>
                        <select name="status" class="form-select form-select-sm">
                            <option value="">Semua status</option>
                            @foreach(['pending','screening','interview','offering','diterima','ditolak'] as $status)
                            <option value="{{ $status }}" {{ request('status')===$status ? 'selected' : '' }}>
                                {{ ucfirst($status) }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 d-grid">
                        <button class="btn btn-primary btn-sm" type="submit">Filter</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- TABLE --}}
        <div class="card mb-4">
            <div class="card-header">Daftar Pelamar</div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="datatablesSimple" class="table table-hover align-middle">

                        <thead>
                            <tr>
                                <th width="50">No</th>
                                <th>Pelamar</th>
                                <th>Lowongan</th>
                                <th>Status</th>
                                <th width="170" class="text-center">Aksi</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($pelamar as $index => $item)

                            @php
                            $badgeColor = match($item->status) {
                            'pending' => 'secondary',
                            'screening' => 'yellow',
                            'interview' => 'blue',
                            'offering' => 'orange',
                            'diterima' => 'green',
                            'ditolak' => 'red',
                            default => 'secondary'
                            };
                            @endphp

                            <tr>

                                {{-- No --}}
                                <td class="fw-semibold text-muted">{{ $index + 1 }}</td>

                                {{-- Pelamar --}}
                                <td>
                                    <div class="fw-semibold">{{ $item->nama }}</div>
                                    <div class="small text-muted">{{ $item->email }}</div>
                                </td>

                                {{-- Lowongan --}}
                                <td>
                                    <div class="fw-semibold">{{ $item->lowongan->judul ?? '-' }}</div>
                                    <div class="small text-muted">
                                        {{ optional(optional($item->lowongan)->jabatan)->nama_jabatan ?? '-' }}
                                    </div>
                                </td>

                                {{-- Status --}}
                                <td>
                                    <span class="badge bg-{{ $badgeColor }}-soft text-{{ $badgeColor }}">
                                        {{ ucfirst($item->status) }}
                                    </span>
                                    @if($item->jadwal_interview)
                                    <div class="small text-muted mt-1">
                                        {{ $item->jadwal_interview->format('d M Y, H:i') }}
                                    </div>
                                    @endif
                                </td>

                                {{-- Aksi --}}
                                <td class="text-center">

                                    {{-- Detail --}}
                                    <button class="btn btn-datatable btn-icon btn-transparent-dark me-1"
                                        data-bs-toggle="modal" data-bs-target="#modalDetail{{ $item->id }}"
                                        title="Lihat Detail">
                                        <i data-feather="eye"></i>
                                    </button>

                                    {{-- Update Status --}}
                                    <button class="btn btn-datatable btn-icon btn-transparent-dark me-1"
                                        data-bs-toggle="modal" data-bs-target="#modalEdit{{ $item->id }}"
                                        title="Update Status">
                                        <i data-feather="edit"></i>
                                    </button>

                                    {{-- Kirim Panggilan --}}
                                    <button class="btn btn-datatable btn-icon btn-transparent-dark me-1"
                                        data-bs-toggle="modal" data-bs-target="#modalPanggilan{{ $item->id }}"
                                        title="Kirim Panggilan Interview">
                                        <i data-feather="mail"></i>
                                    </button>

                                    {{-- Kirim Update Proses --}}
                                    <button class="btn btn-datatable btn-icon btn-transparent-dark me-1"
                                        data-bs-toggle="modal" data-bs-target="#modalUpdateProses{{ $item->id }}"
                                        title="Kirim Update Proses">
                                        <i data-feather="send"></i>
                                    </button>

                                    {{-- Hapus --}}
                                    <button class="btn btn-datatable btn-icon btn-transparent-dark text-danger"
                                        onclick="confirmDeletePelamar({{ $item->id }})" title="Hapus">
                                        <i data-feather="trash-2"></i>
                                    </button>

                                    <form id="delete-pelamar-{{ $item->id }}"
                                        action="{{ route('admin.pelamar.destroy', $item->id) }}" method="POST"
                                        class="d-none">
                                        @csrf
                                        @method('DELETE')
                                    </form>

                                </td>
                            </tr>


                            {{-- MODAL DETAIL --}}
                            <div class="modal fade" id="modalDetail{{ $item->id }}" tabindex="-1">

                                <div class="modal-dialog modal-xl modal-dialog-centered">
                                    <div class="modal-content border-0 shadow-lg">

                                        {{-- HEADER --}}
                                        <div class="modal-header border-bottom bg-white">
                                            <h5 class="modal-title d-flex align-items-center">
                                                <i data-feather="user" class="me-2"></i>
                                                Candidate Profile
                                            </h5>

                                            <button type="button" class="btn-close" data-bs-dismiss="modal">
                                            </button>
                                        </div>



                                        <div class="modal-body p-4">

                                            <div class="row">

                                                {{-- LEFT SIDEBAR --}}
                                                <div class="col-lg-4">

                                                    <div class="card border mb-4">
                                                        <div class="card-body text-center">

                                                            <div class="avatar avatar-xl mb-3">
                                                                <img class="avatar-img rounded-circle"
                                                                    src="https://ui-avatars.com/api/?name={{ urlencode($item->nama) }}">
                                                            </div>

                                                            <div class="fw-bold fs-4">
                                                                {{ $item->nama }}
                                                            </div>

                                                            <div class="small text-muted mb-3">
                                                                {{ $item->email }}
                                                            </div>

                                                            <span
                                                                class="badge bg-{{ $badgeColor }}-soft text-{{ $badgeColor }}">
                                                                {{ ucfirst($item->status) }}
                                                            </span>

                                                        </div>
                                                    </div>



                                                    <div class="card border">
                                                        <div class="card-header">
                                                            Candidate Summary
                                                        </div>

                                                        <div class="card-body">

                                                            <div
                                                                class="d-flex justify-content-between border-bottom py-2">
                                                                <span class="small text-muted">
                                                                    Phone
                                                                </span>

                                                                <strong>
                                                                    {{ $item->no_hp ?: '-' }}
                                                                </strong>
                                                            </div>


                                                            <div
                                                                class="d-flex justify-content-between border-bottom py-2">
                                                                <span class="small text-muted">
                                                                    Birth Date
                                                                </span>

                                                                <strong>
                                                                    {{ optional($item->tanggal_lahir)->format('d M Y')
                                                                    ?: '-' }}
                                                                </strong>
                                                            </div>


                                                            <div
                                                                class="d-flex justify-content-between border-bottom py-2">
                                                                <span class="small text-muted">
                                                                    Applied Date
                                                                </span>

                                                                <strong>
                                                                    {{ optional($item->applied_at)->format('d M Y') ?:
                                                                    '-' }}
                                                                </strong>
                                                            </div>


                                                            <div class="d-flex justify-content-between py-2">
                                                                <span class="small text-muted">
                                                                    Interview
                                                                </span>

                                                                <strong>
                                                                    {{ optional($item->jadwal_interview)->format('d M Y
                                                                    H:i') ?: '-' }}
                                                                </strong>
                                                            </div>

                                                        </div>
                                                    </div>


                                                    @if($item->catatan_hr)
                                                    <div class="card border mt-4">
                                                        <div class="card-header">
                                                            HR Notes
                                                        </div>

                                                        <div class="card-body">
                                                            <div class="p-3 bg-light rounded">
                                                                {{ $item->catatan_hr }}
                                                            </div>
                                                        </div>

                                                    </div>
                                                    @endif

                                                </div>



                                                {{-- RIGHT CONTENT --}}
                                                <div class="col-lg-8">


                                                    <div class="card border mb-4">

                                                        <div class="card-header">
                                                            Application Information
                                                        </div>

                                                        <div class="card-body">

                                                            <div class="row g-4">

                                                                <div class="col-md-6">
                                                                    <label class="small text-muted d-block mb-1">
                                                                        Applied Position
                                                                    </label>

                                                                    <div class="fw-semibold">
                                                                        {{ $item->lowongan->judul ?? '-' }}
                                                                    </div>

                                                                    <div class="small text-muted">
                                                                        {{
                                                                        optional(optional($item->lowongan)->jabatan)->nama_jabatan
                                                                        ?? '-' }}
                                                                    </div>

                                                                </div>



                                                                <div class="col-md-6">
                                                                    <label class="small text-muted d-block mb-1">
                                                                        Recruitment Stage
                                                                    </label>

                                                                    <span
                                                                        class="badge bg-{{ $badgeColor }}-soft text-{{ $badgeColor }}">
                                                                        {{ ucfirst($item->status) }}
                                                                    </span>

                                                                </div>



                                                                <div class="col-md-6">
                                                                    <label class="small text-muted d-block mb-1">
                                                                        Interview Schedule
                                                                    </label>

                                                                    <div class="fw-semibold">
                                                                        {{ optional($item->jadwal_interview)->format('d
                                                                        M Y H:i') ?: '-' }}
                                                                    </div>

                                                                </div>


                                                                <div class="col-md-6">
                                                                    <label class="small text-muted d-block mb-1">
                                                                        Email
                                                                    </label>

                                                                    <div class="fw-semibold">
                                                                        {{ $item->email }}
                                                                    </div>

                                                                </div>



                                                                <div class="col-12">
                                                                    <label class="small text-muted d-block mb-1">
                                                                        Address
                                                                    </label>

                                                                    <div class="p-3 bg-light rounded">
                                                                        {{ $item->alamat ?: '-' }}
                                                                    </div>

                                                                </div>

                                                            </div>

                                                        </div>

                                                    </div>




                                                    <div class="card border">

                                                        <div class="card-header">
                                                            Application Documents
                                                        </div>

                                                        <div class="card-body">

                                                            <div class="d-flex gap-2 flex-wrap">

                                                                <a href="{{ asset('storage/'.$item->cv) }}"
                                                                    target="_blank" class="btn btn-light border">
                                                                    <i data-feather="file-text" class="me-1"></i>
                                                                    Lihat CV
                                                                </a>


                                                                @if($item->foto)
                                                                <a href="{{ asset('storage/'.$item->foto) }}"
                                                                    target="_blank" class="btn btn-light border">
                                                                    <i data-feather="image" class="me-1"></i>
                                                                    Lihat Foto
                                                                </a>
                                                                @endif

                                                            </div>

                                                        </div>
                                                    </div>


                                                </div>
                                            </div>

                                        </div>



                                        {{-- FOOTER --}}
                                        <div class="modal-footer bg-light">

                                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                                                Tutup
                                            </button>


                                            <button class="btn btn-primary" data-bs-toggle="modal"
                                                data-bs-target="#modalEdit{{ $item->id }}" data-bs-dismiss="modal">

                                                <i data-feather="edit" class="me-1"></i>
                                                Update Status

                                            </button>

                                        </div>

                                    </div>
                                </div>
                            </div>


                            {{-- MODAL EDIT STATUS --}}
                            <div class="modal fade" id="modalEdit{{ $item->id }}" tabindex="-1">

                                <div class="modal-dialog modal-lg modal-dialog-centered">
                                    <div class="modal-content border-0 shadow-lg">

                                        <div class="modal-header border-bottom bg-white">
                                            <h5 class="modal-title d-flex align-items-center">
                                                <i data-feather="edit-2" class="me-2"></i>
                                                Update Candidate Stage
                                            </h5>

                                            <button class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>


                                        <form action="{{ route('admin.pelamar.update',$item->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')

                                            <div class="modal-body p-0">

                                                <div class="row g-0">

                                                    <div class="col-lg-4 border-end bg-light">

                                                        <div class="p-4 text-center">

                                                            <div class="avatar avatar-xl mb-3">
                                                                <img class="avatar-img rounded-circle"
                                                                    src="https://ui-avatars.com/api/?name={{ urlencode($item->nama) }}">
                                                            </div>

                                                            <div class="fw-bold">
                                                                {{ $item->nama }}
                                                            </div>

                                                            <div class="small text-muted mb-3">
                                                                {{ $item->email }}
                                                            </div>

                                                        </div>

                                                        <hr class="my-0">

                                                        <div class="p-4">

                                                            <h6 class="mb-3">
                                                                Pipeline Status
                                                            </h6>

                                                            <div class="small text-muted mb-2">
                                                                Current Stage
                                                            </div>

                                                            <span class="badge bg-primary-soft text-primary">
                                                                {{ ucfirst($item->status) }}
                                                            </span>

                                                        </div>

                                                    </div>



                                                    <div class="col-lg-8">

                                                        <div class="p-4">

                                                            <div class="mb-4">
                                                                <label class="small mb-1">
                                                                    Status Pipeline
                                                                </label>

                                                                <select name="status" class="form-select" required>
                                                                    @foreach(['pending','screening','interview','offering','diterima','ditolak']
                                                                    as $statusOpt)
                                                                    <option value="{{ $statusOpt }}" {{ $item->
                                                                        status===$statusOpt?'selected':'' }}>
                                                                        {{ ucfirst($statusOpt) }}
                                                                    </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>



                                                            <div class="mb-4">
                                                                <label class="small mb-1">
                                                                    Interview Schedule
                                                                </label>

                                                                <input type="datetime-local" name="jadwal_interview"
                                                                    class="form-control"
                                                                    value="{{ $item->jadwal_interview ? $item->jadwal_interview->format('Y-m-d\\TH:i'):'' }}">
                                                            </div>



                                                            <div>
                                                                <label class="small mb-1">
                                                                    HR Notes
                                                                </label>

                                                                <textarea name="catatan_hr" rows="5"
                                                                    class="form-control"
                                                                    placeholder="Catatan proses rekrutmen">{{ $item->catatan_hr }}</textarea>
                                                            </div>

                                                        </div>

                                                    </div>

                                                </div>

                                            </div>


                                            <div class="modal-footer bg-light">
                                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                                                    Batal
                                                </button>

                                                <button class="btn btn-primary">
                                                    Simpan Perubahan
                                                </button>
                                            </div>

                                        </form>

                                    </div>
                                </div>
                            </div>


                            {{-- MODAL KIRIM PANGGILAN --}}
                            <div class="modal fade" id="modalPanggilan{{ $item->id }}" tabindex="-1">

                                <div class="modal-dialog modal-lg modal-dialog-centered">
                                    <div class="modal-content border-0 shadow-lg">

                                        <div class="modal-header border-bottom bg-white">
                                            <h5 class="modal-title d-flex align-items-center">
                                                <i data-feather="mail" class="me-2"></i>
                                                Interview Invitation
                                            </h5>

                                            <button class="btn-close" data-bs-dismiss="modal">
                                            </button>
                                        </div>


                                        <form action="{{ route('admin.pelamar.kirim-panggilan',$item->id) }}"
                                            method="POST">
                                            @csrf

                                            <div class="modal-body p-4">

                                                <div class="alert alert-primary-soft mb-4">
                                                    Email akan dikirim ke
                                                    <strong>{{ $item->email }}</strong><br>
                                                    Status otomatis menjadi <strong>Interview</strong>
                                                </div>



                                                <div class="mb-4">
                                                    <label class="small mb-1">
                                                        Jadwal Interview
                                                    </label>

                                                    <input type="datetime-local" name="jadwal_interview" required
                                                        class="form-control"
                                                        value="{{ $item->jadwal_interview ? $item->jadwal_interview->format('Y-m-d\\TH:i'):'' }}">
                                                </div>



                                                <div>
                                                    <label class="small mb-1">
                                                        Isi Pesan
                                                    </label>

                                                    <textarea name="pesan" rows="8" required
                                                        class="form-control">Mohon hadir tepat waktu dan membawa dokumen pendukung yang diperlukan. Jika ada kendala, silakan menghubungi pihak HR.</textarea>
                                                </div>

                                            </div>


                                            <div class="modal-footer bg-light">

                                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                                                    Batal
                                                </button>

                                                <button class="btn btn-primary">
                                                    <i data-feather="send" class="me-1"></i>
                                                    Kirim Email
                                                </button>

                                            </div>

                                        </form>

                                    </div>
                                </div>
                            </div>


                            {{-- MODAL UPDATE PROSES --}}
                            <div class="modal fade" id="modalUpdateProses{{ $item->id }}" tabindex="-1">

                                <div class="modal-dialog modal-lg modal-dialog-centered">
                                    <div class="modal-content border-0 shadow-lg">

                                        <div class="modal-header border-bottom bg-white">
                                            <h5 class="modal-title d-flex align-items-center">
                                                <i data-feather="send" class="me-2"></i>
                                                Process Update Notification
                                            </h5>

                                            <button class="btn-close" data-bs-dismiss="modal">
                                            </button>
                                        </div>


                                        <form action="{{ route('admin.pelamar.kirim-update-proses',$item->id) }}"
                                            method="POST">
                                            @csrf


                                            <div class="modal-body p-4">

                                                <div class="alert alert-success-soft mb-4">
                                                    Update proses akan dikirim ke
                                                    <strong>{{ $item->email }}</strong>
                                                </div>



                                                <div class="mb-4">
                                                    <label class="small mb-1">
                                                        Status Proses
                                                    </label>

                                                    <select name="status" class="form-select" required>

                                                        @foreach(['pending','screening','interview','offering','diterima','ditolak']
                                                        as $statusOpt)
                                                        <option value="{{ $statusOpt }}" {{ $item->
                                                            status===$statusOpt?'selected':'' }}>
                                                            {{ ucfirst($statusOpt) }}
                                                        </option>
                                                        @endforeach

                                                    </select>

                                                </div>




                                                <div class="mb-4">

                                                    <label class="small mb-1">
                                                        Jadwal Interview
                                                    </label>

                                                    <input type="datetime-local" name="jadwal_interview"
                                                        class="form-control"
                                                        value="{{ $item->jadwal_interview ? $item->jadwal_interview->format('Y-m-d\\TH:i'):'' }}">

                                                    <div class="small text-muted mt-2">
                                                        Isi jika berkaitan dengan jadwal interview
                                                    </div>

                                                </div>




                                                <div>
                                                    <label class="small mb-1">
                                                        Isi Pesan
                                                    </label>

                                                    <textarea name="pesan" rows="8" required
                                                        class="form-control">Terima kasih telah mengikuti proses rekrutmen PT. Tidarjaya Solidindo. Berikut kami sampaikan pembaruan status lamaran Anda.</textarea>
                                                </div>

                                            </div>



                                            <div class="modal-footer bg-light">

                                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                                                    Batal
                                                </button>

                                                <button class="btn btn-success">
                                                    <i data-feather="send" class="me-1"></i>
                                                    Kirim Update
                                                </button>

                                            </div>

                                        </form>

                                    </div>
                                </div>
                            </div>

                            @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-5">
                                    Belum ada data pelamar.
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
@endsection

@push('scripts')
<script>
    function confirmDeletePelamar(id) {
        Swal.fire({
            title: 'Hapus Pelamar?',
            text: 'Data dan dokumen pelamar akan dihapus permanen.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-pelamar-' + id).submit();
            }
        });
    }
</script>
@endpush