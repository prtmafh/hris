@extends('admin.layouts.app')

@section('title', 'Detail Karyawan')

@section('content')
<main>
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
                        <a href="{{ route('admin.daftar_karyawan') }}" class="btn btn-sm btn-secondary">
                            <i data-feather="arrow-left"></i> Kembali
                        </a>
                        <a href="{{ route('admin.karyawan.edit', $karyawan->id) }}" class="btn btn-sm btn-warning ms-1">
                            <i data-feather="edit"></i> Edit
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div class="container-xl px-4 mt-4">
        <hr class="mt-0 mb-4">

        <div class="row">

            {{-- Kolom Kiri: Foto --}}
            <div class="col-xl-4">
                <div class="card mb-4 mb-xl-0">
                    <div class="card-header">Foto Profil</div>
                    <div class="card-body text-center">
                        <img src="{{ $karyawan->foto ? asset('storage/'.$karyawan->foto) : 'https://ui-avatars.com/api/?name='.urlencode($karyawan->nama).'&size=200' }}"
                            class="rounded-circle mb-3" style="width: 150px; height: 150px; object-fit: cover;">

                        <div class="fw-bold fs-5 text-capitalize">{{ $karyawan->nama }}</div>
                        <div class="text-muted small mb-2 text-capitalize">{{ optional($karyawan->jabatan)->nama_jabatan
                            ?? '-' }}</div>

                        @if($karyawan->status === 'aktif')
                        <span class="badge bg-success">Aktif</span>
                        @else
                        <span class="badge bg-secondary">Nonaktif</span>
                        @endif
                    </div>
                </div>

                {{-- STATUS TOGGLE --}}
                <div class="card mt-4">
                    <div class="card-header">Manajemen Status</div>
                    <div class="card-body">

                        {{-- Status Karyawan --}}
                        <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                            <div>
                                <div class="fw-semibold small">Status Karyawan</div>
                                <div class="text-muted" style="font-size:.78rem;">Status kepegawaian karyawan</div>
                            </div>
                            <form action="{{ route('admin.karyawan.toggleKaryawanStatus', $karyawan->id) }}"
                                method="POST">
                                @csrf
                                <div class="form-check form-switch mb-0">
                                    <input class="form-check-input" type="checkbox" role="switch"
                                        style="width:2.5em; height:1.3em; cursor:pointer;" {{ $karyawan->status ===
                                    'aktif' ? 'checked' : '' }}
                                    onchange="this.form.submit()">
                                </div>
                            </form>
                        </div>

                        {{-- Status Akun (Login) --}}
                        <div class="d-flex justify-content-between align-items-center pt-2">
                            <div>
                                <div class="fw-semibold small">Status Akun Login</div>
                                <div class="text-muted" style="font-size:.78rem;">Nonaktif = tidak bisa login</div>
                            </div>
                            <form action="{{ route('admin.karyawan.toggleStatus', $karyawan->id) }}" method="POST">
                                @csrf
                                <div class="form-check form-switch mb-0">
                                    <input class="form-check-input" type="checkbox" role="switch"
                                        style="width:2.5em; height:1.3em; cursor:pointer;" {{
                                        optional($karyawan->user)->status === 'aktif' ? 'checked' : '' }}
                                    onchange="this.form.submit()">
                                </div>
                            </form>
                        </div>

                    </div>
                </div>
            </div>

            {{-- Kolom Kanan: Info Detail --}}
            <div class="col-xl-8">
                <div class="card mb-4">
                    <div class="card-header">Informasi Karyawan</div>
                    <div class="card-body">

                        <div class="mb-3">
                            <label class="small mb-1 text-muted">NIK</label>
                            <div class="form-control bg-light">{{ $karyawan->nik ?? '-' }}</div>
                        </div>

                        <div class="row gx-3 mb-3">
                            <div class="col-md-6">
                                <label class="small mb-1 text-muted">Nama Lengkap</label>
                                <div class="form-control bg-light text-capitalize">{{ $karyawan->nama }}</div>
                            </div>
                            <div class="col-md-6">
                                <label class="small mb-1 text-muted">Jabatan</label>
                                <div class="form-control bg-light text-capitalize">{{
                                    optional($karyawan->jabatan)->nama_jabatan ?? '-'
                                    }}</div>
                            </div>
                        </div>

                        <div class="row gx-3 mb-3">
                            <div class="col-md-6">
                                <label class="small mb-1 text-muted">Tanggal Lahir</label>
                                <div class="form-control bg-light">
                                    {{ $karyawan->tgl_lahir ? \Carbon\Carbon::parse($karyawan->tgl_lahir)->format('d M
                                    Y') : '-' }}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="small mb-1 text-muted">Tanggal Masuk</label>
                                <div class="form-control bg-light">
                                    {{ $karyawan->tgl_masuk ? \Carbon\Carbon::parse($karyawan->tgl_masuk)->format('d M
                                    Y') : '-' }}
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="small mb-1 text-muted">Alamat</label>
                            <div class="form-control bg-light" style="min-height: 60px;">{{ $karyawan->alamat ?? '-' }}
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="small mb-1 text-muted">No. HP</label>
                            <div class="form-control bg-light">{{ $karyawan->no_hp ?? '-' }}</div>
                        </div>

                        <div class="row gx-3 mb-0">
                            <div class="col-md-6">
                                <label class="small mb-1 text-muted">Jenis Gaji</label>
                                <div class="form-control bg-light">
                                    @if($karyawan->status_gaji == 'harian')
                                    <span class="badge bg-info">Harian</span>
                                    @else
                                    <span class="badge bg-primary">Bulanan</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="small mb-1 text-muted">Nominal Gaji</label>
                                <div class="form-control bg-light">
                                    @if($karyawan->status_gaji == 'bulanan')
                                    Rp {{ number_format($karyawan->gaji_pokok, 0, ',', '.') }}
                                    @else
                                    Rp {{ number_format($karyawan->gaji_per_hari, 0, ',', '.') }} / hari
                                    @endif
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>
</main>
{{-- MODAL EDIT --}}
<div class="modal fade" id="modalEditKaryawan" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('admin.karyawan.update', $karyawan->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i data-feather="edit"></i> Edit Karyawan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <div class="mb-3">
                        <label class="small mb-1 text-muted">Nama</label>
                        <input type="text" name="nama" class="form-control text-capitalize"
                            value="{{ $karyawan->nama }}">
                    </div>

                    <div class="mb-3">
                        <label class="small mb-1 text-muted">NIK</label>
                        <input type="text" name="nik" class="form-control" value="{{ $karyawan->nik }}">
                    </div>

                    <div class="row gx-3 mb-3">
                        <div class="col-md-6">
                            <label class="small mb-1 text-muted">Tanggal Lahir</label>
                            <input type="date" name="tgl_lahir" class="form-control" value="{{ $karyawan->tgl_lahir }}">
                        </div>
                        <div class="col-md-6">
                            <label class="small mb-1 text-muted">Tanggal Masuk</label>
                            <input type="date" name="tgl_masuk" class="form-control" value="{{ $karyawan->tgl_masuk }}">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="small mb-1 text-muted">Alamat</label>
                        <textarea name="alamat" class="form-control" rows="3">{{ $karyawan->alamat }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label class="small mb-1 text-muted">No. HP</label>
                        <input type="text" name="no_hp" class="form-control" value="{{ $karyawan->no_hp }}">
                    </div>

                    <div class="mb-3">
                        <label class="small mb-1 text-muted">Jabatan</label>
                        <select name="jabatan_id" class="form-control">
                            @foreach($jabatan as $j)
                            <option value="{{ $j->id }}" {{ $karyawan->jabatan_id == $j->id ? 'selected' : '' }}>
                                {{ $j->nama_jabatan }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="small mb-1 text-muted">Jenis Gaji</label>
                        <select name="status_gaji" id="status_gaji" class="form-control">
                            <option value="bulanan" {{ $karyawan->status_gaji == 'bulanan' ? 'selected' : '' }}>Bulanan
                            </option>
                            <option value="harian" {{ $karyawan->status_gaji == 'harian' ? 'selected' : '' }}>Harian
                            </option>
                        </select>
                    </div>

                    <div class="mb-3" id="wrap_gaji_pokok"
                        style="{{ $karyawan->status_gaji == 'bulanan' ? '' : 'display:none;' }}">
                        <label class="small mb-1 text-muted">Gaji Pokok (Bulanan)</label>
                        <input type="number" name="gaji_pokok" class="form-control" value="{{ $karyawan->gaji_pokok }}">
                    </div>

                    <div class="mb-3" id="wrap_gaji_harian"
                        style="{{ $karyawan->status_gaji == 'harian' ? '' : 'display:none;' }}">
                        <label class="small mb-1 text-muted">Gaji Per Hari</label>
                        <input type="number" name="gaji_per_hari" class="form-control"
                            value="{{ $karyawan->gaji_per_hari }}">
                    </div>

                    <div class="mb-0">
                        <label class="small mb-1 text-muted">Foto</label>
                        <input type="file" name="foto" class="form-control">
                        <div class="small text-muted mt-1">Kosongkan jika tidak ingin mengubah foto</div>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </div>
        </form>
    </div>
</div>
@push('scripts')
<script>
    const statusGaji    = document.getElementById('status_gaji');
    const gajiPokok     = document.getElementById('wrap_gaji_pokok');
    const gajiHarian    = document.getElementById('wrap_gaji_harian');

    statusGaji.addEventListener('change', function () {
        gajiPokok.style.display  = this.value === 'bulanan' ? 'block' : 'none';
        gajiHarian.style.display = this.value === 'harian'  ? 'block' : 'none';
    });
</script>
@endpush
@endsection