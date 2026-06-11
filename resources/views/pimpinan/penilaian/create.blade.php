@extends('pimpinan.layouts.app')

@section('title', 'Beri Penilaian')

@section('content')
<main>

    <header class="page-header page-header-compact page-header-light border-bottom bg-white mb-4 ">
        <div class="container-fluid px-4">
            <div class="page-header-content">
                <div class="row align-items-center justify-content-between pt-3">

                    <div class="col-auto mb-3">
                        <h1 class="page-header-title">
                            <div class="page-header-icon">
                                <i data-feather="star"></i>
                            </div>
                            Penilaian Karyawan
                        </h1>
                    </div>

                    <div class="col-12 col-xl-auto mb-3">
                        <a href="{{ route('pimpinan.penilaian.index') }}" class="btn btn-sm btn-light border">
                            <i data-feather="arrow-left"></i>
                            Kembali
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </header>


    <div class="container-fluid px-4">

        @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form action="{{ route('pimpinan.penilaian.store') }}" method="POST">
            @csrf

            <div class="row">

                {{-- FORM --}}
                <div class="col-xl-8">

                    <div class="card shadow-sm mb-4">

                        <div class="card-header">
                            <i data-feather="user" class="me-2"></i>
                            Informasi Penilaian
                        </div>

                        <div class="card-body">

                            <div class="row g-3">

                                <div class="col-md-8">
                                    <label class="form-label fw-semibold">
                                        Karyawan
                                    </label>

                                    <select name="karyawan_id" class="form-select" required>

                                        <option value="">
                                            -- Pilih Karyawan --
                                        </option>

                                        @foreach($karyawan as $k)
                                        <option value="{{ $k->id }}" data-nama="{{ $k->nama }}"
                                            data-jabatan="{{ $k->jabatan->nama_jabatan ?? '-' }}" {{
                                            old('karyawan_id')==$k->id ? 'selected' : '' }}
                                            {{ in_array($k->id,$sudahDinilai) ? 'disabled' : '' }}>

                                            {{ $k->nama }}
                                            ({{ $k->jabatan->nama_jabatan ?? '-' }})

                                            {{ in_array($k->id,$sudahDinilai) ? ' - Sudah Dinilai' : '' }}
                                        </option>
                                        @endforeach

                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">
                                        Tahun
                                    </label>

                                    <select name="periode_tahun" class="form-select">

                                        @foreach($tahunList as $t)
                                        <option value="{{ $t }}" {{ old('periode_tahun',$tahun)==$t ? 'selected' : ''
                                            }}>
                                            {{ $t }}
                                        </option>
                                        @endforeach

                                    </select>
                                </div>

                            </div>

                        </div>

                    </div>

                    {{-- KPI --}}
                    <div class="row">

                        <div class="col-md-4">

                            <div class="card border-start-success border-4 shadow-sm h-100">

                                <div class="card-body text-center">

                                    <div class="mb-2 text-success">
                                        <i data-feather="check-circle"></i>
                                    </div>

                                    <div class="text-muted small">
                                        Kehadiran
                                    </div>

                                    <h1 class="fw-bold text-success mb-0" id="nilai_kehadiran_preview">
                                        0
                                    </h1>

                                    <div class="small text-muted">
                                        Bobot 40%
                                    </div>

                                    <input type="hidden" id="nilai_kehadiran" value="0">
                                </div>

                            </div>

                        </div>

                        <div class="col-md-4">

                            <div class="card border-start-warning border-4 shadow-sm h-100">

                                <div class="card-body">

                                    <div class="d-flex justify-content-between">
                                        <span>Kedisiplinan</span>
                                        <strong id="nilaiDisiplinLabel">
                                            80
                                        </strong>
                                    </div>

                                    <input type="range" min="0" max="100" value="{{ old('nilai_kedisiplinan',80) }}"
                                        class="form-range mt-3" id="nilai_kedisiplinan_range">

                                    <input type="hidden" name="nilai_kedisiplinan" id="nilai_kedisiplinan"
                                        value="{{ old('nilai_kedisiplinan',80) }}">

                                    <div class="small text-muted">
                                        Bobot 30%
                                    </div>

                                </div>

                            </div>

                        </div>

                        <div class="col-md-4">

                            <div class="card border-start-primary border-4 shadow-sm h-100">

                                <div class="card-body">

                                    <div class="d-flex justify-content-between">
                                        <span>Kinerja</span>
                                        <strong id="nilaiKinerjaLabel">
                                            80
                                        </strong>
                                    </div>

                                    <input type="range" min="0" max="100" value="{{ old('nilai_kinerja',80) }}"
                                        class="form-range mt-3" id="nilai_kinerja_range">

                                    <input type="hidden" name="nilai_kinerja" id="nilai_kinerja"
                                        value="{{ old('nilai_kinerja',80) }}">

                                    <div class="small text-muted">
                                        Bobot 30%
                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                    {{-- CATATAN --}}
                    <div class="card shadow-sm mt-4">

                        <div class="card-header">
                            Catatan Penilaian
                        </div>

                        <div class="card-body">

                            <textarea name="catatan" rows="5" class="form-control"
                                placeholder="Tambahkan catatan penilaian...">{{ old('catatan') }}</textarea>

                        </div>

                    </div>

                </div>

                {{-- SUMMARY --}}
                <div class="col-xl-4">

                    <div class="card shadow sticky-top" style="top:90px;">

                        <div class="card-body">

                            <div class="text-center">

                                <div class="small text-muted mb-2">
                                    TOTAL NILAI
                                </div>

                                <div id="previewTotal" class="display-2 fw-bold text-primary">
                                    0
                                </div>

                                <span id="previewGrade" class="badge bg-secondary fs-5 px-4 py-2">
                                    -
                                </span>

                            </div>

                            <hr>

                            <div class="mb-2 d-flex justify-content-between">
                                <span>Progress Penilaian</span>
                                <span id="persenTotal">
                                    0%
                                </span>
                            </div>

                            <div class="progress" style="height:20px">

                                <div id="progressTotal" class="progress-bar" style="width:0%">
                                </div>

                            </div>

                            <div id="keteranganGrade" class="alert alert-light mt-4 mb-0">

                                Menunggu penilaian.

                            </div>

                            <hr>

                            <div class="d-grid">

                                <button type="submit" class="btn btn-primary btn-lg">

                                    <i data-feather="save"></i>
                                    Simpan Penilaian

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
    const nilaiKehadiranMap = @json($nilaiKehadiranMap);

