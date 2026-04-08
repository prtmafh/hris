@extends('admin.layouts.app')

@section('title', 'Tambah Absensi')

@section('content')
<main>
    <header class="page-header page-header-dark bg-gradient-primary-to-secondary pb-10">
        <div class="container-xl px-4">
            <div class="page-header-content pt-4">
                <h1 class="page-header-title">
                    <div class="page-header-icon"><i data-feather="plus-circle"></i></div>
                    Tambah Absensi
                </h1>
                <div class="page-header-subtitle">Input data absensi karyawan secara manual</div>
            </div>
        </div>
    </header>

    <div class="container-xl px-4 mt-n10">
        <div class="row justify-content-center">
            <div class="col-lg-7">
                <div class="card mb-4">
                    <div class="card-header">Form Tambah Absensi</div>
                    <div class="card-body">
                        <form action="{{ route('admin.absensi.store') }}" method="POST">
                            @csrf

                            <div class="mb-3">
                                <label class="form-label">Karyawan <span class="text-danger">*</span></label>
                                <select name="karyawan_id" class="form-select @error('karyawan_id') is-invalid @enderror">
                                    <option value="">-- Pilih Karyawan --</option>
                                    @foreach($karyawanList as $k)
                                    <option value="{{ $k->id }}" {{ old('karyawan_id') == $k->id ? 'selected' : '' }}>
                                        {{ $k->nama }} ({{ $k->nik }})
                                    </option>
                                    @endforeach
                                </select>
                                @error('karyawan_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Tanggal <span class="text-danger">*</span></label>
                                <input type="date" name="tanggal" class="form-control @error('tanggal') is-invalid @enderror"
                                    value="{{ old('tanggal', now()->toDateString()) }}">
                                @error('tanggal')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Jam Masuk</label>
                                    <input type="time" name="jam_masuk" class="form-control @error('jam_masuk') is-invalid @enderror"
                                        value="{{ old('jam_masuk') }}">
                                    @error('jam_masuk')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Jam Keluar</label>
                                    <input type="time" name="jam_keluar" class="form-control @error('jam_keluar') is-invalid @enderror"
                                        value="{{ old('jam_keluar') }}">
                                    @error('jam_keluar')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label">Status <span class="text-danger">*</span></label>
                                <select name="status" class="form-select @error('status') is-invalid @enderror">
                                    <option value="">-- Pilih Status --</option>
                                    <option value="hadir" {{ old('status') == 'hadir' ? 'selected' : '' }}>Hadir</option>
                                    <option value="terlambat" {{ old('status') == 'terlambat' ? 'selected' : '' }}>Terlambat</option>
                                    <option value="izin" {{ old('status') == 'izin' ? 'selected' : '' }}>Izin</option>
                                    <option value="alpha" {{ old('status') == 'alpha' ? 'selected' : '' }}>Alpha</option>
                                </select>
                                @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">Simpan</button>
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
