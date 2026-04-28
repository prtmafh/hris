@extends('admin.layouts.app')

@section('title','Tambah Absensi')

@section('content')
<main>

    <header class="page-header page-header-compact page-header-light border-bottom bg-white mb-4">
        <div class="container-xl px-4">
            <div class="page-header-content">
                <div class="row align-items-center justify-content-between pt-3">

                    <div class="col-auto mb-3">
                        <h1 class="page-header-title">
                            <div class="page-header-icon">
                                <i data-feather="plus-circle"></i>
                            </div>
                            Tambah Absensi
                        </h1>

                        <div class="page-header-subtitle">
                            Input data absensi karyawan secara manual
                        </div>
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

                        <div class="avatar avatar-xl mb-3">
                            <div
                                class="avatar-img rounded-circle bg-primary-soft d-flex align-items-center justify-content-center">
                                <i data-feather="user-plus"></i>
                            </div>
                        </div>

                        <div class="fw-bold fs-5">
                            Absensi Manual
                        </div>

                        <div class="small text-muted mb-3">
                            Input presensi karyawan
                        </div>

                        <span class="badge bg-blue-soft text-blue">
                            Form Input Kehadiran
                        </span>

                    </div>
                </div>




                <div class="card">
                    <div class="card-header">
                        Panduan Input
                    </div>

                    <div class="card-body">

                        <div class="small text-muted mb-3">
                            Pastikan data berikut sesuai:
                        </div>

                        <ul class="small mb-0 ps-3">
                            <li class="mb-2">
                                Pilih karyawan yang sesuai
                            </li>

                            <li class="mb-2">
                                Pastikan tanggal absensi benar
                            </li>

                            <li class="mb-2">
                                Isi jam masuk dan keluar bila ada
                            </li>

                            <li>
                                Pilih status kehadiran yang tepat
                            </li>

                        </ul>

                    </div>
                </div>

            </div>





            {{-- FORM --}}
            <div class="col-xl-8">

                <form action="{{ route('admin.absensi.store') }}" method="POST">

                    @csrf

                    <div class="card mb-4">

                        <div class="card-header">
                            Form Tambah Absensi
                        </div>

                        <div class="card-body p-4">


                            <div class="mb-4">

                                <label class="small mb-1">
                                    Karyawan
                                    <span class="text-danger">*</span>
                                </label>

                                <select name="karyawan_id"
                                    class="form-select @error('karyawan_id') is-invalid @enderror">

                                    <option value="">
                                        -- Pilih Karyawan --
                                    </option>

                                    @foreach($karyawanList as $k)
                                    <option value="{{ $k->id }}" {{ old('karyawan_id')==$k->id?'selected':'' }}>

                                        {{ $k->nama }} ({{ $k->nik }})

                                    </option>
                                    @endforeach

                                </select>

                                @error('karyawan_id')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror

                            </div>





                            <div class="mb-4">

                                <label class="small mb-1">
                                    Tanggal
                                    <span class="text-danger">*</span>
                                </label>

                                <input type="date" name="tanggal"
                                    class="form-control @error('tanggal') is-invalid @enderror"
                                    value="{{ old('tanggal',now()->toDateString()) }}">

                                @error('tanggal')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror

                            </div>





                            <div class="row g-4 mb-4">

                                <div class="col-md-6">

                                    <label class="small mb-1">
                                        Jam Masuk
                                    </label>

                                    <input type="time" name="jam_masuk"
                                        class="form-control @error('jam_masuk') is-invalid @enderror"
                                        value="{{ old('jam_masuk') }}">

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
                                        value="{{ old('jam_keluar') }}">

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

                                    <option value="">
                                        -- Pilih Status --
                                    </option>

                                    <option value="hadir" {{ old('status')=='hadir' ?'selected':'' }}>
                                        Hadir
                                    </option>

                                    <option value="terlambat" {{ old('status')=='terlambat' ?'selected':'' }}>
                                        Terlambat
                                    </option>

                                    <option value="izin" {{ old('status')=='izin' ?'selected':'' }}>
                                        Izin
                                    </option>

                                    <option value="alpha" {{ old('status')=='alpha' ?'selected':'' }}>
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
                                Simpan
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