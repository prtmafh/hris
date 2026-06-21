@extends('karyawan.layouts.app')

@section('title', 'Detail Karyawan')

@section('content')
<main>
    {{-- HEADER (SUDAH BENAR, TIDAK DIUBAH LOGIC) --}}
    <header class="page-header page-header-compact page-header-light border-bottom bg-white mb-4">
        <div class="container-xl px-4">
            <div class="page-header-content">
                <div class="row align-items-center justify-content-between pt-3">

                    <div class="col-auto mb-3">
                        <h1 class="page-header-title">
                            <div class="page-header-icon"><i data-feather="user"></i></div>
                            Detail Karyawan
                        </h1>
                    </div>

                    <div class="col-auto mb-3">
                        <a href="{{ route('admin.daftar_karyawan') }}" class="btn btn-sm btn-light">
                            <i data-feather="arrow-left"></i> Kembali
                        </a>
                        {{-- <a href="{{ route('admin.karyawan.edit', $karyawan->id) }}"
                            class="btn btn-sm btn-light text-primary ms-1">
                            <i data-feather="edit"></i> Edit
                        </a> --}}
                    </div>

                </div>
            </div>
        </div>
    </header>

    <div class="container-xl px-4 mt-4">

        {{-- NAV SB ADMIN PRO --}}
        {{-- <nav class="nav nav-borders">
            <a class="nav-link active ms-0">Profil</a>
        </nav>

        <hr class="mt-0 mb-4"> --}}

        <div class="row">

            {{-- KIRI --}}
            <div class="col-xl-4">

                <div class="card mb-4 mb-xl-0">
                    <div class="card-header">Foto Profil</div>

                    <div class="card-body text-center">

                        <img class="img-account-profile rounded-circle mb-2"
                            src="{{ $karyawan->foto ? asset('storage/'.$karyawan->foto) : 'https://ui-avatars.com/api/?name='.urlencode($karyawan->nama).'&size=200' }}">

                        <div class="fw-bold fs-5 text-capitalize">{{ $karyawan->nama }}</div>

                        <div class="small text-muted mb-2 text-capitalize">
                            {{ optional($karyawan->jabatan)->nama_jabatan ?? '-' }}
                        </div>

                        @if($karyawan->status === 'aktif')
                        <span class="badge bg-green-soft text-green">Aktif</span>
                        @else
                        <span class="badge bg-red-soft text-red">Nonaktif</span>
                        @endif

                    </div>
                </div>

                {{-- STATUS --}}
                <div class="card mt-4">
                    <div class="card-header">Penilaian Karyawan</div>

                    <div class="card-body">
                        @if($penilaian)
                        @php
                        $gradeColor = ['A'=>'success','B'=>'primary','C'=>'warning','D'=>'danger'][$penilaian->grade] ??
                        'secondary';
                        $gradeLabel = ['A'=>'Sangat Baik','B'=>'Baik','C'=>'Cukup','D'=>'Kurang'][$penilaian->grade] ??
                        '-';
                        @endphp
                        <div class="card mb-4 border-{{ $gradeColor }} border-2">
                            <div class="card-body text-center py-4">
                                <div class="text-muted small mb-1">Nilai Total</div>
                                <div class="display-4 fw-bold text-{{ $gradeColor }}">
                                    {{ number_format($penilaian->nilai_total, 2) }}
                                </div>
                                <div class="progress mx-auto mt-3 mb-3" style="height:12px;max-width:300px;">
                                    <div class="progress-bar bg-{{ $gradeColor }}"
                                        style="width:{{ $penilaian->nilai_total }}%">
                                    </div>
                                </div>
                                <span class="badge bg-{{ $gradeColor }} fs-3 px-4 py-2">{{ $penilaian->grade }}</span>
                                <div class="mt-2 text-{{ $gradeColor }} fw-semibold">{{ $gradeLabel }}</div>
                            </div>
                        </div>
                        @else

                        <div class="alert alert-info">
                            Belum ada data penilaian karyawan.
                        </div>

                        @endif
                    </div>
                </div>

                {{-- RESET PASSWORD --}}
                <div class="card mt-4 mb-4">
                    <div class="card-header">Ubah Password</div>

                    <div class="card-body">

                        <form action="{{ route('karyawan.updatePassword', $karyawan->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label class="small mb-1">Password Baru</label>
                                <input type="password" name="password" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label class="small mb-1">Konfirmasi Password</label>
                                <input type="password" name="password_confirmation" class="form-control" required>
                            </div>

                            <button type="submit" class="btn btn-primary w-100">
                                <i data-feather="lock"></i> Simpan Password
                            </button>

                        </form>

                    </div>
                </div>

            </div>

            {{-- KANAN --}}
            <div class="col-xl-8">

                <div class="card mb-4">
                    <div class="card-header">Detail Akun</div>

                    <div class="card-body">

                        <div class="mb-3">
                            <label class="small mb-1">NIK</label>
                            <input class="form-control" value="{{ $karyawan->nik ?? '-' }}" readonly>
                        </div>

                        <div class="row gx-3 mb-3">

                            <div class="col-md-6">
                                <label class="small mb-1">Nama</label>
                                <input class="form-control text-capitalize" value="{{ $karyawan->nama }}" readonly>
                            </div>

                            <div class="col-md-6">
                                <label class="small mb-1">Jabatan</label>
                                <input class="form-control text-capitalize"
                                    value="{{ optional($karyawan->jabatan)->nama_jabatan ?? '-' }}" readonly>
                            </div>

                        </div>

                        <div class="row gx-3 mb-3">

                            <div class="col-md-6">
                                <label class="small mb-1">Tanggal Lahir</label>
                                <input class="form-control"
                                    value="{{ $karyawan->tgl_lahir ? \Carbon\Carbon::parse($karyawan->tgl_lahir)->format('d M Y') : '-' }}"
                                    readonly>
                            </div>

                            <div class="col-md-6">
                                <label class="small mb-1">Tanggal Masuk</label>
                                <input class="form-control"
                                    value="{{ $karyawan->tgl_masuk ? \Carbon\Carbon::parse($karyawan->tgl_masuk)->format('d M Y') : '-' }}"
                                    readonly>
                            </div>

                        </div>

                        <div class="mb-3">
                            <label class="small mb-1">Alamat</label>
                            <textarea class="form-control" rows="3" readonly>{{ $karyawan->alamat ?? '-' }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label class="small mb-1">No HP</label>
                            <input class="form-control" value="{{ $karyawan->no_hp ?? '-' }}" readonly>
                        </div>

                        <div class="row gx-3 mb-0">

                            <div class="col-md-6">
                                <label class="small mb-1">Jenis Gaji</label>
                                <input class="form-control" value="{{ $karyawan->status_gaji }}" readonly>
                            </div>

                            <div class="col-md-6">
                                <label class="small mb-1">Nominal</label>
                                <input class="form-control"
                                    value="@if($karyawan->status_gaji == 'bulanan') Rp {{ number_format($karyawan->gaji_pokok,0,',','.') }} @else Rp {{ number_format($karyawan->gaji_per_hari,0,',','.') }}/hari @endif"
                                    readonly>
                            </div>

                        </div>

                    </div>
                </div>

            </div>

        </div>
    </div>
</main>

@push('scripts')
<script>
    const statusGaji    = document.getElementById('status_gaji');
    const gajiPokok     = document.getElementById('wrap_gaji_pokok');
    const gajiHarian    = document.getElementById('wrap_gaji_harian');

    statusGaji.addEventListener('change', function () {
        gajiPokok.style.display  = this.value === 'bulanan' ? 'block' : 'none';
        gajiHarian.style.display = this.value === 'harian'  ? 'block' : 'none';
    });

    function confirmResetPassword(event, nama, tanggalLahir) {
        event.preventDefault();

        Swal.fire({
            title: 'Reset kata sandi?',
            text: `Kata sandi ${nama} akan direset ke tanggal lahir ${tanggalLahir}.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, reset',
            cancelButtonText: 'Batal',
            reverseButtons: true,
            customClass: {
                confirmButton: 'btn btn-danger',
                cancelButton: 'btn btn-light'
            },
            buttonsStyling: false
        }).then((result) => {
            if (result.isConfirmed) {
                event.target.submit();
            }
        });

        return false;
    }
</script>
@endpush
@endsection