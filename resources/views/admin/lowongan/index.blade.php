@extends('admin.layouts.app')

@section('title','Lowongan')

@section('content')
<main>

    <header class="page-header page-header-compact page-header-light border-bottom bg-white mb-4">
        <div class="container-xl px-4">
            <div class="page-header-content">
                <div class="row align-items-center justify-content-between pt-3">

                    <div class="col-auto mb-3">
                        <h1 class="page-header-title">
                            <div class="page-header-icon">
                                <i data-feather="briefcase"></i>
                            </div>
                            Recruitment Openings
                        </h1>
                    </div>

                    <div class="col-auto mb-3">
                        <button class="btn btn-sm btn-light text-primary" data-bs-toggle="modal"
                            data-bs-target="#modalTambah">
                            <i data-feather="plus"></i>
                            Tambah Lowongan
                        </button>
                    </div>

                </div>
            </div>
        </div>
    </header>


    <div class="container-xl px-4">

        <div class="card">
            <div class="card-header">
                Daftar Lowongan Rekrutmen
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table id="datatablesSimple" class="table table-hover align-middle">

                        <thead>
                            <tr>
                                <th width="50">No</th>
                                <th>Posisi / Jabatan</th>
                                <th>Kuota</th>
                                <th>Pelamar</th>
                                <th>Status</th>
                                <th width="160" class="text-center">Aksi</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($lowongan as $index => $item)

                            @php
                            $badge = match($item->status) {
                            'draft' => 'secondary',
                            'aktif' => 'green',
                            'ditutup' => 'red',
                            default => 'secondary'
                            };
                            @endphp

                            <tr>

                                {{-- No --}}
                                <td class="fw-semibold text-muted">{{ $index + 1 }}</td>

                                {{-- Posisi --}}
                                <td>
                                    <div class="fw-semibold">{{ $item->judul }}</div>
                                    <div class="small text-muted">{{ $item->jabatan->nama_jabatan ?? '-' }}</div>
                                </td>

                                {{-- Kuota --}}
                                <td>
                                    <div class="fw-semibold">{{ $item->kuota }} orang</div>
                                </td>

                                {{-- Pelamar --}}
                                <td>
                                    <span class="badge bg-blue-soft text-blue">
                                        {{ $item->pelamar_count }} pelamar
                                    </span>
                                </td>

                                {{-- Status --}}
                                <td>
                                    <span class="badge bg-{{ $badge }}-soft text-{{ $badge }}">
                                        {{ ucfirst($item->status) }}
                                    </span>
                                </td>

                                {{-- Aksi --}}
                                <td class="text-center">

                                    {{-- Detail --}}
                                    <button class="btn btn-datatable btn-icon btn-transparent-dark me-1"
                                        data-bs-toggle="modal" data-bs-target="#modalDetail{{ $item->id }}"
                                        title="Lihat Detail">
                                        <i data-feather="eye"></i>
                                    </button>

                                    {{-- Toggle Aktif/Tutup --}}
                                    <form action="{{ route('admin.lowongan.toggle', $item->id) }}" method="POST"
                                        class="d-inline">
                                        @csrf
                                        <button type="submit"
                                            class="btn btn-datatable btn-icon btn-transparent-dark me-1"
                                            title="{{ $item->status === 'aktif' ? 'Tutup Lowongan' : 'Aktifkan Lowongan' }}">
                                            <i
                                                data-feather="{{ $item->status === 'aktif' ? 'pause-circle' : 'play-circle' }}"></i>
                                        </button>
                                    </form>

                                    {{-- Edit --}}
                                    <button class="btn btn-datatable btn-icon btn-transparent-dark me-1"
                                        data-bs-toggle="modal" data-bs-target="#modalEdit{{ $item->id }}" title="Edit">
                                        <i data-feather="edit"></i>
                                    </button>

                                    {{-- Hapus --}}
                                    <button class="btn btn-datatable btn-icon btn-transparent-dark text-danger"
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


                            {{-- MODAL DETAIL --}}
                            <div class="modal fade" id="modalDetail{{ $item->id }}" tabindex="-1">

                                <div class="modal-dialog modal-xl modal-dialog-centered">
                                    <div class="modal-content border-0 shadow-lg">


                                        {{-- HEADER --}}
                                        <div class="modal-header border-bottom bg-white">
                                            <h5 class="modal-title d-flex align-items-center">
                                                <i data-feather="briefcase" class="me-2"></i>
                                                Detail Lowongan
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
                                                                <div
                                                                    class="avatar-img rounded-circle bg-primary-soft d-flex align-items-center justify-content-center">
                                                                    <i data-feather="briefcase"></i>
                                                                </div>
                                                            </div>

                                                            <div class="fw-bold fs-5">
                                                                {{ $item->judul }}
                                                            </div>

                                                            <div class="small text-muted mb-3">
                                                                {{ $item->jabatan->nama_jabatan ?? '-' }}
                                                            </div>


                                                            <span class="badge bg-{{ $badge }}-soft text-{{ $badge }}">
                                                                {{ ucfirst($item->status) }}
                                                            </span>

                                                        </div>
                                                    </div>



                                                    <div class="card border">
                                                        <div class="card-header">
                                                            Recruitment Summary
                                                        </div>

                                                        <div class="card-body">


                                                            <div
                                                                class="d-flex justify-content-between border-bottom py-2">
                                                                <span class="small text-muted">
                                                                    Hiring Capacity
                                                                </span>

                                                                <strong>
                                                                    {{ $item->kuota }} Orang
                                                                </strong>
                                                            </div>


                                                            <div
                                                                class="d-flex justify-content-between border-bottom py-2">
                                                                <span class="small text-muted">
                                                                    Total Pelamar
                                                                </span>

                                                                <strong>
                                                                    {{ $item->pelamar_count }}
                                                                </strong>
                                                            </div>


                                                            <div class="d-flex justify-content-between py-2">
                                                                <span class="small text-muted">
                                                                    Status
                                                                </span>

                                                                <strong>
                                                                    {{ ucfirst($item->status) }}
                                                                </strong>
                                                            </div>


                                                        </div>
                                                    </div>

                                                </div>




                                                {{-- RIGHT CONTENT --}}
                                                <div class="col-lg-8">

                                                    <div class="card border mb-4">

                                                        <div class="card-header">
                                                            Informasi Lowongan
                                                        </div>

                                                        <div class="card-body">

                                                            <div class="row g-4">

                                                                <div class="col-md-6">
                                                                    <label class="small text-muted d-block mb-1">
                                                                        Posisi
                                                                    </label>

                                                                    <div class="fw-semibold">
                                                                        {{ $item->judul }}
                                                                    </div>
                                                                </div>



                                                                <div class="col-md-6">
                                                                    <label class="small text-muted d-block mb-1">
                                                                        Jabatan
                                                                    </label>

                                                                    <div class="fw-semibold">
                                                                        {{ $item->jabatan->nama_jabatan ?? '-' }}
                                                                    </div>
                                                                </div>



                                                                <div class="col-md-6">
                                                                    <label class="small text-muted d-block mb-1">
                                                                        Tanggal Pembukaan
                                                                    </label>

                                                                    <div class="fw-semibold">
                                                                        {{ optional($item->tanggal_buka)->format('d M
                                                                        Y') ?? '-' }}
                                                                    </div>
                                                                </div>



                                                                <div class="col-md-6">
                                                                    <label class="small text-muted d-block mb-1">
                                                                        Deadline Rekrutmen
                                                                    </label>

                                                                    <div class="fw-semibold">
                                                                        {{ optional($item->tanggal_tutup)->format('d M
                                                                        Y') ?? '-' }}
                                                                    </div>
                                                                </div>



                                                                <div class="col-md-6">
                                                                    <label class="small text-muted d-block mb-1">
                                                                        Kuota Posisi
                                                                    </label>

                                                                    <div class="fw-semibold">
                                                                        {{ $item->kuota }} orang
                                                                    </div>
                                                                </div>



                                                                <div class="col-md-6">
                                                                    <label class="small text-muted d-block mb-1">
                                                                        Applicant Pipeline
                                                                    </label>

                                                                    <div class="fw-semibold">
                                                                        {{ $item->pelamar_count }} pelamar
                                                                    </div>
                                                                </div>

                                                            </div>

                                                        </div>

                                                    </div>




                                                    <div class="card border">

                                                        <div class="card-header">
                                                            Job Description
                                                        </div>

                                                        <div class="card-body">

                                                            <div class="p-4 bg-light rounded">
                                                                {!! $item->deskripsi ?: '<span class="text-muted">Tidak
                                                                    ada deskripsi
                                                                    lowongan</span>' !!}
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
                                                Edit Lowongan

                                            </button>

                                        </div>

                                    </div>
                                </div>
                            </div>

                            {{-- MODAL EDIT --}}
                            <div class="modal fade" id="modalEdit{{ $item->id }}" tabindex="-1">

                                <div class="modal-dialog modal-xl modal-dialog-centered">
                                    <div class="modal-content border-0 shadow-lg">

                                        {{-- HEADER --}}
                                        <div class="modal-header border-bottom bg-white">
                                            <h5 class="modal-title d-flex align-items-center">
                                                <i data-feather="edit-2" class="me-2"></i>
                                                Edit Lowongan Rekrutmen
                                            </h5>

                                            <button class="btn-close" data-bs-dismiss="modal">
                                            </button>
                                        </div>


                                        <form action="{{ route('admin.lowongan.update',$item->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')


                                            <div class="modal-body p-0">

                                                <div class="row g-0">

                                                    {{-- SIDEBAR LEFT --}}
                                                    <div class="col-lg-4 border-end bg-light">

                                                        <div class="p-4 text-center">

                                                            <div class="avatar avatar-xl mb-3">
                                                                <div
                                                                    class="avatar-img rounded-circle bg-primary-soft d-flex align-items-center justify-content-center">
                                                                    <i data-feather="briefcase"></i>
                                                                </div>
                                                            </div>

                                                            <div class="fw-bold fs-5">
                                                                {{ $item->judul }}
                                                            </div>

                                                            <div class="small text-muted mb-3">
                                                                {{ $item->jabatan->nama_jabatan ?? '-' }}
                                                            </div>

                                                            <span class="badge bg-primary-soft text-primary">
                                                                Recruitment Position
                                                            </span>

                                                        </div>


                                                        <hr class="my-0">


                                                        <div class="p-4">

                                                            <h6 class="mb-3">
                                                                Position Summary
                                                            </h6>

                                                            <div
                                                                class="d-flex justify-content-between border-bottom py-2">
                                                                <span class="small text-muted">
                                                                    Kuota
                                                                </span>

                                                                <strong>
                                                                    {{ $item->kuota }} Orang
                                                                </strong>
                                                            </div>


                                                            <div
                                                                class="d-flex justify-content-between border-bottom py-2">
                                                                <span class="small text-muted">
                                                                    Pelamar
                                                                </span>

                                                                <strong>
                                                                    {{ $item->pelamar_count }}
                                                                </strong>
                                                            </div>


                                                            <div class="d-flex justify-content-between py-2">
                                                                <span class="small text-muted">
                                                                    Status
                                                                </span>

                                                                <strong class="text-capitalize">
                                                                    {{ $item->status }}
                                                                </strong>
                                                            </div>

                                                        </div>

                                                    </div>




                                                    {{-- FORM RIGHT --}}
                                                    <div class="col-lg-8">

                                                        <div class="p-4">

                                                            <div class="mb-4">
                                                                <h6 class="mb-1">
                                                                    Job Vacancy Information
                                                                </h6>

                                                                <p class="small text-muted mb-0">
                                                                    Perbarui informasi lowongan rekrutmen
                                                                </p>
                                                            </div>


                                                            <div class="card border shadow-none">
                                                                <div class="card-body">

                                                                    @include('admin.lowongan.partials.form',[
                                                                    'jabatan'=>$jabatan,
                                                                    'lowongan'=>$item,
                                                                    'submitLabel'=>'Update'
                                                                    ])

                                                                </div>
                                                            </div>


                                                        </div>

                                                    </div>

                                                </div>

                                            </div>



                                            {{-- FOOTER --}}
                                            <div class="modal-footer bg-light border-top">

                                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                                                    Batal
                                                </button>

                                                <button class="btn btn-primary">
                                                    <i data-feather="save" class="me-1"></i>
                                                    Simpan Perubahan
                                                </button>

                                            </div>

                                        </form>

                                    </div>
                                </div>
                            </div>

                            @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-5">
                                    Belum ada data lowongan
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




{{-- MODAL TAMBAH --}}
<div class="modal fade" id="modalTambah">
    <div class="modal-dialog modal-xl modal-dialog-centered">

        <div class="modal-content border-0 shadow-lg">

            <div class="modal-header border-bottom">
                <h5 class="modal-title d-flex align-items-center">
                    <i data-feather="plus-circle" class="me-2"></i>
                    Tambah Lowongan
                </h5>

                <button class="btn-close" data-bs-dismiss="modal">
                </button>
            </div>


            <form action="{{ route('admin.lowongan.store') }}" method="POST">
                @csrf

                <div class="modal-body p-4">

                    <div class="card border">
                        <div class="card-body">

                            @include('admin.lowongan.partials.form',[
                            'jabatan'=>$jabatan,
                            'lowongan'=>null,
                            'submitLabel'=>'Simpan'
                            ])

                        </div>
                    </div>

                </div>


                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                        Batal
                    </button>

                    <button class="btn btn-primary">
                        Simpan Lowongan
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>

@endsection