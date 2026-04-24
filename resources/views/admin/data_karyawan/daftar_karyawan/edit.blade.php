@extends('admin.layouts.app')

@section('title', 'Edit Karyawan')

@section('content')
<main>

    {{-- HEADER --}}
    <header class="page-header page-header-compact page-header-light border-bottom bg-white mb-4">
        <div class="container-xl px-4">
            <div class="page-header-content">
                <div class="row align-items-center justify-content-between pt-3">

                    <div class="col-auto mb-3">
                        <h1 class="page-header-title">
                            <div class="page-header-icon"><i data-feather="edit"></i></div>
                            Edit Karyawan
                        </h1>
                    </div>

                    <div class="col-auto mb-3">
                        <button type="button" class="btn btn-sm btn-light" onclick="history.back()">
                            <i data-feather="arrow-left"></i> Kembali
                        </button>
                    </div>

                </div>
            </div>
        </div>
    </header>

    <div class="container-xl px-4 mt-4">

        {{-- NAV SB PRO --}}
        <nav class="nav nav-borders">
            <a class="nav-link active ms-0">Edit Profile</a>
        </nav>

        <hr class="mt-0 mb-4">

        <form action="{{ route('admin.karyawan.update', $karyawan->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row">

                {{-- KIRI (FOTO) --}}
                <div class="col-xl-4">
                    <div class="card mb-4 mb-xl-0">

                        <div class="card-header">Profile Picture</div>

                        <div class="card-body text-center">

                            <img id="preview_foto" class="img-account-profile rounded-circle mb-2"
                                src="{{ $karyawan->foto ? asset('storage/'.$karyawan->foto) : 'https://ui-avatars.com/api/?name='.urlencode($karyawan->nama).'&size=200' }}">

                            <div class="small text-muted mb-3">
                                JPG atau PNG, maksimal 2 MB
                            </div>

                            <input type="file" name="foto" id="input_foto" class="form-control" accept="image/*">

                            <div class="small text-muted mt-1">
                                Kosongkan jika tidak ingin mengubah foto
                            </div>

                        </div>
                    </div>
                </div>

                {{-- KANAN (FORM) --}}
                <div class="col-xl-8">

                    <div class="card mb-4">

                        <div class="card-header">Account Details</div>

                        <div class="card-body">

                            <div class="mb-3">
                                <label class="small mb-1">Nama Lengkap</label>
                                <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror"
                                    value="{{ old('nama', $karyawan->nama) }}">
                                @error('nama')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="small mb-1">NIK</label>
                                <input type="text" name="nik" class="form-control @error('nik') is-invalid @enderror"
                                    value="{{ old('nik', $karyawan->nik) }}">
                                @error('nik')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row gx-3 mb-3">

                                <div class="col-md-6">
                                    <label class="small mb-1">Tanggal Lahir</label>
                                    <input type="date" name="tgl_lahir"
                                        class="form-control @error('tgl_lahir') is-invalid @enderror"
                                        value="{{ old('tgl_lahir', $karyawan->tgl_lahir) }}">
                                    @error('tgl_lahir')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="small mb-1">Tanggal Masuk</label>
                                    <input type="date" name="tgl_masuk"
                                        class="form-control @error('tgl_masuk') is-invalid @enderror"
                                        value="{{ old('tgl_masuk', $karyawan->tgl_masuk) }}">
                                    @error('tgl_masuk')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                            </div>

                            <div class="mb-3">
                                <label class="small mb-1">Alamat</label>
                                <textarea name="alamat" class="form-control @error('alamat') is-invalid @enderror"
                                    rows="3">{{ old('alamat', $karyawan->alamat) }}</textarea>
                                @error('alamat')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="small mb-1">No. HP</label>
                                <input type="text" name="no_hp"
                                    class="form-control @error('no_hp') is-invalid @enderror"
                                    value="{{ old('no_hp', $karyawan->no_hp) }}">
                                @error('no_hp')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="small mb-1">Jabatan</label>
                                <select name="jabatan_id"
                                    class="form-control @error('jabatan_id') is-invalid @enderror">
                                    @foreach($jabatan as $j)
                                    <option value="{{ $j->id }}" {{ old('jabatan_id', $karyawan->jabatan_id) == $j->id ?
                                        'selected' : '' }}>
                                        {{ $j->nama_jabatan }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('jabatan_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="small mb-1">Jenis Gaji</label>
                                <select name="status_gaji" id="status_gaji" class="form-control">
                                    <option value="bulanan" {{ old('status_gaji', $karyawan->status_gaji) == 'bulanan' ?
                                        'selected' : '' }}>
                                        Bulanan
                                    </option>
                                    <option value="harian" {{ old('status_gaji', $karyawan->status_gaji) == 'harian' ?
                                        'selected' : '' }}>
                                        Harian
                                    </option>
                                </select>
                            </div>

                            <div class="mb-3" id="wrap_gaji_pokok">
                                <label class="small mb-1">Gaji Pokok (Bulanan)</label>
                                <input type="number" name="gaji_pokok" class="form-control"
                                    value="{{ old('gaji_pokok', $karyawan->gaji_pokok) }}">
                            </div>

                            <div class="mb-3" id="wrap_gaji_harian">
                                <label class="small mb-1">Gaji Per Hari</label>
                                <input type="number" name="gaji_per_hari" class="form-control"
                                    value="{{ old('gaji_per_hari', $karyawan->gaji_per_hari) }}">
                            </div>

                            {{-- ACTION --}}
                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('admin.karyawan.show', $karyawan->id) }}" class="btn btn-light">
                                    Batal
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    Simpan Perubahan
                                </button>
                            </div>
                        </div>
                    </div>


                </div>
            </div>

        </form>

    </div>
</main>
@endsection

@push('scripts')
<script>
    // ====== JS ASLI (TIDAK DIUBAH) ======

    const statusGaji  = document.getElementById('status_gaji');
    const gajiPokok   = document.getElementById('wrap_gaji_pokok');
    const gajiHarian  = document.getElementById('wrap_gaji_harian');

    function toggleGaji() {
        gajiPokok.style.display  = statusGaji.value === 'bulanan' ? 'block' : 'none';
        gajiHarian.style.display = statusGaji.value === 'harian'  ? 'block' : 'none';
    }

    toggleGaji();
    statusGaji.addEventListener('change', toggleGaji);

    document.getElementById('input_foto').addEventListener('change', function () {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = e => document.getElementById('preview_foto').src = e.target.result;
            reader.readAsDataURL(file);
        }
    });
</script>
@endpush