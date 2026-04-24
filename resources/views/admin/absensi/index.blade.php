@extends('admin.layouts.app')

@section('title', 'Data Absensi')

@section('content')
<main>

    {{-- HEADER --}}
    <header class="page-header page-header-compact page-header-light border-bottom bg-white mb-4 py-2">
        <div class="container-xl px-4">
            <div class="page-header-content">
                <div class="row align-items-center justify-content-between pt-3">

                    <div class="col-auto mb-3">
                        <h1 class="page-header-title">
                            <div class="page-header-icon"><i data-feather="clock"></i></div>
                            Data Absensi
                        </h1>
                    </div>

                </div>
            </div>
        </div>
    </header>

    <div class="container-xl px-4">

        {{-- FILTER --}}
        <div class="card mb-4">
            <div class="card-body">

                <form method="GET" action="{{ route('data_absen') }}" class="row gx-2 gy-2 align-items-end">

                    <div class="col-md-3">
                        <label class="small mb-1">Dari Tanggal</label>
                        <input type="date" name="tanggal_dari" class="form-control form-control-sm"
                            value="{{ request('tanggal_dari') }}">
                    </div>

                    <div class="col-md-3">
                        <label class="small mb-1">Sampai Tanggal</label>
                        <input type="date" name="tanggal_sampai" class="form-control form-control-sm"
                            value="{{ request('tanggal_sampai') }}">
                    </div>

                    <div class="col-md-2">
                        <label class="small mb-1">Karyawan</label>
                        <select name="karyawan_id" class="form-select form-select-sm">
                            <option value="">Semua</option>
                            @foreach($karyawanList as $k)
                            <option value="{{ $k->id }}" {{ request('karyawan_id')==$k->id ? 'selected' : '' }}>
                                {{ $k->nama }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label class="small mb-1">Status</label>
                        <select name="status" class="form-select form-select-sm">
                            <option value="">Semua</option>
                            <option value="hadir" {{ request('status')=='hadir' ? 'selected' : '' }}>Hadir</option>
                            <option value="terlambat" {{ request('status')=='terlambat' ? 'selected' : '' }}>Terlambat
                            </option>
                            <option value="izin" {{ request('status')=='izin' ? 'selected' : '' }}>Izin</option>
                            <option value="alpha" {{ request('status')=='alpha' ? 'selected' : '' }}>Alpha</option>
                        </select>
                    </div>

                    <div class="col-md-2 d-flex gap-1">
                        <button type="submit" class="btn btn-primary btn-sm w-100">
                            <i data-feather="search"></i>
                        </button>

                        <a href="{{ route('admin.absensi.export', request()->query()) }}"
                            class="btn btn-success btn-sm">
                            <i data-feather="download"></i>
                        </a>

                        <a href="{{ route('data_absen') }}" class="btn btn-light btn-sm">
                            <i data-feather="x"></i>
                        </a>
                    </div>

                </form>

            </div>
        </div>

        {{-- TABLE --}}
        <div class="card mb-4">

            <div class="card-header">Data Absensi</div>

            <div class="card-body">

                <table id="datatablesSimple" class="table responsive">

                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Karyawan</th>
                            <th>Jabatan</th>
                            <th>Tanggal</th>
                            <th>Masuk</th>
                            <th>Keluar</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($absensi as $index => $a)
                        <tr>

                            <td>{{ $absensi->firstItem() + $index }}</td>

                            {{-- KARYAWAN --}}
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar me-2">
                                        <img class="avatar-img img-fluid"
                                            src="{{ $a->karyawan->foto 
                                                ? asset('storage/'.$a->karyawan->foto) 
                                                : 'https://ui-avatars.com/api/?name='.urlencode($a->karyawan->nama) }}">
                                    </div>
                                    <div class="fw-semibold text-capitalize">
                                        {{ $a->karyawan->nama }}
                                    </div>
                                </div>
                            </td>

                            <td class="text-capitalize">
                                {{ optional($a->karyawan->jabatan)->nama_jabatan ?? '-' }}
                            </td>

                            <td>{{ $a->tanggal->format('d M Y') }}</td>

                            <td>{{ $a->jam_masuk ?? '-' }}</td>
                            <td>{{ $a->jam_keluar ?? '-' }}</td>

                            {{-- STATUS --}}
                            <td>
                                @php
                                $badge = match($a->status) {
                                'hadir' => 'green',
                                'terlambat' => 'yellow',
                                'izin' => 'blue',
                                'alpha' => 'red',
                                default => 'secondary',
                                };
                                @endphp

                                <span class="badge bg-{{ $badge }}-soft text-{{ $badge }} text-capitalize">
                                    {{ $a->status }}
                                </span>
                            </td>

                            {{-- AKSI --}}
                            <td>

                                <a href="{{ route('admin.absensi.show', $a->id) }}"
                                    class="btn btn-datatable btn-icon btn-transparent-dark me-2">
                                    <i data-feather="eye"></i>
                                </a>

                                <a href="{{ route('admin.absensi.edit', $a->id) }}"
                                    class="btn btn-datatable btn-icon btn-transparent-dark me-2">
                                    <i data-feather="edit"></i>
                                </a>

                                <button class="btn btn-datatable btn-icon btn-transparent-dark text-danger"
                                    onclick="confirmDelete({{ $a->id }})">
                                    <i data-feather="trash-2"></i>
                                </button>

                                <form id="delete-form-{{ $a->id }}"
                                    action="{{ route('admin.absensi.destroy', $a->id) }}" method="POST"
                                    style="display:none;">
                                    @csrf
                                    @method('DELETE')
                                </form>

                            </td>

                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">
                                Tidak ada data absensi
                            </td>
                        </tr>
                        @endforelse
                    </tbody>

                </table>

                <div class="mt-3">
                    {{ $absensi->links() }}
                </div>

            </div>
        </div>

    </div>
</main>
@endsection
