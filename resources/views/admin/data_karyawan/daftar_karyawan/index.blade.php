@extends('admin.layouts.app')

@section('title', 'Daftar Karyawan')

@section('content')
<main>

    {{-- HEADER (PERSIS SB ADMIN PRO) --}}
    <header class="page-header page-header-compact page-header-light border-bottom bg-white mb-4">
        <div class="container-fluid px-4">
            <div class="page-header-content">
                <div class="row align-items-center justify-content-between pt-3">

                    <div class="col-auto mb-3">
                        <h1 class="page-header-title">
                            <div class="page-header-icon">
                                <i data-feather="users"></i>
                            </div>
                            Daftar Karyawan
                        </h1>
                    </div>

                    <div class="col-12 col-xl-auto mb-3">
                        <a href="{{ route('admin.karyawan.create') }}" class="btn btn-sm btn-light text-primary">
                            <i class="me-1" data-feather="user-plus"></i>
                            Tambah Karyawan
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </header>

    {{-- CONTENT --}}
    <div class="container-fluid px-4">
        <div class="card">
            <div class="card-header">
                Daftar Karyawan
            </div>
            <div class="card-body">

                <table id="datatablesSimple">

                    <thead>
                        <tr>
                            <th>User</th>
                            <th>NIK</th>
                            <th>Role / Jabatan</th>
                            <th>Status Gaji</th>
                            <th>Gaji</th>
                            <th>Status</th>
                            <th>Joined Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    {{--
                    <tfoot>
                        <tr>
                            <th>User</th>
                            <th>NIK</th>
                            <th>Role / Jabatan</th>
                            <th>Status Gaji</th>
                            <th>Gaji</th>
                            <th>Status</th>
                            <th>Joined Date</th>
                            <th>Actions</th>
                        </tr>
                    </tfoot> --}}

                    <tbody>
                        @foreach($karyawan as $k)
                        <tr>

                            {{-- USER (PERSIS TEMPLATE) --}}
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar me-2">
                                        <img class="avatar-img img-fluid" src="{{ $k->foto 
                                                ? asset('storage/'.$k->foto) 
                                                : 'https://ui-avatars.com/api/?name='.urlencode($k->nama) }}">
                                    </div>
                                    {{ $k->nama }}
                                </div>
                            </td>

                            {{-- NIK --}}
                            <td>{{ $k->nik }}</td>

                            {{-- ROLE / JABATAN (PAKAI BADGE STYLE SB PRO) --}}
                            <td>
                                <span class="badge bg-blue-soft text-blue">
                                    {{ optional($k->jabatan)->nama_jabatan ?? '-' }}
                                </span>
                            </td>

                            {{-- STATUS GAJI --}}
                            <td>
                                @if($k->status_gaji == 'harian')
                                <span class="badge bg-green-soft text-green">Harian</span>
                                @else
                                <span class="badge bg-purple-soft text-purple">Bulanan</span>
                                @endif
                            </td>

                            {{-- GAJI --}}
                            <td>
                                @if($k->status_gaji == 'harian')
                                Rp {{ number_format($k->gaji_per_hari ?? 0, 0, ',', '.') }}
                                @else
                                Rp {{ number_format($k->gaji_pokok ?? 0, 0, ',', '.') }}
                                @endif
                            </td>

                            {{-- STATUS --}}
                            <td>
                                @if($k->status === 'aktif')
                                <span class="badge bg-green-soft text-green">Aktif</span>
                                @else
                                <span class="badge bg-red-soft text-red">Nonaktif</span>
                                @endif
                            </td>

                            {{-- JOINED DATE --}}
                            <td>
                                {{ \Carbon\Carbon::parse($k->tgl_masuk)->format('d M Y') }}
                            </td>

                            {{-- ACTIONS (PERSIS STYLE) --}}
                            <td>
                                <a class="btn btn-datatable btn-icon btn-transparent-dark me-2"
                                    href="{{ route('admin.karyawan.edit', $k->id) }}">
                                    <i data-feather="edit"></i>
                                </a>

                                <a class="btn btn-datatable btn-icon btn-transparent-dark me-2"
                                    href="{{ route('admin.karyawan.show', $k->id) }}">
                                    <i data-feather="eye"></i>
                                </a>

                                <button class="btn btn-datatable btn-icon btn-transparent-dark"
                                    onclick="confirmDelete({{ $k->id }})">
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

</main>
@endsection