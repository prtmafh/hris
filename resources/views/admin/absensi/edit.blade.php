@extends('admin.layouts.app')

@section('title','Edit Absensi')

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
                                <i data-feather="edit"></i>
                            </div>
                            Edit Absensi
                        </h1>

                        {{-- <div class="page-header-subtitle">
                            {{ $absensi->karyawan->nama }}
                            —
                            {{ $absensi->tanggal->format('d F Y') }}
                        </div> --}}
                    </div>


                    <div class="col-auto mb-3">
                        <a href="{{ route('data_absen') }}" class="btn btn-sm btn-light">
                            <i data-feather="arrow-left"></i>
                            Kembali
                        </a>
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



                <div class="card">
                    <div class="card-header">
                        Ringkasan Absensi
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

            </div>




            {{-- FORM --}}
            <div class="col-xl-8">

                <form action="{{ route('admin.absensi.update',$absensi->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="card mb-4">

                        <div class="card-header">
                            Form Edit Absensi
                        </div>

                        <div class="card-body p-4">


                            <div class="row g-4 mb-4">

                                <div class="col-md-6">
                                    <label class="small mb-1">
                                        Karyawan
                                    </label>

                                    <input type="text" class="form-control" value="{{ $absensi->karyawan->nama }}"
                                        disabled>
                                </div>



                                <div class="col-md-6">
                                    <label class="small mb-1">
                                        Tanggal
                                    </label>

                                    <input type="text" class="form-control"
                                        value="{{ $absensi->tanggal->format('d F Y') }}" disabled>
                                </div>

                            </div>




                            <div class="row g-4 mb-4">

                                <div class="col-md-6">
                                    <label class="small mb-1">
                                        Jam Masuk
                                    </label>

                                    <input type="time" name="jam_masuk"
                                        class="form-control @error('jam_masuk') is-invalid @enderror"
                                        value="{{ old('jam_masuk',$absensi->jam_masuk) }}">

                                    @error('jam_masuk')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror

                                </div>




                                <div class="col-md-6">
                                    <label class="small mb-1">
                                        Jam Keluar
                                    </label>

                                    <input type="time" name="jam_keluar"
                                        class="form-control @error('jam_keluar') is-invalid @enderror"
                                        value="{{ old('jam_keluar',$absensi->jam_keluar) }}">

                                    @error('jam_keluar')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror

                                </div>

                            </div>




                            <div class="mb-4">

                                <label class="small mb-1">
                                    Status Kehadiran
                                    <span class="text-danger">*</span>
                                </label>

                                <select name="status" class="form-select @error('status') is-invalid @enderror">

                                    <option value="hadir" {{ old('status',$absensi->status)=='hadir'?'selected':'' }}>
                                        Hadir
                                    </option>

                                    <option value="terlambat" {{ old('status',$absensi->
                                        status)=='terlambat'?'selected':'' }}>
                                        Terlambat
                                    </option>

                                    <option value="izin" {{ old('status',$absensi->status)=='izin'?'selected':'' }}>
                                        Izin
                                    </option>

                                    <option value="alpha" {{ old('status',$absensi->status)=='alpha'?'selected':'' }}>
                                        Alpha
                                    </option>

                                </select>

                                @error('status')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror

                            </div>


                        </div>



                        <div class="card-footer bg-light d-flex gap-2">

                            <button type="submit" class="btn btn-primary">
                                <i data-feather="save"></i>
                                Simpan Perubahan
                            </button>

                            <a href="{{ route('data_absen') }}" class="btn btn-light">
                                Batal
                            </a>

                        </div>

                    </div>

                </form>


            </div>

        </div>

    </div>

</main>
@endsection