@extends('admin.layouts.app')

@section('title', 'Tambah Absensi Sesi Manual')

@section('content')
<main>
    <header class="page-header page-header-compact page-header-light border-bottom bg-white mb-4">
        <div class="container-xl px-4">
            <div class="page-header-content">
                <div class="row align-items-center justify-content-between pt-3">
                    <div class="col-auto mb-3">
                        <h1 class="page-header-title">
                            <div class="page-header-icon"><i data-feather="plus-circle"></i></div>
                            Tambah Absensi Sesi Manual
                        </h1>
                        {{-- <div class="page-header-subtitle">Input absensi sesi untuk karyawan harian</div> --}}
                    </div>
                    <div class="col-auto mb-3">
                        <a href="{{ route('data_absen', ['type' => 'sesi']) }}" class="btn btn-sm btn-light">
                            <i data-feather="arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div class="container-xl px-4">
        <div class="card mb-4">
            <div class="card-header">Form Absensi Sesi</div>
            <form action="{{ route('karyawan.absensi-sesi.store') }}" method="POST">
                @csrf
                <div class="card-body p-4">
                    <div class="row g-4 mb-4">
                        <div class="col-md-6">
                            <label class="small mb-1">Karyawan Harian <span class="text-danger">*</span></label>
                            <select name="karyawan_id" class="form-select @error('karyawan_id') is-invalid @enderror"
                                required>
                                <option value="">-- Pilih Karyawan --</option>
                                @foreach($karyawanList as $karyawan)
                                <option value="{{ $karyawan->id }}" @selected(old('karyawan_id')==$karyawan->id)>
                                    {{ $karyawan->nama }} ({{ $karyawan->nik }})
                                </option>
                                @endforeach
                            </select>
                            @error('karyawan_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="small mb-1">Tanggal <span class="text-danger">*</span></label>
                            <input type="date" name="tanggal" value="{{ old('tanggal', now()->toDateString()) }}"
                                class="form-control @error('tanggal') is-invalid @enderror" required>
                            @error('tanggal')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="row g-4 mb-4">
                        <div class="col-md-4">
                            <label class="small mb-1">Sesi Ke <span class="text-danger">*</span></label>
                            <select name="sesi_ke" class="form-select @error('sesi_ke') is-invalid @enderror" required>
                                @foreach(range(1, $maxSesi) as $nomor)
                                <option value="{{ $nomor }}" @selected(old('sesi_ke')==$nomor)>Sesi {{ $nomor }}
                                </option>
                                @endforeach
                            </select>
                            @error('sesi_ke')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="small mb-1">Jam Check-in</label>
                            <input type="time" name="jam_checkin" value="{{ old('jam_checkin') }}"
                                class="form-control @error('jam_checkin') is-invalid @enderror">
                            @error('jam_checkin')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="small mb-1">Jam Check-out</label>
                            <input type="time" name="jam_checkout" value="{{ old('jam_checkout') }}"
                                class="form-control @error('jam_checkout') is-invalid @enderror">
                            @error('jam_checkout')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="row g-4">
                        <div class="col-md-4">
                            <label class="small mb-1">Status <span class="text-danger">*</span></label>
                            <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                                @foreach(['hadir' => 'Hadir', 'terlambat' => 'Terlambat', 'izin' => 'Izin', 'alpha' =>
                                'Alpha'] as $value => $label)
                                <option value="{{ $value }}" @selected(old('status', 'hadir' )===$value)>{{ $label }}
                                </option>
                                @endforeach
                            </select>
                            @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-8">
                            <label class="small mb-1">Keterangan</label>
                            <textarea name="keterangan" rows="3" maxlength="1000"
                                class="form-control @error('keterangan') is-invalid @enderror"
                                placeholder="Catatan tambahan (opsional)">{{ old('keterangan') }}</textarea>
                            @error('keterangan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-light d-flex gap-2">
                    <button type="submit" class="btn btn-primary"><i data-feather="save"></i> Simpan</button>
                    <a href="{{ route('data_absen', ['type' => 'sesi']) }}" class="btn btn-light">Batal</a>
                </div>
            </form>
        </div>
    </div>
</main>
@endsection