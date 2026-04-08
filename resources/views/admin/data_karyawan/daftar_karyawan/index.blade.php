@extends('admin.layouts.app')

@section('title', 'Daftar Karyawan')

@section('content')
<main>
    <header class="page-header page-header-dark bg-gradient-primary-to-secondary pb-10">
        <div class="container-xl px-4">
            <div class="page-header-content pt-4 d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="page-header-title">
                        <div class="page-header-icon"><i data-feather="users"></i></div>
                        Daftar Karyawan
                    </h1>
                    <div class="page-header-subtitle">Manajemen data karyawan</div>
                </div>
                {{--
                <button class="btn btn-white btn-sm" data-bs-toggle="modal" data-bs-target="#modalTambahKaryawan">
                    <i data-feather="plus"></i> Tambah Karyawan
                </button> --}}
                <a href="{{ route('admin.karyawan.create') }}" class="btn btn-white btn-sm">
                    <i data-feather="plus"></i> Tambah Karyawan
                </a>
            </div>
        </div>
    </header>

    <div class="container-xl px-4 mt-n10">
        <div class="row">{{-- TABLE --}}
            <div class="col-lg-12">
                <div class="card mb-4">
                    <div class="card-header">Data Karyawan</div>
                    <div class="card-body">
                        <table id="datatablesSimple" class="table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Karyawan</th>
                                    <th>Jabatan</th>
                                    <th>Status Gaji</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($karyawan as $index => $k)
                                <tr>
                                    <td>{{ $index + 1 }}</td>

                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="{{ $k->foto ? asset('storage/'.$k->foto) : 'https://ui-avatars.com/api/?name='.urlencode($k->nama) }}"
                                                width="40" height="40" class="rounded-circle me-2">
                                            <div class="fw-bold text-capitalize">{{ $k->nama }}</div>
                                        </div>
                                    </td>

                                    <td class="text-capitalize">{{ optional($k->jabatan)->nama_jabatan ?? '-' }}</td>

                                    <td>
                                        @if($k->status_gaji == 'harian')
                                        <span class="badge bg-info">Harian</span>
                                        @else
                                        <span class="badge bg-primary">Bulanan</span>
                                        @endif
                                    </td>

                                    <td>
                                        @if($k->status === 'aktif')
                                        <span class="badge bg-success">Aktif</span>
                                        @else
                                        <span class="badge bg-danger">Nonaktif</span>
                                        @endif
                                    </td>

                                    <td>
                                        <a href="{{ route('admin.karyawan.show', $k->id) }}"
                                            class="btn btn-sm btn-info text-white">
                                            <i data-feather="eye"></i>
                                        </a>

                                        <a href="{{ route('admin.karyawan.edit', $k->id) }}"
                                            class="btn btn-sm btn-warning ms-1">
                                            <i data-feather="edit"></i>
                                        </a>

                                        <button class="btn btn-sm btn-danger" onclick="confirmDelete({{ $k->id }})">
                                            <i data-feather="trash-2"></i>
                                        </button>

                                        <form id="delete-form-{{ $k->id }}"
                                            action="{{ route('admin.karyawan.destroy', $k->id) }}" method="POST"
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

@endsection

{{-- @endpush --}}