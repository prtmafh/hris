@extends('admin.layouts.app')

@section('title', 'Daftar Karyawan')

@section('content')
<main>
    <header class="page-header page-header-dark bg-gradient-primary-to-secondary pb-10">
        <div class="container-xl px-4">
            <div class="page-header-content pt-4">
                <div class="row align-items-center justify-content-between">
                    <div class="col-auto mt-4">
                        <h1 class="page-header-title">
                            <div class="page-header-icon"><i data-feather="users"></i></div>
                            Daftar Karyawan
                        </h1>
                        <div class="page-header-subtitle">Manajemen data karyawan</div>
                    </div>
                    <div class="col-auto mt-4">
                        <button class="btn btn-white btn-sm" data-bs-toggle="modal"
                            data-bs-target="#modalTambahKaryawan">
                            <i data-feather="plus"></i> Tambah Karyawan
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div class="container-xl px-4 mt-n10">
        <div class="card mb-4">
            <div class="card-header">Data Karyawan</div>
            <div class="card-body">
                <table id="datatablesSimple">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Karyawan</th>
                            <th>Alamat</th>
                            <th>No HP</th>
                            <th>Tanggal Masuk</th>
                            <th>Jenis Gaji</th>
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
                                    <img src="{{ asset('storage/'.$k->foto) }}" width="40" class="rounded-circle me-2">
                                    <div>
                                        <div class="fw-bold">{{ $k->nama }}</div>
                                        <small>{{ $k->jabatan->nama_jabatan ?? '-' }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $k->alamat }}</td>
                            <td>{{ $k->no_hp }}</td>
                            <td>{{ \Carbon\Carbon::parse($k->tgl_masuk)->format('d M Y') }}</td>

                            {{-- STATUS GAJI --}}
                            <td>
                                @if($k->status_gaji == 'harian')
                                <span class="badge bg-info">Harian</span>
                                @else
                                <span class="badge bg-primary">Bulanan</span>
                                @endif
                            </td>

                            {{-- STATUS USER --}}
                            <td>
                                @if($k->user && $k->user->status === 'aktif')
                                <span class="badge bg-success">Aktif</span>
                                @else
                                <span class="badge bg-danger">Nonaktif</span>
                                @endif
                            </td>

                            <td>
                                <button class="btn btn-sm btn-warning" data-bs-toggle="modal"
                                    data-bs-target="#modalEditKaryawan" data-id="{{ $k->id_karyawan }}"
                                    data-nama="{{ $k->nama }}" data-alamat="{{ $k->alamat }}"
                                    data-nohp="{{ $k->no_hp }}" data-tglmasuk="{{ $k->tgl_masuk }}"
                                    data-jabatan="{{ $k->jabatan_id }}" data-statusgaji="{{ $k->status_gaji }}">
                                    Edit
                                </button>

                                <button class="btn btn-sm btn-danger" onclick="confirmDelete({{ $k->id_karyawan }})">
                                    Hapus
                                </button>

                                <form id="delete-form-{{ $k->id_karyawan }}"
                                    action="{{ route('admin.karyawan.destroy', $k->id_karyawan) }}" method="POST"
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
</main>

{{-- MODAL TAMBAH --}}
<div class="modal fade" id="modalTambahKaryawan">
    <div class="modal-dialog">
        <form action="{{ route('admin.karyawan.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5>Tambah Karyawan</h5>
                </div>
                <div class="modal-body">

                    <input type="text" name="nama" class="form-control mb-2" placeholder="Nama" required>
                    <textarea name="alamat" class="form-control mb-2" placeholder="Alamat"></textarea>
                    <input type="text" name="no_hp" class="form-control mb-2" placeholder="No HP">
                    <input type="date" name="tgl_masuk" class="form-control mb-2" required>

                    <select name="jabatan_id" class="form-control mb-2" required>
                        <option value="">Pilih Jabatan</option>
                        @foreach($jabatan as $j)
                        <option value="{{ $j->id_jabatan }}">{{ $j->nama_jabatan }}</option>
                        @endforeach
                    </select>

                    <select name="status_gaji" class="form-control mb-2" required>
                        <option value="">Jenis Gaji</option>
                        <option value="harian">Harian</option>
                        <option value="bulanan">Bulanan</option>
                    </select>

                    <input type="file" name="foto" class="form-control">

                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>

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
                        <option value="{{ $j->id_jabatan }}">{{ $j->nama_jabatan }}</option>
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
    document.getElementById('edit_tgl').value = btn.getAttribute('data-tglmasuk');
    document.getElementById('edit_jabatan').value = btn.getAttribute('data-jabatan');
    document.getElementById('edit_status_gaji').value = btn.getAttribute('data-statusgaji');

    document.getElementById('formEdit').action = "/karyawan/" + id;
});

function confirmDelete(id) {
    if (confirm('Yakin hapus data?')) {
        document.getElementById('delete-form-' + id).submit();
    }
}
</script>
@endpush