function ambilNilaiKehadiran()
{
    const karyawanId =
        document.querySelector('[name="karyawan_id"]').value;

    const tahun =
        document.querySelector('[name="periode_tahun"]').value;

    let nilai = 0;

    if(
        nilaiKehadiranMap[karyawanId] &&
        nilaiKehadiranMap[karyawanId][tahun]
    ){
        nilai =
            parseFloat(
                nilaiKehadiranMap[karyawanId][tahun]
            );
    }

    document.getElementById(
        'nilai_kehadiran_preview'
    ).innerHTML =
        nilai.toFixed(0);

    document.getElementById(
        'nilai_kehadiran'
    ).value =
        nilai;

    return nilai;
}

function hitungTotal()
{
    const kehadiran =
        ambilNilaiKehadiran();

    const disiplin =
        parseFloat(
            document.getElementById(
                'nilai_kedisiplinan'
            ).value
        ) || 0;

    const kinerja =
        parseFloat(
            document.getElementById(
                'nilai_kinerja'
            ).value
        ) || 0;

    const total =
        (kehadiran * 0.4) +
        (disiplin * 0.3) +
        (kinerja * 0.3);

    document.getElementById(
        'previewTotal'
    ).innerHTML =
        total.toFixed(2);

    document.getElementById(
        'persenTotal'
    ).innerHTML =
        total.toFixed(0) + '%';

    document.getElementById(
        'progressTotal'
    ).style.width =
        total + '%';

    let grade='D';
    let color='danger';
    let text='Membutuhkan pembinaan.';

    if(total >= 90){
        grade='A';
        color='success';
        text='Kinerja sangat baik dan layak menjadi role model.';
    }
    else if(total >= 75){
        grade='B';
        color='primary';
        text='Kinerja baik dan memenuhi target perusahaan.';
    }
    else if(total >= 60){
        grade='C';
        color='warning';
        text='Perlu peningkatan pada beberapa aspek.';
    }

    document.getElementById(
        'previewGrade'
    ).className =
        'badge bg-' + color + ' fs-5 px-4 py-2';

    document.getElementById(
        'previewGrade'
    ).innerHTML =
        grade;

    document.getElementById(
        'progressTotal'
    ).className =
        'progress-bar bg-' + color;

    document.getElementById(
        'keteranganGrade'
    ).className =
        'alert alert-' + color + ' mt-4 mb-0';

    document.getElementById(
        'keteranganGrade'
    ).innerHTML =
        text;
}

document
.getElementById('nilai_kedisiplinan_range')
.addEventListener('input', function(){

    document.getElementById(
        'nilaiDisiplinLabel'
    ).innerHTML =
        this.value;

    document.getElementById(
        'nilai_kedisiplinan'
    ).value =
        this.value;

    hitungTotal();
});

document
.getElementById('nilai_kinerja_range')
.addEventListener('input', function(){

    document.getElementById(
        'nilaiKinerjaLabel'
    ).innerHTML =
        this.value;

    document.getElementById(
        'nilai_kinerja'
    ).value =
        this.value;

    hitungTotal();
});

document.querySelector('[name="karyawan_id"]')
.addEventListener('change', hitungTotal);

document.querySelector('[name="periode_tahun"]')
.addEventListener('change', hitungTotal);

hitungTotal();

</script>
@endpush