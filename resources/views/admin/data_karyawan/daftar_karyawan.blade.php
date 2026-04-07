@extends('admin.layouts.app')

@section('title', 'Daftar Karyawan')

@section('content')
<main>
    <header class="page-header page-header-dark bg-gradient-primary-to-secondary pb-10">
        <div class="container-xl px-4">
            <div class="page-header-content pt-4 d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="page-header-title">
                        <div class="page-header-icon"><i data-feather="users"></i></div>
                        Daftar Karyawan
                    </h1>
                    <div class="page-header-subtitle">Manajemen data karyawan</div>
                </div>
                {{--
                <button class="btn btn-white btn-sm" data-bs-toggle="modal" data-bs-target="#modalTambahKaryawan">
                    <i data-feather="plus"></i> Tambah Karyawan
                </button> --}}
                <a href="{{ route('admin.karyawan.create') }}" class="btn btn-white btn-sm">
                    <i data-feather="plus"></i> Tambah Karyawan
                </a>
            </div>
        </div>
    </header>

    <div class="container-xl px-4 mt-n10">
        <div class="row">{{-- TABLE --}}
            <div class="col-lg-12">
                <div class="card mb-4">
                    <div class="card-header">Data Karyawan</div>
                    <div class="card-body">
                        <table id="datatablesSimple" class="table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Karyawan</th>
                                    <th>Jabatan</th>
                                    <th>Status Gaji</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($karyawan as $index => $k)
                                <tr>
                                    <td>{{ $index + 1 }}</td>

                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="{{ $k->foto ? asset('storage/'.$k->foto) : 'https://ui-avatars.com/api/?name='.urlencode($k->nama) }}"
                                                width="40" height="40" class="rounded-circle me-2">
                                            <div class="fw-bold">{{ $k->nama }}</div>
                                        </div>
                                    </td>

                                    <td>{{ optional($k->jabatan)->nama_jabatan ?? '-' }}</td>

                                    <td>
                                        @if($k->status_gaji == 'harian')
                                        <span class="badge bg-info">Harian</span>
                                        @else
                                        <span class="badge bg-primary">Bulanan</span>
                                        @endif
                                    </td>

                                    <td>
                                        @if(optional($k->user)->status === 'aktif')
                                        <span class="badge bg-success">Aktif</span>
                                        @else
                                        <span class="badge bg-danger">Nonaktif</span>
                                        @endif
                                    </td>

                                    <td>
                                        {{-- Tombol Detail --}}
                                        <button class="btn btn-sm btn-info text-white" data-bs-toggle="modal"
                                            data-bs-target="#modalDetailKaryawan" data-id="{{ $k->id }}"
                                            data-nama="{{ $k->nama }}"
                                            data-jabatan="{{ optional($k->jabatan)->nama_jabatan ?? '-' }}"
                                            data-alamat="{{ $k->alamat ?? '-' }}" data-nohp="{{ $k->no_hp ?? '-' }}"
                                            data-nik="{{ $k->nik ?? '-' }}"
                                            data-tgllahir="{{ $k->tgl_lahir ? \Carbon\Carbon::parse($k->tgl_lahir)->format('d M Y') : '-' }}"
                                            data-tglmasuk="{{ $k->tgl_masuk ? \Carbon\Carbon::parse($k->tgl_masuk)->format('d M Y') : '-' }}"
                                            data-statusgaji="{{ $k->status_gaji }}"
                                            data-gaji="{{ $k->status_gaji == 'bulanan' ? 'Rp '.number_format($k->gaji_pokok,0,',','.') : 'Rp '.number_format($k->gaji_per_hari,0,',','.').' / hari' }}"
                                            data-status="{{ optional($k->user)->status ?? 'nonaktif' }}"
                                            data-foto="{{ $k->foto ? asset('storage/'.$k->foto) : 'https://ui-avatars.com/api/?name='.urlencode($k->nama) }}">
                                            <i data-feather="eye"></i> Detail
                                        </button>

                                        <button class="btn btn-sm btn-warning" data-bs-toggle="modal"
                                            data-bs-target="#modalEditKaryawan" data-id="{{ $k->id }}"
                                            data-nama="{{ $k->nama }}" data-alamat="{{ $k->alamat }}"
                                            data-nohp="{{ $k->no_hp }}" data-tgl="{{ $k->tgl_masuk }}"
                                            data-jabatan="{{ $k->jabatan_id }}" data-statusgaji="{{ $k->status_gaji }}">
                                            <i data-feather="edit"></i> Edit
                                        </button>

                                        <button class="btn btn-sm btn-danger" onclick="confirmDelete({{ $k->id }})">
                                            <i data-feather="trash-2"></i> Hapus
                                        </button>

                                        <form id="delete-form-{{ $k->id }}"
                                            action="{{ route('admin.karyawan.destroy', $k->id) }}" method="POST"
                                            style="display:none;">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>


{{-- MODAL EDIT --}}
<div class="modal fade" id="modalEditKaryawan">
    <div class="modal-dialog">
        <form id="formEdit" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="modal-content">
                <div class="modal-header">
                    <h5>Edit Karyawan</h5>
                </div>

                <div class="modal-body">

                    <input type="hidden" id="edit_id">

                    <input type="text" id="edit_nama" name="nama" class="form-control mb-2">
                    <textarea id="edit_alamat" name="alamat" class="form-control mb-2"></textarea>
                    <input type="text" id="edit_nohp" name="no_hp" class="form-control mb-2">
                    <input type="date" id="edit_tgl" name="tgl_masuk" class="form-control mb-2">

                    <select id="edit_jabatan" name="jabatan_id" class="form-control mb-2">
                        @foreach($jabatan as $j)
                        <option value="{{ $j->id }}">{{ $j->nama_jabatan }}</option>
                        @endforeach
                    </select>

                    <select id="edit_status_gaji" name="status_gaji" class="form-control mb-2">
                        <option value="harian">Harian</option>
                        <option value="bulanan">Bulanan</option>
                    </select>

                    <input type="file" name="foto" class="form-control">

                </div>

                <div class="modal-footer">
                    <button class="btn btn-primary">Update</button>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
    document.getElementById('modalEditKaryawan').addEventListener('show.bs.modal', function (event) {

    const btn = event.relatedTarget;

    const id = btn.getAttribute('data-id');

    document.getElementById('edit_id').value = id;
    document.getElementById('edit_nama').value = btn.getAttribute('data-nama');
    document.getElementById('edit_alamat').value = btn.getAttribute('data-alamat');
    document.getElementById('edit_nohp').value = btn.getAttribute('data-nohp');
    document.getElementById('edit_tgl').value = btn.getAttribute('data-tgl');
    document.getElementById('edit_jabatan').value = btn.getAttribute('data-jabatan');
    document.getElementById('edit_status_gaji').value = btn.getAttribute('data-statusgaji');

    document.getElementById('formEdit').action = "/admin/karyawan/" + id;
});

function confirmDelete(id) {
    if (confirm('Yakin hapus data?')) {
        document.getElementById('delete-form-' + id).submit();
    }
}
</script>
<script>
    const statusGaji = document.getElementById('status_gaji');
const gajiPokok = document.getElementById('wrap_gaji_pokok');
const gajiHarian = document.getElementById('wrap_gaji_harian');

// default hidden
gajiPokok.style.display = 'none';
gajiHarian.style.display = 'none';

statusGaji.addEventListener('change', function () {
    let val = this.value;

    gajiPokok.style.display = (val === 'bulanan') ? 'block' : 'none';
    gajiHarian.style.display = (val === 'harian') ? 'block' : 'none';
});
</script>
@endpush