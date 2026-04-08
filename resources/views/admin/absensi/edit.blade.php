@extends('admin.layouts.app')

@section('title', 'Edit Absensi')

@section('content')
<main>
    <header class="page-header page-header-dark bg-gradient-primary-to-secondary pb-10">
        <div class="container-xl px-4">
            <div class="page-header-content pt-4 d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="page-header-title">
                        <div class="page-header-icon"><i data-feather="edit"></i></div>
                        Edit Absensi
                    </h1>
                    <div class="page-header-subtitle">{{ $absensi->karyawan->nama }} &mdash; {{ $absensi->tanggal->format('d F Y') }}</div>
                </div>
                <a href="{{ route('data_absen') }}" class="btn btn-white btn-sm">
                    <i data-feather="arrow-left"></i> Kembali
                </a>
            </div>
        </div>
    </header>

    <div class="container-xl px-4 mt-n10">
        <div class="row justify-content-center">
            <div class="col-lg-7">
                <div class="card mb-4">
                    <div class="card-header">Form Edit Absensi</div>
                    <div class="card-body">
                        <form action="{{ route('admin.absensi.update', $absensi->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label class="form-label">Karyawan</label>
                                <input type="text" class="form-control" value="{{ $absensi->karyawan->nama }}" disabled>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Tanggal</label>
                                <input type="text" class="form-control" value="{{ $absensi->tanggal->format('d F Y') }}" disabled>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Jam Masuk</label>
                                    <input type="time" name="jam_masuk"
                                        class="form-control @error('jam_masuk') is-invalid @enderror"
                                        value="{{ old('jam_masuk', $absensi->jam_masuk) }}">
                                    @error('jam_masuk')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Jam Keluar</label>
                                    <input type="time" name="jam_keluar"
                                        class="form-control @error('jam_keluar') is-invalid @enderror"
                                        value="{{ old('jam_keluar', $absensi->jam_keluar) }}">
                                    @error('jam_keluar')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label">Status <span class="text-danger">*</span></label>
                                <select name="status" class="form-select @error('status') is-invalid @enderror">
                                    <option value="hadir" {{ old('status', $absensi->status) == 'hadir' ? 'selected' : '' }}>Hadir</option>
                                    <option value="terlambat" {{ old('status', $absensi->status) == 'terlambat' ? 'selected' : '' }}>Terlambat</option>
                                    <option value="izin" {{ old('status', $absensi->status) == 'izin' ? 'selected' : '' }}>Izin</option>
                                    <option value="alpha" {{ old('status', $absensi->status) == 'alpha' ? 'selected' : '' }}>Alpha</option>
                                </select>
                                @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                <a href="{{ route('data_absen') }}" class="btn btn-secondary">Batal</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
