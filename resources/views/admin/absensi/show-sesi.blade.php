@extends('admin.layouts.app')

@section('title', 'Detail Absensi Sesi')

@section('content')
<main>
    <header class="page-header page-header-dark bg-gradient-primary-to-secondary pb-10">
        <div class="container-xl px-4">
            <div class="page-header-content pt-4 d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="page-header-title">
                        <div class="page-header-icon"><i data-feather="file-text"></i></div>
                        Detail Absensi Sesi
                    </h1>
                    <div class="page-header-subtitle">Sesi {{ $sesi->sesi_ke }} - {{ $sesi->absensi->tanggal->format('d
                        F Y') }}</div>
                </div>
                <a href="{{ route('data_absen', ['type' => 'sesi']) }}" class="btn btn-white btn-sm">
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
                        <img src="{{ $sesi->absensi->karyawan->foto ? asset('storage/'.$sesi->absensi->karyawan->foto) : 'https://ui-avatars.com/api/?name='.urlencode($sesi->absensi->karyawan->nama).'&size=128' }}"
                            width="100" height="100" class="rounded-circle mb-3">
                        <h5 class="fw-bold text-capitalize mb-1">{{ $sesi->absensi->karyawan->nama }}</h5>
                        <div class="text-muted small mb-1">{{ optional($sesi->absensi->karyawan->jabatan)->nama_jabatan
                            ?? '-' }}</div>
                        <div class="text-muted small">NIK: {{ $sesi->absensi->karyawan->nik }}</div>
                    </div>
                </div>
            </div>

            {{-- DETAIL ABSENSI SESI --}}
            <div class="col-lg-8 mb-4">
                <div class="card h-100">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        Detail Sesi {{ $sesi->sesi_ke }}
                        @php
                        $badge = match($sesi->status) {
                        'hadir' => 'success',
                        'terlambat' => 'warning',
                        'izin' => 'info',
                        'alpha' => 'danger',
                        default => 'secondary',
                        };
                        @endphp
                        <span class="badge bg-{{ $badge }} text-capitalize fs-6">{{ $sesi->status }}</span>
                    </div>
                    <div class="card-body">
                        <dl class="row mb-0">
                            <dt class="col-sm-4">Tanggal</dt>
                            <dd class="col-sm-8">{{ $sesi->absensi->tanggal->format('d F Y') }}</dd>

                            <dt class="col-sm-4">Sesi Ke</dt>
                            <dd class="col-sm-8">{{ $sesi->sesi_ke }}</dd>

                            <dt class="col-sm-4">Jam Check In</dt>
                            <dd class="col-sm-8">{{ $sesi->jam_checkin ?? '-' }}</dd>

                            <dt class="col-sm-4">Jam Check Out</dt>
                            <dd class="col-sm-8">{{ $sesi->jam_checkout ?? '-' }}</dd>

                            @if($sesi->latitude_masuk)
                            <dt class="col-sm-4">Lokasi Check In</dt>
                            <dd class="col-sm-8">
                                <a href="https://maps.google.com/?q={{ $sesi->latitude_masuk }},{{ $sesi->longitude_masuk }}"
                                    target="_blank" class="text-decoration-none">
                                    <i data-feather="map-pin" style="width:14px"></i>
                                    {{ $sesi->latitude_masuk }}, {{ $sesi->longitude_masuk }}
                                </a>
                            </dd>
                            @endif

                            @if($sesi->latitude_keluar)
                            <dt class="col-sm-4">Lokasi Check Out</dt>
                            <dd class="col-sm-8">
                                <a href="https://maps.google.com/?q={{ $sesi->latitude_keluar }},{{ $sesi->longitude_keluar }}"
                                    target="_blank" class="text-decoration-none">
                                    <i data-feather="map-pin" style="width:14px"></i>
                                    {{ $sesi->latitude_keluar }}, {{ $sesi->longitude_keluar }}
                                </a>
                            </dd>
                            @endif

                            @if($sesi->keterangan)
                            <dt class="col-sm-4">Keterangan</dt>
                            <dd class="col-sm-8">{{ $sesi->keterangan }}</dd>
                            @endif
                        </dl>
                    </div>
                    <div class="card-footer d-flex gap-2">
                        <a href="{{ route('admin.absensi-sesi.edit', $sesi->id) }}" class="btn btn-warning btn-sm">
                            <i data-feather="edit"></i> Edit
                        </a>
                        <button class="btn btn-danger btn-sm" onclick="confirmDelete({{ $sesi->id }})">
                            <i data-feather="trash-2"></i> Hapus
                        </button>
                        <form id="delete-form-{{ $sesi->id }}"
                            action="{{ route('admin.absensi-sesi.destroy', $sesi->id) }}" method="POST"
                            style="display:none;">
                            @csrf
                            @method('DELETE')
                        </form>
                    </div>
                </div>
            </div>

            {{-- FOTO CHECK IN & CHECK OUT --}}
            @if($sesi->foto_masuk || $sesi->foto_keluar)
            <div class="col-12 mb-4">
                <div class="card">
                    <div class="card-header">Foto Sesi</div>
                    <div class="card-body">
                        <div class="row g-3">
                            @if($sesi->foto_masuk)
                            <div class="col-md-6 text-center">
                                <p class="fw-bold text-muted small mb-2">Foto Check In</p>
                                <img src="{{ asset('storage/'.$sesi->foto_masuk) }}" class="img-fluid rounded"
                                    style="max-height:300px">
                            </div>
                            @endif
                            @if($sesi->foto_keluar)
                            <div class="col-md-6 text-center">
                                <p class="fw-bold text-muted small mb-2">Foto Check Out</p>
                                <img src="{{ asset('storage/'.$sesi->foto_keluar) }}" class="img-fluid rounded"
                                    style="max-height:300px">
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

@push('scripts')
<script>
    function confirmDelete(id) {
        if (confirm('Apakah Anda yakin ingin menghapus data absensi sesi ini?')) {
            document.getElementById('delete-form-' + id).submit();
        }
    }
</script>
@endpush