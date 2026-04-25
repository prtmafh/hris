@extends('admin.layouts.app')

@section('title', 'Edit Absensi Sesi')

@section('content')
<main>
    <header class="page-header page-header-dark bg-gradient-primary-to-secondary pb-10">
        <div class="container-xl px-4">
            <div class="page-header-content pt-4 d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="page-header-title">
                        <div class="page-header-icon"><i data-feather="edit"></i></div>
                        Edit Absensi Sesi
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
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card mb-4">
                    <div class="card-header">Detail Sesi {{ $sesi->sesi_ke }}</div>
                    <div class="card-body">
                        <form action="{{ route('admin.absensi-sesi.update', $sesi->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            {{-- INFO KARYAWAN --}}
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <label for="karyawan_nama" class="form-label">Nama Karyawan</label>
                                    <input type="text" class="form-control" id="karyawan_nama"
                                        value="{{ $sesi->absensi->karyawan->nama }}" disabled>
                                </div>
                                <div class="col-md-6">
                                    <label for="tanggal" class="form-label">Tanggal</label>
                                    <input type="text" class="form-control" id="tanggal"
                                        value="{{ $sesi->absensi->tanggal->format('d F Y') }}" disabled>
                                </div>
                            </div>

                            {{-- JAM CHECK IN & CHECK OUT --}}
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <label for="jam_checkin" class="form-label">Jam Check In</label>
                                    <input type="time" name="jam_checkin"
                                        class="form-control @error('jam_checkin') is-invalid @enderror"
                                        value="{{ $sesi->jam_checkin }}" placeholder="HH:MM">
                                    @error('jam_checkin')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="jam_checkout" class="form-label">Jam Check Out</label>
                                    <input type="time" name="jam_checkout"
                                        class="form-control @error('jam_checkout') is-invalid @enderror"
                                        value="{{ $sesi->jam_checkout }}" placeholder="HH:MM">
                                    @error('jam_checkout')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- STATUS --}}
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <label for="status" class="form-label">Status <span
                                            class="text-danger">*</span></label>
                                    <select name="status" class="form-select @error('status') is-invalid @enderror"
                                        required>
                                        <option value="">Pilih Status</option>
                                        <option value="hadir" {{ $sesi->status === 'hadir' ? 'selected' : '' }}>Hadir
                                        </option>
                                        <option value="terlambat" {{ $sesi->status === 'terlambat' ? 'selected' : ''
                                            }}>Terlambat</option>
                                        <option value="izin" {{ $sesi->status === 'izin' ? 'selected' : '' }}>Izin
                                        </option>
                                        <option value="alpha" {{ $sesi->status === 'alpha' ? 'selected' : '' }}>Alpha
                                        </option>
                                    </select>
                                    @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="sesi_ke" class="form-label">Sesi Ke</label>
                                    <input type="text" class="form-control" id="sesi_ke" value="{{ $sesi->sesi_ke }}"
                                        disabled>
                                </div>
                            </div>

                            {{-- KETERANGAN --}}
                            <div class="mb-4">
                                <label for="keterangan" class="form-label">Keterangan</label>
                                <textarea name="keterangan"
                                    class="form-control @error('keterangan') is-invalid @enderror" rows="3"
                                    placeholder="Catatan tambahan (opsional)">{{ $sesi->keterangan }}</textarea>
                                @error('keterangan')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- TOMBOL --}}
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i data-feather="save"></i> Simpan
                                </button>
                                <a href="{{ route('data_absen', ['type' => 'sesi']) }}"
                                    class="btn btn-outline-secondary">
                                    <i data-feather="x"></i> Batal
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection