@extends('admin.layouts.app')

@section('title', 'Tambah Karyawan')

@section('content')
<main>

    {{-- HEADER --}}
    <header class="page-header page-header-compact page-header-light border-bottom bg-white mb-4">
        <div class="container-xl px-4">
            <div class="page-header-content">
                <div class="row align-items-center justify-content-between pt-3">

                    <div class="col-auto mb-3">
                        <h1 class="page-header-title">
                            <div class="page-header-icon"><i data-feather="user-plus"></i></div>
                            Tambah Karyawan
                        </h1>
                    </div>

                    <div class="col-auto mb-3">
                        <a href="{{ route('admin.daftar_karyawan') }}" class="btn btn-sm btn-light">
                            <i data-feather="arrow-left"></i> Kembali
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </header>

    <div class="container-xl px-4 mt-4">

        {{-- NAV --}}
        <nav class="nav nav-borders">
            <a class="nav-link active ms-0">Create Employee</a>
        </nav>

        <hr class="mt-0 mb-4">

        <form id="formTambahKaryawan" action="{{ route('admin.karyawan.store') }}" method="POST"
            enctype="multipart/form-data">
            @csrf

            <div class="row">

                {{-- LEFT --}}
                <div class="col-xl-4">
                    <div class="card mb-4 mb-xl-0">
                        <div class="card-header">Profile Picture</div>
                        <div class="card-body text-center">

                            <img id="preview" class="img-account-profile rounded-circle mb-2 d-none" width="120">

                            <div class="small text-muted mb-3">
                                JPG atau PNG maksimal 2 MB
                            </div>

                            <input type="file" name="foto" id="foto" class="form-control">

                        </div>
                    </div>
                </div>

                {{-- RIGHT --}}
                <div class="col-xl-8">

                    <div class="card mb-4">
                        <div class="card-header">Account Details</div>

                        <div class="card-body">

                            {{-- DATA PRIBADI --}}
                            <div class="row gx-3 mb-3">

                                <div class="col-md-6">
                                    <label class="small mb-1">Nama</label>
                                    <input type="text" name="nama" class="form-control" required>
                                </div>

                                <div class="col-md-6">
                                    <label class="small mb-1">NIK</label>
                                    <input type="text" name="nik" class="form-control" required>
                                </div>

                            </div>

                            <div class="row gx-3 mb-3">

                                <div class="col-md-6">
                                    <label class="small mb-1">Tanggal Lahir</label>
                                    <input type="date" name="tgl_lahir" class="form-control" required>
                                </div>

                                <div class="col-md-6">
                                    <label class="small mb-1">No HP</label>
                                    <input type="text" name="no_hp" class="form-control">
                                </div>

                            </div>

                            <div class="mb-3">
                                <label class="small mb-1">Alamat</label>
                                <textarea name="alamat" class="form-control"></textarea>
                            </div>

                            {{-- PEKERJAAN --}}
                            <div class="row gx-3 mb-3">

                                <div class="col-md-6">
                                    <label class="small mb-1">Tanggal Masuk</label>
                                    <input type="date" name="tgl_masuk" class="form-control" required>
                                </div>

                                <div class="col-md-6">
                                    <label class="small mb-1">Jabatan</label>
                                    <select name="jabatan_id" class="form-select" required>
                                        <option value="">-- Pilih Jabatan --</option>
                                        @foreach($jabatan as $j)
                                        <option value="{{ $j->id }}">{{ $j->nama_jabatan }}</option>
                                        @endforeach
                                    </select>
                                </div>

                            </div>

                            <div class="mb-3">
                                <label class="small mb-1">Status</label>
                                <select name="status" class="form-select">
                                    <option value="aktif">Aktif</option>
                                    <option value="nonaktif">Nonaktif</option>
                                </select>
                            </div>

                            {{-- GAJI --}}
                            <div class="row gx-3 mb-3">

                                <div class="col-md-6">
                                    <label class="small mb-1">Jenis Gaji</label>
                                    <select name="status_gaji" id="status_gaji" class="form-select" required>
                                        <option value="">-- Pilih Jenis Gaji --</option>
                                        <option value="harian">Harian</option>
                                        <option value="bulanan">Bulanan</option>
                                    </select>
                                </div>

                                <div class="col-md-6" id="wrap_gaji_pokok">
                                    <label class="small mb-1">Gaji Pokok</label>
                                    <input type="text" name="gaji_pokok" id="gaji_pokok" class="form-control">
                                </div>

                                <div class="col-md-6" id="wrap_gaji_harian">
                                    <label class="small mb-1">Gaji Harian</label>
                                    <input type="text" name="gaji_per_hari" id="gaji_per_hari" class="form-control">
                                </div>

                            </div>

                            <div class="alert alert-info">
                                Login menggunakan <b>NIK</b> dan password <b>tanggal lahir</b>
                            </div>

                        </div>
                    </div>

                    {{-- ACTION --}}
                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('admin.daftar_karyawan') }}" class="btn btn-light">Batal</a>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>

                </div>

            </div>

        </form>
    </div>
</main>
@endsection
@push('scripts')
<script>
    // ====== TANPA STEP ======

const statusGaji = document.getElementById('status_gaji');
const wrapGajiPokok = document.getElementById('wrap_gaji_pokok');
const wrapGajiHarian = document.getElementById('wrap_gaji_harian');
const gajiPokokInput = document.getElementById('gaji_pokok');
const gajiHarianInput = document.getElementById('gaji_per_hari');
const fotoInput = document.getElementById('foto');
const preview = document.getElementById('preview');

// toggle gaji
function toggleGajiFields(value) {
    const isBulanan = value === 'bulanan';
    const isHarian = value === 'harian';

    wrapGajiPokok.style.display = isBulanan ? 'block' : 'none';
    wrapGajiHarian.style.display = isHarian ? 'block' : 'none';

    gajiPokokInput.disabled = !isBulanan;
    gajiHarianInput.disabled = !isHarian;

    if (!isBulanan) gajiPokokInput.value = '';
    if (!isHarian) gajiHarianInput.value = '';
}

statusGaji.addEventListener('change', function () {
    toggleGajiFields(this.value);
});

toggleGajiFields(statusGaji.value);

// format rupiah
function formatRupiah(input) {
    const value = input.value.replace(/\D/g, '');
    input.value = value ? new Intl.NumberFormat('id-ID').format(value) : '';
}

gajiPokokInput.addEventListener('input', function () {
    formatRupiah(this);
});

gajiHarianInput.addEventListener('input', function () {
    formatRupiah(this);
});

// preview foto
fotoInput.addEventListener('change', function (e) {
    const file = e.target.files[0];

    if (!file) {
        preview.classList.add('d-none');
        preview.removeAttribute('src');
        return;
    }

    const reader = new FileReader();
    reader.onload = function () {
        preview.src = reader.result;
        preview.classList.remove('d-none');
    };
    reader.readAsDataURL(file);
});

// submit bersihin angka
const formTambahKaryawan = document.getElementById('formTambahKaryawan');
if (formTambahKaryawan) {
    formTambahKaryawan.addEventListener('submit', function () {
        gajiPokokInput.value = gajiPokokInput.value.replace(/\D/g, '');
        gajiHarianInput.value = gajiHarianInput.value.replace(/\D/g, '');
    });
}

</script>
@endpush