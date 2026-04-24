@extends('admin.layouts.app')

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
                        <a href="{{ route('admin.karyawan.edit', $karyawan->id) }}"
                            class="btn btn-sm btn-light text-primary ms-1">
                            <i data-feather="edit"></i> Edit
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </header>

    <div class="container-xl px-4 mt-4">

        {{-- NAV SB ADMIN PRO --}}
        <nav class="nav nav-borders">
            <a class="nav-link active ms-0">Profile</a>
        </nav>

        <hr class="mt-0 mb-4">

        <div class="row">

            {{-- KIRI --}}
            <div class="col-xl-4">

                <div class="card mb-4 mb-xl-0">
                    <div class="card-header">Profile Picture</div>

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
                    <div class="card-header">Manajemen Status</div>

                    <div class="card-body">

                        <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                            <div>
                                <div class="small fw-semibold">Status Karyawan</div>
                                <div class="text-muted small">Status kepegawaian</div>
                            </div>

                            <form action="{{ route('admin.karyawan.toggleKaryawanStatus', $karyawan->id) }}"
                                method="POST">
                                @csrf
                                <div class="form-check form-switch mb-0">
                                    <input class="form-check-input" type="checkbox" {{ $karyawan->status === 'aktif' ?
                                    'checked' : '' }}
                                    onchange="this.form.submit()">
                                </div>
                            </form>
                        </div>

                        <div class="d-flex justify-content-between align-items-center pt-2">
                            <div>
                                <div class="small fw-semibold">Status Login</div>
                                <div class="text-muted small">Akses login</div>
                            </div>

                            <form action="{{ route('admin.karyawan.toggleStatus', $karyawan->id) }}" method="POST">
                                @csrf
                                <div class="form-check form-switch mb-0">
                                    <input class="form-check-input" type="checkbox" {{ optional($karyawan->user)->status
                                    === 'aktif' ? 'checked' : '' }}
                                    onchange="this.form.submit()">
                                </div>
                            </form>
                        </div>

                    </div>
                </div>

                {{-- RESET PASSWORD --}}
                <div class="card mt-4">
                    <div class="card-header">Security</div>

                    <div class="card-body">

                        <div class="small text-muted mb-3">
                            Password akan direset ke:
                            <strong>{{ $karyawan->tgl_lahir ?? '-' }}</strong>
                        </div>

                        <form action="{{ route('admin.karyawan.resetPassword', $karyawan->id) }}" method="POST"
                            onsubmit="return confirmResetPassword(event, '{{ addslashes($karyawan->nama) }}', '{{ $karyawan->tgl_lahir ?? '-' }}')">
                            @csrf

                            <button type="submit" class="btn btn-danger w-100">
                                <i data-feather="refresh-cw"></i> Reset Password
                            </button>

                        </form>

                    </div>
                </div>

            </div>

            {{-- KANAN --}}
            <div class="col-xl-8">

                <div class="card mb-4">
                    <div class="card-header">Account Details</div>

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
            title: 'Reset password?',
            text: `Password ${nama} akan direset ke tanggal lahir ${tanggalLahir}.`,
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