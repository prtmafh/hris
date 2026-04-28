@extends('admin.layouts.app')

@section('title','Detail Absensi')

@section('content')
@php
$badge = match($absensi->status){
'hadir'=>'green',
'terlambat'=>'yellow',
'izin'=>'blue',
'alpha'=>'red',
default=>'secondary'
};
@endphp

<main>

    <header class="page-header page-header-compact page-header-light border-bottom bg-white mb-4">
        <div class="container-xl px-4">
            <div class="page-header-content">
                <div class="row align-items-center justify-content-between pt-3">

                    <div class="col-auto mb-3">
                        <h1 class="page-header-title">
                            <div class="page-header-icon">
                                <i data-feather="clipboard"></i>
                            </div>
                            Detail Absensi
                        </h1>

                        <div class="page-header-subtitle">
                            {{ $absensi->tanggal->format('d F Y') }}
                        </div>
                    </div>

                    <div class="col-auto mb-3 d-flex gap-2">

                        <a href="{{ route('data_absen') }}" class="btn btn-sm btn-light">
                            <i data-feather="arrow-left"></i>
                            Kembali
                        </a>

                        <a href="{{ route('admin.absensi.edit',$absensi->id) }}" class="btn btn-sm btn-primary">
                            <i data-feather="edit"></i>
                            Edit
                        </a>

                        <button class="btn btn-sm btn-danger" onclick="confirmDelete({{ $absensi->id }})">
                            <i data-feather="trash-2"></i>
                            Hapus
                        </button>

                        <form id="delete-form-{{ $absensi->id }}"
                            action="{{ route('admin.absensi.destroy',$absensi->id) }}" method="POST"
                            style="display:none;">
                            @csrf
                            @method('DELETE')
                        </form>

                    </div>

                </div>
            </div>
        </div>
    </header>



    <div class="container-xl px-4">

        <div class="row">

            {{-- SIDEBAR PROFIL --}}
            <div class="col-xl-4">

                <div class="card mb-4">
                    <div class="card-body text-center">

                        <img class="img-account-profile rounded-circle mb-3" src="{{ $absensi->karyawan->foto
? asset('storage/'.$absensi->karyawan->foto)
: 'https://ui-avatars.com/api/?name='.urlencode($absensi->karyawan->nama) }}">

                        <div class="fw-bold fs-5 text-capitalize">
                            {{ $absensi->karyawan->nama }}
                        </div>

                        <div class="small text-muted mb-2">
                            {{ optional($absensi->karyawan->jabatan)->nama_jabatan ?? '-' }}
                        </div>

                        <div class="small text-muted mb-3">
                            NIK: {{ $absensi->karyawan->nik }}
                        </div>


                        <span class="badge bg-{{ $badge }}-soft text-{{ $badge }}">
                            {{ ucfirst($absensi->status) }}
                        </span>

                    </div>
                </div>



                <div class="card mb-4">
                    <div class="card-header">
                        Ringkasan Kehadiran
                    </div>

                    <div class="card-body">

                        <div class="d-flex justify-content-between border-bottom py-2">
                            <span class="small text-muted">
                                Tanggal
                            </span>

                            <strong>
                                {{ $absensi->tanggal->format('d M Y') }}
                            </strong>
                        </div>


                        <div class="d-flex justify-content-between border-bottom py-2">
                            <span class="small text-muted">
                                Jam Masuk
                            </span>

                            <strong>
                                {{ $absensi->jam_masuk ?: '-' }}
                            </strong>
                        </div>


                        <div class="d-flex justify-content-between py-2">
                            <span class="small text-muted">
                                Jam Keluar
                            </span>

                            <strong>
                                {{ $absensi->jam_keluar ?: '-' }}
                            </strong>
                        </div>

                    </div>
                </div>



                @if($absensi->latitude_masuk || $absensi->latitude_keluar)
                <div class="card">
                    <div class="card-header">
                        Data Lokasi
                    </div>

                    <div class="card-body">

                        @if($absensi->latitude_masuk)
                        <div class="mb-3">

                            <div class="small text-muted mb-1">
                                Lokasi Masuk
                            </div>

                            <a href="https://maps.google.com/?q={{ $absensi->latitude_masuk }},{{ $absensi->longitude_masuk }}"
                                target="_blank" class="btn btn-light border btn-sm">
                                <i data-feather="map-pin"></i>
                                Lihat Lokasi
                            </a>

                        </div>
                        @endif


                        @if($absensi->latitude_keluar)
                        <div>

                            <div class="small text-muted mb-1">
                                Lokasi Keluar
                            </div>

                            <a href="https://maps.google.com/?q={{ $absensi->latitude_keluar }},{{ $absensi->longitude_keluar }}"
                                target="_blank" class="btn btn-light border btn-sm">
                                <i data-feather="map-pin"></i>
                                Lihat Lokasi
                            </a>

                        </div>
                        @endif

                    </div>
                </div>
                @endif

            </div>




            {{-- KONTEN --}}
            <div class="col-xl-8">

                <div class="card mb-4">
                    <div class="card-header">
                        Informasi Absensi
                    </div>

                    <div class="card-body">

                        <div class="row g-4">

                            <div class="col-md-6">
                                <label class="small text-muted d-block mb-1">
                                    Nama Karyawan
                                </label>

                                <div class="fw-semibold">
                                    {{ $absensi->karyawan->nama }}
                                </div>
                            </div>



                            <div class="col-md-6">
                                <label class="small text-muted d-block mb-1">
                                    Status Kehadiran
                                </label>

                                <span class="badge bg-{{ $badge }}-soft text-{{ $badge }}">
                                    {{ ucfirst($absensi->status) }}
                                </span>

                            </div>



                            <div class="col-md-6">
                                <label class="small text-muted d-block mb-1">
                                    Jam Masuk
                                </label>

                                <div class="fw-semibold">
                                    {{ $absensi->jam_masuk ?: '-' }}
                                </div>
                            </div>



                            <div class="col-md-6">
                                <label class="small text-muted d-block mb-1">
                                    Jam Keluar
                                </label>

                                <div class="fw-semibold">
                                    {{ $absensi->jam_keluar ?: '-' }}
                                </div>
                            </div>

                        </div>

                    </div>
                </div>




                @if($absensi->foto_masuk || $absensi->foto_keluar)

                <div class="card">
                    <div class="card-header">
                        Foto Kehadiran
                    </div>

                    <div class="card-body">

                        <div class="row g-4">

                            @if($absensi->foto_masuk)
                            <div class="col-md-6">

                                <div class="card border shadow-none">
                                    <div class="card-body text-center">

                                        <div class="small text-muted mb-3">
                                            Foto Masuk
                                        </div>

                                        <img src="{{ asset('storage/'.$absensi->foto_masuk) }}"
                                            class="img-fluid rounded">

                                    </div>
                                </div>

                            </div>
                            @endif




                            @if($absensi->foto_keluar)
                            <div class="col-md-6">

                                <div class="card border shadow-none">
                                    <div class="card-body text-center">

                                        <div class="small text-muted mb-3">
                                            Foto Keluar
                                        </div>

                                        <img src="{{ asset('storage/'.$absensi->foto_keluar) }}"
                                            class="img-fluid rounded">

                                    </div>
                                </div>

                            </div>
                            @endif


                        </div>

                    </div>
                </div>

                @endif


            </div>
        </div>

    </div>

</main>
@endsection