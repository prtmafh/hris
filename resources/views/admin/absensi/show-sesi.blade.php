@extends('admin.layouts.app')

@section('title','Detail Absensi Sesi')

@section('content')
@php
$badge = match($sesi->status){
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
                                <i data-feather="layers"></i>
                            </div>
                            Detail Absensi Sesi
                        </h1>

                        <div class="page-header-subtitle">
                            Sesi {{ $sesi->sesi_ke }} •
                            {{ $sesi->absensi->tanggal->format('d F Y') }}
                        </div>
                    </div>


                    <div class="col-auto mb-3 d-flex gap-2">

                        <a href="{{ route('data_absen',['type'=>'sesi']) }}" class="btn btn-sm btn-light">
                            <i data-feather="arrow-left"></i>
                            Kembali
                        </a>

                        <a href="{{ route('admin.absensi-sesi.edit',$sesi->id) }}" class="btn btn-sm btn-primary">
                            <i data-feather="edit"></i>
                            Edit
                        </a>


                        <button class="btn btn-sm btn-danger" onclick="confirmDelete({{ $sesi->id }})">
                            <i data-feather="trash-2"></i>
                            Hapus
                        </button>

                        <form id="delete-form-{{ $sesi->id }}"
                            action="{{ route('admin.absensi-sesi.destroy',$sesi->id) }}" method="POST"
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

            {{-- SIDEBAR --}}
            <div class="col-xl-4">


                <div class="card mb-4">
                    <div class="card-body text-center">

                        <img class="img-account-profile rounded-circle mb-3" src="{{ $sesi->absensi->karyawan->foto
? asset('storage/'.$sesi->absensi->karyawan->foto)
: 'https://ui-avatars.com/api/?name='.urlencode($sesi->absensi->karyawan->nama) }}">

                        <div class="fw-bold fs-5 text-capitalize">
                            {{ $sesi->absensi->karyawan->nama }}
                        </div>

                        <div class="small text-muted mb-2">
                            {{ optional($sesi->absensi->karyawan->jabatan)->nama_jabatan ?? '-' }}
                        </div>

                        <div class="small text-muted mb-3">
                            NIK:
                            {{ $sesi->absensi->karyawan->nik }}
                        </div>


                        <span class="badge bg-{{ $badge }}-soft text-{{ $badge }}">
                            {{ ucfirst($sesi->status) }}
                        </span>

                    </div>
                </div>




                <div class="card mb-4">
                    <div class="card-header">
                        Ringkasan Sesi
                    </div>

                    <div class="card-body">


                        <div class="d-flex justify-content-between border-bottom py-2">
                            <span class="small text-muted">
                                Tanggal
                            </span>

                            <strong>
                                {{ $sesi->absensi->tanggal->format('d M Y') }}
                            </strong>
                        </div>



                        <div class="d-flex justify-content-between border-bottom py-2">
                            <span class="small text-muted">
                                Sesi Ke
                            </span>

                            <strong>
                                {{ $sesi->sesi_ke }}
                            </strong>
                        </div>



                        <div class="d-flex justify-content-between border-bottom py-2">
                            <span class="small text-muted">
                                Check In
                            </span>

                            <strong>
                                {{ $sesi->jam_checkin ?: '-' }}
                            </strong>
                        </div>



                        <div class="d-flex justify-content-between py-2">
                            <span class="small text-muted">
                                Check Out
                            </span>

                            <strong>
                                {{ $sesi->jam_checkout ?: '-' }}
                            </strong>
                        </div>


                    </div>
                </div>



                @if($sesi->latitude_masuk || $sesi->latitude_keluar)

                <div class="card">
                    <div class="card-header">
                        Lokasi Presensi
                    </div>

                    <div class="card-body">

                        @if($sesi->latitude_masuk)
                        <div class="mb-3">

                            <div class="small text-muted mb-2">
                                Lokasi Masuk
                            </div>

                            <a href="https://maps.google.com/?q={{ $sesi->latitude_masuk }},{{ $sesi->longitude_masuk }}"
                                target="_blank" class="btn btn-light border btn-sm">
                                <i data-feather="map-pin"></i>
                                Lihat Lokasi
                            </a>

                        </div>
                        @endif



                        @if($sesi->latitude_keluar)

                        <div>
                            <div class="small text-muted mb-2">
                                Lokasi Keluar
                            </div>

                            <a href="https://maps.google.com/?q={{ $sesi->latitude_keluar }},{{ $sesi->longitude_keluar }}"
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




            {{-- CONTENT --}}
            <div class="col-xl-8">

                <div class="card mb-4">

                    <div class="card-header">
                        Informasi Absensi Sesi
                    </div>

                    <div class="card-body">

                        <div class="row g-4">

                            <div class="col-md-6">
                                <label class="small text-muted d-block mb-1">
                                    Nama Karyawan
                                </label>

                                <div class="fw-semibold">
                                    {{ $sesi->absensi->karyawan->nama }}
                                </div>
                            </div>



                            <div class="col-md-6">
                                <label class="small text-muted d-block mb-1">
                                    Status Kehadiran
                                </label>

                                <span class="badge bg-{{ $badge }}-soft text-{{ $badge }}">
                                    {{ ucfirst($sesi->status) }}
                                </span>
                            </div>



                            <div class="col-md-6">
                                <label class="small text-muted d-block mb-1">
                                    Jam Check In
                                </label>

                                <div class="fw-semibold">
                                    {{ $sesi->jam_checkin ?: '-' }}
                                </div>
                            </div>



                            <div class="col-md-6">
                                <label class="small text-muted d-block mb-1">
                                    Jam Check Out
                                </label>

                                <div class="fw-semibold">
                                    {{ $sesi->jam_checkout ?: '-' }}
                                </div>
                            </div>


                            @if($sesi->keterangan)
                            <div class="col-12">

                                <label class="small text-muted d-block mb-1">
                                    Keterangan
                                </label>

                                <div class="p-3 bg-light rounded">
                                    {{ $sesi->keterangan }}
                                </div>

                            </div>
                            @endif

                        </div>

                    </div>
                </div>




                @if($sesi->foto_masuk || $sesi->foto_keluar)

                <div class="card">
                    <div class="card-header">
                        Foto Presensi Sesi
                    </div>

                    <div class="card-body">

                        <div class="row g-4">

                            @if($sesi->foto_masuk)
                            <div class="col-md-6">

                                <div class="card border shadow-none">
                                    <div class="card-body text-center">

                                        <div class="small text-muted mb-3">
                                            Foto Check In
                                        </div>

                                        <img src="{{ asset('storage/'.$sesi->foto_masuk) }}" class="img-fluid rounded">

                                    </div>
                                </div>

                            </div>
                            @endif



                            @if($sesi->foto_keluar)
                            <div class="col-md-6">

                                <div class="card border shadow-none">
                                    <div class="card-body text-center">

                                        <div class="small text-muted mb-3">
                                            Foto Check Out
                                        </div>

                                        <img src="{{ asset('storage/'.$sesi->foto_keluar) }}" class="img-fluid rounded">

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


@push('scripts')
<script>
    function confirmDelete(id){
if(confirm('Apakah Anda yakin ingin menghapus data absensi sesi ini?')){
document.getElementById('delete-form-'+id).submit();
}
}
</script>
@endpush