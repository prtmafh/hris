@extends('admin.layouts.app')

@section('title', 'Tambah Karyawan')
@push('styles')
<style>
    .step {
        flex: 1;
        text-align: center;
        padding: 10px;
        border-radius: 8px;
        background: #f1f3f5;
        font-weight: 500;
        transition: 0.3s;
    }

    .step.active {
        background: #0d6efd;
        color: white;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
    }
</style>
@endpush
@section('content')
<main>
    <header class="page-header page-header-dark bg-gradient-primary-to-secondary pb-10">
        <div class="container-xl px-4">
            <div class="page-header-content pt-4 d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="page-header-title">
                        <div class="page-header-icon"><i data-feather="users"></i></div>
                        Tambah Karyawan
                    </h1>
                    <div class="page-header-subtitle">Manajemen data karyawan</div>
                </div>
            </div>
        </div>
    </header>

    <div class="container-xl px-4 mt-n10">
        <div class="row">
            <div class="col-lg-12">
                <div class="card mb-4 shadow-sm">
                    <div class="card-header fw-bold">
                        <i data-feather="user-plus" class="me-1"></i>
                        Tambah Karyawan
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.karyawan.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <div class="d-flex justify-content-between mb-4">
                                <div class="step active p-3" data-step="1">1. Pribadi</div>
                                <div class="step" data-step="2">2. Pekerjaan</div>
                                <div class="step" data-step="3">3. Gaji</div>
                            </div>

                            <div class="step-content" id="step-1">
                                <h5 class="fw-bold mb-3">Data Pribadi</h5>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-semibold">Nama</label>
                                        <input type="text" name="nama" class="form-control"
                                            placeholder="Masukkan nama lengkap" required>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-semibold">NIK</label>
                                        <input type="text" name="nik" class="form-control"
                                            placeholder="Nomor Induk Karyawan" required>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-semibold">Tanggal Lahir</label>
                                        <input type="date" name="tgl_lahir" class="form-control" required>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-semibold">No HP</label>
                                        <input type="text" name="no_hp" class="form-control" placeholder="08xxxxxxxxxx">
                                    </div>

                                    <div class="col-12 mb-3">
                                        <label class="form-label fw-semibold">Alamat</label>
                                        <textarea name="alamat" class="form-control" rows="2"></textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="step-content d-none" id="step-2">
                                <h5 class="fw-bold mb-3">Data Pekerjaan</h5>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-semibold">Tanggal Masuk</label>
                                        <input type="date" name="tgl_masuk" class="form-control" required>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-semibold">Jabatan</label>
                                        <select name="jabatan_id" class="form-select" required>
                                            <option value="">-- Pilih Jabatan --</option>
                                            @foreach($jabatan as $j)
                                            <option value="{{ $j->id }}">{{ $j->nama_jabatan }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-semibold">Status</label>
                                        <select name="status" class="form-select">
                                            <option value="aktif">Aktif</option>
                                            <option value="nonaktif">Nonaktif</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="step-content d-none" id="step-3">
                                <h5 class="fw-bold mb-3">Penggajian & Foto</h5>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-semibold">Jenis Gaji</label>
                                        <select name="status_gaji" id="status_gaji" class="form-select" required>
                                            <option value="">-- Pilih Jenis Gaji --</option>
                                            <option value="harian">Harian</option>
                                            <option value="bulanan">Bulanan</option>
                                        </select>
                                    </div>

                                    <div class="col-md-6 mb-3" id="wrap_gaji_pokok">
                                        <label class="form-label fw-semibold">Gaji Pokok</label>
                                        <input type="text" name="gaji_pokok" id="gaji_pokok" class="form-control"
                                            inputmode="numeric" placeholder="Masukkan gaji pokok">
                                    </div>

                                    <div class="col-md-6 mb-3" id="wrap_gaji_harian">
                                        <label class="form-label fw-semibold">Gaji Per Hari</label>
                                        <input type="text" name="gaji_per_hari" id="gaji_per_hari" class="form-control"
                                            inputmode="numeric" placeholder="Masukkan gaji per hari">
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-semibold">Foto</label>
                                        <input type="file" name="foto" id="foto" class="form-control">
                                    </div>

                                    <div class="col-12 text-center">
                                        <img id="preview" class="img-thumbnail d-none mt-2 shadow-sm" width="120">
                                    </div>
                                </div>

                                <div class="alert alert-info mt-3">
                                    <i data-feather="info" class="me-1"></i>
                                    Login menggunakan <b>NIK</b> dan password default <b>tanggal lahir</b>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between mt-3">
                                <button type="button" class="btn btn-secondary" id="prevBtn">Kembali</button>

                                <div>
                                    <button type="button" class="btn btn-primary" id="nextBtn">Lanjut</button>
                                    <button type="submit" class="btn btn-success d-none" id="submitBtn">Simpan</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

@endsection

@push('scripts')
<script>
    let currentStep = 1;

    const form = document.querySelector('form[action="{{ route('admin.karyawan.store') }}"]');
    const steps = document.querySelectorAll('.step-content');
    const stepIndicator = document.querySelectorAll('.step');
    const nextBtn = document.getElementById('nextBtn');
    const prevBtn = document.getElementById('prevBtn');
    const submitBtn = document.getElementById('submitBtn');
    const statusGaji = document.getElementById('status_gaji');
    const wrapGajiPokok = document.getElementById('wrap_gaji_pokok');
    const wrapGajiHarian = document.getElementById('wrap_gaji_harian');
    const gajiPokokInput = document.getElementById('gaji_pokok');
    const gajiHarianInput = document.getElementById('gaji_per_hari');
    const fotoInput = document.getElementById('foto');
    const preview = document.getElementById('preview');

    showStep(currentStep);
    toggleGajiFields(statusGaji.value);

    function showStep(step) {
        steps.forEach(stepItem => {
            stepItem.classList.add('d-none');
            stepItem.classList.remove('fade-in');
        });

        const active = document.getElementById('step-' + step);
        active.classList.remove('d-none');
        active.classList.add('fade-in');

        stepIndicator.forEach(indicator => indicator.classList.remove('active'));
        stepIndicator[step - 1].classList.add('active');

        prevBtn.style.display = step === 1 ? 'none' : 'inline-block';
        nextBtn.style.display = step === 3 ? 'none' : 'inline-block';
        submitBtn.classList.toggle('d-none', step !== 3);
    }

    function toggleGajiFields(value) {
        const isBulanan = value === 'bulanan';
        const isHarian = value === 'harian';

        wrapGajiPokok.style.display = isBulanan ? 'block' : 'none';
        wrapGajiHarian.style.display = isHarian ? 'block' : 'none';

        gajiPokokInput.disabled = !isBulanan;
        gajiHarianInput.disabled = !isHarian;

        if (!isBulanan) {
            gajiPokokInput.value = '';
        }

        if (!isHarian) {
            gajiHarianInput.value = '';
        }
    }

    function formatRupiah(input) {
        const value = input.value.replace(/\D/g, '');
        input.value = value ? new Intl.NumberFormat('id-ID').format(value) : '';
    }

    nextBtn.onclick = () => {
        if (currentStep < 3) {
            currentStep++;
            showStep(currentStep);
        }
    };

    prevBtn.onclick = () => {
        if (currentStep > 1) {
            currentStep--;
            showStep(currentStep);
        }
    };

    statusGaji.addEventListener('change', function () {
        toggleGajiFields(this.value);
    });

    gajiPokokInput.addEventListener('input', function () {
        formatRupiah(this);
    });

    gajiHarianInput.addEventListener('input', function () {
        formatRupiah(this);
    });

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

    form.addEventListener('submit', function () {
        gajiPokokInput.value = gajiPokokInput.value.replace(/\D/g, '');
        gajiHarianInput.value = gajiHarianInput.value.replace(/\D/g, '');
    });
</script>
@endpush