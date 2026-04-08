@extends('admin.layouts.app')

@section('title', 'Detail Absensi')

@section('content')
<main>
    <header class="page-header page-header-dark bg-gradient-primary-to-secondary pb-10">
        <div class="container-xl px-4">
            <div class="page-header-content pt-4 d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="page-header-title">
                        <div class="page-header-icon"><i data-feather="file-text"></i></div>
                        Detail Absensi
                    </h1>
                    <div class="page-header-subtitle">{{ $absensi->tanggal->format('d F Y') }}</div>
                </div>
                <a href="{{ route('data_absen') }}" class="btn btn-white btn-sm">
                    <i data-feather="arrow-left"></i> Kembali
                </a>
            </div>
        </div>
    </header>

    <div class="container-xl px-4 mt-n10">
        <div class="row">

            {{-- INFO KARYAWAN --}}
            <div class="col-lg-4 mb-4">
                <div class="card h-100">
                    <div class="card-header">Info Karyawan</div>
                    <div class="card-body text-center">
                        <img src="{{ $absensi->karyawan->foto ? asset('storage/'.$absensi->karyawan->foto) : 'https://ui-avatars.com/api/?name='.urlencode($absensi->karyawan->nama).'&size=128' }}"
                            width="100" height="100" class="rounded-circle mb-3">
                        <h5 class="fw-bold text-capitalize mb-1">{{ $absensi->karyawan->nama }}</h5>
                        <div class="text-muted small mb-1">{{ optional($absensi->karyawan->jabatan)->nama_jabatan ?? '-' }}</div>
                        <div class="text-muted small">NIK: {{ $absensi->karyawan->nik }}</div>
                    </div>
                </div>
            </div>

            {{-- DETAIL ABSENSI --}}
            <div class="col-lg-8 mb-4">
                <div class="card h-100">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        Detail Kehadiran
                        @php
                            $badge = match($absensi->status) {
                                'hadir'     => 'success',
                                'terlambat' => 'warning',
                                'izin'      => 'info',
                                'alpha'     => 'danger',
                                default     => 'secondary',
                            };
                        @endphp
                        <span class="badge bg-{{ $badge }} text-capitalize fs-6">{{ $absensi->status }}</span>
                    </div>
                    <div class="card-body">
                        <dl class="row mb-0">
                            <dt class="col-sm-4">Tanggal</dt>
                            <dd class="col-sm-8">{{ $absensi->tanggal->format('d F Y') }}</dd>

                            <dt class="col-sm-4">Jam Masuk</dt>
                            <dd class="col-sm-8">{{ $absensi->jam_masuk ?? '-' }}</dd>

                            <dt class="col-sm-4">Jam Keluar</dt>
                            <dd class="col-sm-8">{{ $absensi->jam_keluar ?? '-' }}</dd>

                            @if($absensi->latitude_masuk)
                            <dt class="col-sm-4">Lokasi Masuk</dt>
                            <dd class="col-sm-8">
                                <a href="https://maps.google.com/?q={{ $absensi->latitude_masuk }},{{ $absensi->longitude_masuk }}"
                                    target="_blank" class="text-decoration-none">
                                    <i data-feather="map-pin" style="width:14px"></i>
                                    {{ $absensi->latitude_masuk }}, {{ $absensi->longitude_masuk }}
                                </a>
                            </dd>
                            @endif

                            @if($absensi->latitude_keluar)
                            <dt class="col-sm-4">Lokasi Keluar</dt>
                            <dd class="col-sm-8">
                                <a href="https://maps.google.com/?q={{ $absensi->latitude_keluar }},{{ $absensi->longitude_keluar }}"
                                    target="_blank" class="text-decoration-none">
                                    <i data-feather="map-pin" style="width:14px"></i>
                                    {{ $absensi->latitude_keluar }}, {{ $absensi->longitude_keluar }}
                                </a>
                            </dd>
                            @endif
                        </dl>
                    </div>
                    <div class="card-footer d-flex gap-2">
                        <a href="{{ route('admin.absensi.edit', $absensi->id) }}" class="btn btn-warning btn-sm">
                            <i data-feather="edit"></i> Edit
                        </a>
                        <button class="btn btn-danger btn-sm" onclick="confirmDelete({{ $absensi->id }})">
                            <i data-feather="trash-2"></i> Hapus
                        </button>
                        <form id="delete-form-{{ $absensi->id }}"
                            action="{{ route('admin.absensi.destroy', $absensi->id) }}" method="POST"
                            style="display:none;">
                            @csrf
                            @method('DELETE')
                        </form>
                    </div>
                </div>
            </div>

            {{-- FOTO MASUK & KELUAR --}}
            @if($absensi->foto_masuk || $absensi->foto_keluar)
            <div class="col-12 mb-4">
                <div class="card">
                    <div class="card-header">Foto Kehadiran</div>
                    <div class="card-body">
                        <div class="row g-3">
                            @if($absensi->foto_masuk)
                            <div class="col-md-6 text-center">
                                <p class="fw-bold text-muted small mb-2">Foto Masuk</p>
                                <img src="{{ asset('storage/'.$absensi->foto_masuk) }}"
                                    class="img-fluid rounded" style="max-height:300px">
                            </div>
                            @endif
                            @if($absensi->foto_keluar)
                            <div class="col-md-6 text-center">
                                <p class="fw-bold text-muted small mb-2">Foto Keluar</p>
                                <img src="{{ asset('storage/'.$absensi->foto_keluar) }}"
                                    class="img-fluid rounded" style="max-height:300px">
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endif

        </div>
    </div>
</main>
@endsection
