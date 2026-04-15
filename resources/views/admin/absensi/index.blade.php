@extends('admin.layouts.app')

@section('title', 'Data Absensi')

@section('content')
<main>
    <header class="page-header page-header-dark bg-gradient-primary-to-secondary pb-10">
        <div class="container-xl px-4">
            <div class="page-header-content pt-4 d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="page-header-title">
                        <div class="page-header-icon"><i data-feather="clock"></i></div>
                        Data Absensi
                    </h1>
                    <div class="page-header-subtitle">Manajemen data kehadiran karyawan</div>
                </div>
                {{-- <a href="{{ route('admin.absensi.create') }}" class="btn btn-white btn-sm">
                    <i data-feather="plus"></i> Tambah Absensi
                </a> --}}
            </div>
        </div>
    </header>

    <div class="container-xl px-4 mt-n10">

        {{-- FILTER --}}
        <div class="card mb-4">
            <div class="card-body">
                <form method="GET" action="{{ route('data_absen') }}" class="row g-2 align-items-end">
                    <div class="col-md-3">
                        <label class="form-label small mb-1">Dari Tanggal</label>
                        <input type="date" name="tanggal_dari" class="form-control form-control-sm"
                            value="{{ request('tanggal_dari') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small mb-1">Sampai Tanggal</label>
                        <input type="date" name="tanggal_sampai" class="form-control form-control-sm"
                            value="{{ request('tanggal_sampai') }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small mb-1">Karyawan</label>
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
                        <label class="form-label small mb-1">Status</label>
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
                            <i data-feather="search"></i> Filter
                        </button>
                        <a href="{{ route('data_absen') }}" class="btn btn-secondary btn-sm">
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
                <table id="datatablesSimple" class="table table-hover">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Karyawan</th>
                            <th>Jabatan</th>
                            <th>Tanggal</th>
                            <th>Jam Masuk</th>
                            <th>Jam Keluar</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($absensi as $index => $a)
                        <tr>
                            <td>{{ $absensi->firstItem() + $index }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <img src="{{ $a->karyawan->foto ? asset('storage/'.$a->karyawan->foto) : 'https://ui-avatars.com/api/?name='.urlencode($a->karyawan->nama) }}"
                                        width="36" height="36" class="rounded-circle me-2">
                                    <span class="fw-bold text-capitalize">{{ $a->karyawan->nama }}</span>
                                </div>
                            </td>
                            <td class="text-capitalize">{{ optional($a->karyawan->jabatan)->nama_jabatan ?? '-' }}</td>
                            <td>{{ $a->tanggal->format('d/m/Y') }}</td>
                            <td>{{ $a->jam_masuk ?? '-' }}</td>
                            <td>{{ $a->jam_keluar ?? '-' }}</td>
                            <td>
                                @php
                                $badge = match($a->status) {
                                'hadir' => 'success',
                                'terlambat' => 'warning',
                                'izin' => 'info',
                                'alpha' => 'danger',
                                default => 'secondary',
                                };
                                @endphp
                                <span class="badge bg-{{ $badge }} text-capitalize">{{ $a->status }}</span>
                            </td>
                            <td>
                                <a href="{{ route('admin.absensi.show', $a->id) }}"
                                    class="btn btn-sm btn-info text-white">
                                    <i data-feather="eye"></i>
                                </a>
                                <a href="{{ route('admin.absensi.edit', $a->id) }}" class="btn btn-sm btn-warning ms-1">
                                    <i data-feather="edit"></i>
                                </a>
                                <button class="btn btn-sm btn-danger ms-1" onclick="confirmDelete({{ $a->id }})">
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