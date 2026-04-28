@extends('admin.layouts.app')

@section('title','Edit Absensi Sesi')

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
                                <i data-feather="edit"></i>
                            </div>
                            Edit Absensi Sesi
                        </h1>

                        {{-- <div class="page-header-subtitle">
                            Sesi {{ $sesi->sesi_ke }}
                            —
                            {{ $sesi->absensi->tanggal->format('d F Y') }}
                        </div> --}}
                    </div>


                    <div class="col-auto mb-3">
                        <a href="{{ route('data_absen',['type'=>'sesi']) }}" class="btn btn-sm btn-light">
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




                <div class="card">
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

            </div>





            {{-- FORM --}}
            <div class="col-xl-8">

                <form action="{{ route('admin.absensi-sesi.update',$sesi->id) }}" method="POST">

                    @csrf
                    @method('PUT')

                    <div class="card mb-4">

                        <div class="card-header">
                            Form Edit Absensi Sesi
                        </div>


                        <div class="card-body p-4">

                            <div class="row g-4 mb-4">

                                <div class="col-md-6">
                                    <label class="small mb-1">
                                        Nama Karyawan
                                    </label>

                                    <input type="text" class="form-control" value="{{ $sesi->absensi->karyawan->nama }}"
                                        disabled>
                                </div>



                                <div class="col-md-6">
                                    <label class="small mb-1">
                                        Tanggal
                                    </label>

                                    <input type="text" class="form-control"
                                        value="{{ $sesi->absensi->tanggal->format('d F Y') }}" disabled>
                                </div>

                            </div>





                            <div class="row g-4 mb-4">

                                <div class="col-md-6">

                                    <label class="small mb-1">
                                        Jam Check In
                                    </label>

                                    <input type="time" name="jam_checkin"
                                        class="form-control @error('jam_checkin') is-invalid @enderror"
                                        value="{{ old('jam_checkin',$sesi->jam_checkin) }}">

                                    @error('jam_checkin')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror

                                </div>




                                <div class="col-md-6">

                                    <label class="small mb-1">
                                        Jam Check Out
                                    </label>

                                    <input type="time" name="jam_checkout"
                                        class="form-control @error('jam_checkout') is-invalid @enderror"
                                        value="{{ old('jam_checkout',$sesi->jam_checkout) }}">

                                    @error('jam_checkout')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror

                                </div>

                            </div>





                            <div class="row g-4 mb-4">

                                <div class="col-md-6">

                                    <label class="small mb-1">
                                        Status Kehadiran
                                        <span class="text-danger">*</span>
                                    </label>

                                    <select name="status" class="form-select @error('status') is-invalid @enderror"
                                        required>

                                        <option value="">
                                            Pilih Status
                                        </option>

                                        <option value="hadir" {{ $sesi->status=='hadir'?'selected':'' }}>
                                            Hadir
                                        </option>

                                        <option value="terlambat" {{ $sesi->status=='terlambat'?'selected':'' }}>
                                            Terlambat
                                        </option>

                                        <option value="izin" {{ $sesi->status=='izin'?'selected':'' }}>
                                            Izin
                                        </option>

                                        <option value="alpha" {{ $sesi->status=='alpha'?'selected':'' }}>
                                            Alpha
                                        </option>

                                    </select>

                                    @error('status')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror

                                </div>




                                <div class="col-md-6">

                                    <label class="small mb-1">
                                        Sesi Ke
                                    </label>

                                    <input type="text" class="form-control" value="{{ $sesi->sesi_ke }}" disabled>

                                </div>

                            </div>





                            <div class="mb-4">

                                <label class="small mb-1">
                                    Keterangan
                                </label>

                                <textarea name="keterangan" rows="4"
                                    class="form-control @error('keterangan') is-invalid @enderror"
                                    placeholder="Catatan tambahan (opsional)">{{ old('keterangan',$sesi->keterangan) }}</textarea>

                                @error('keterangan')
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


                            <a href="{{ route('data_absen',['type'=>'sesi']) }}" class="btn btn-light">
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