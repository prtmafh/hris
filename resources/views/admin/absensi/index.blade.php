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

        {{-- TAB NAVIGATION --}}
        <nav class="nav nav-borders mb-4">
            <a class="nav-link {{ request('type') !== 'sesi' ? 'active' : '' }}"
                href="{{ route('data_absen', array_merge(request()->query(), ['type' => 'biasa'])) }}">
                <i data-feather="clock" class="me-2" style="width: 16px;"></i>
                Absensi Biasa
            </a>
            <a class="nav-link {{ request('type') === 'sesi' ? 'active' : '' }}"
                href="{{ route('data_absen', array_merge(request()->query(), ['type' => 'sesi'])) }}">
                <i data-feather="layers" class="me-2" style="width: 16px;"></i>
                Absensi Sesi
            </a>
        </nav>

        {{-- FILTER --}}
        <div class="card mb-4">
            <div class="card-body">

                <form method="GET" action="{{ route('data_absen') }}" class="row gx-2 gy-2 align-items-end">
                    <input type="hidden" name="type" value="{{ request('type', 'biasa') }}">

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

            <div class="card-header">
                @if(request('type') === 'sesi')
                Data Absensi Sesi
                @else
                Data Absensi Biasa
                @endif
            </div>

            <div class="card-body">

                <table id="datatablesSimple" class="table responsive">

                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Karyawan</th>
                            <th>Jabatan</th>
                            <th>Tanggal</th>
                            @if(request('type') === 'sesi')
                            <th>Sesi</th>
                            <th>Check In</th>
                            <th>Check Out</th>
                            @else
                            <th>Masuk</th>
                            <th>Keluar</th>
                            @endif
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @if(request('type') === 'sesi')
                        @forelse($absensi as $index => $sesi)
                        <tr>
                            <td>{{ $absensi->firstItem() + $index }}</td>

                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar me-2">
                                        <img class="avatar-img img-fluid"
                                            src="{{ $sesi->absensi->karyawan->foto
                                                    ? asset('storage/'.$sesi->absensi->karyawan->foto)
                                                    : 'https://ui-avatars.com/api/?name='.urlencode($sesi->absensi->karyawan->nama) }}">
                                    </div>
                                    <div class="fw-semibold text-capitalize">
                                        {{ $sesi->absensi->karyawan->nama }}
                                    </div>
                                </div>
                            </td>

                            <td class="text-capitalize">
                                {{ optional($sesi->absensi->karyawan->jabatan)->nama_jabatan ?? '-' }}
                            </td>

                            <td>{{ $sesi->absensi->tanggal->format('d M Y') }}</td>

                            <td class="fw-semibold">Sesi {{ $sesi->sesi_ke }}</td>
                            <td>{{ $sesi->jam_checkin ?? '-' }}</td>
                            <td>{{ $sesi->jam_checkout ?? '-' }}</td>

                            <td>
                                @php
                                $badge = match($sesi->status) {
                                'hadir' => 'green',
                                'terlambat' => 'yellow',
                                'izin' => 'blue',
                                'alpha' => 'red',
                                default => 'secondary',
                                };
                                @endphp

                                <span class="badge bg-{{ $badge }}-soft text-{{ $badge }} text-capitalize">
                                    {{ $sesi->status }}
                                </span>
                            </td>

                            <td>
                                <a href="{{ route('admin.absensi-sesi.show', $sesi->id) }}"
                                    class="btn btn-datatable btn-icon btn-transparent-dark me-2">
                                    <i data-feather="eye"></i>
                                </a>

                                <a href="{{ route('admin.absensi-sesi.edit', $sesi->id) }}"
                                    class="btn btn-datatable btn-icon btn-transparent-dark me-2">
                                    <i data-feather="edit"></i>
                                </a>

                                <button class="btn btn-datatable btn-icon btn-transparent-dark text-danger"
                                    onclick="confirmDeleteSesi({{ $sesi->id }})">
                                    <i data-feather="trash-2"></i>
                                </button>

                                <form id="delete-form-{{ $sesi->id }}"
                                    action="{{ route('admin.absensi-sesi.destroy', $sesi->id) }}" method="POST"
                                    style="display:none;">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted py-4">
                                Tidak ada data absensi sesi
                            </td>
                        </tr>
                        @endforelse
                        @else
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
                                Tidak ada data absensi biasa
                            </td>
                        </tr>
                        @endforelse
                        @endif
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

@push('scripts')
<script>
    function confirmDeleteSesi(id) {
        if (confirm('Apakah Anda yakin ingin menghapus data absensi sesi ini?')) {
            document.getElementById('delete-form-' + id).submit();
        }
    }
</script>
@endpush