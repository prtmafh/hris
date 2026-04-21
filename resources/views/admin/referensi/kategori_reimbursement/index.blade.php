@extends('admin.layouts.app')

@section('title', 'Kategori Reimbursement')

@section('content')
<main>
    <header class="page-header page-header-dark bg-gradient-primary-to-secondary pb-10">
        <div class="container-xl px-4">
            <div class="page-header-content pt-4 d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="page-header-title">
                        <div class="page-header-icon"><i data-feather="tag"></i></div>
                        Kategori Reimbursement
                    </h1>
                    <div class="page-header-subtitle">
                        Manajemen kategori reimbursement (plafon & aturan)
                    </div>
                </div>

                {{-- tombol tambah --}}
                <button class="btn btn-white btn-sm" data-bs-toggle="modal" data-bs-target="#modalTambah">
                    <i data-feather="plus"></i> Tambah
                </button>
            </div>
        </div>
    </header>

    <div class="container-xl px-4 mt-n10">
        <div class="card mb-4">
            <div class="card-header">Data Kategori Reimbursement</div>
            <div class="card-body">

                <div class="table-responsive">
                    <table id="datatablesSimple" class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>Plafon</th>
                                <th>Bukti</th>
                                <th>Status</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($data as $i => $item)
                            <tr>
                                <td>{{ $i + 1 }}</td>

                                <td>
                                    <div class="fw-bold">{{ $item->nama }}</div>
                                    <small class="text-muted">
                                        {{ $item->deskripsi ?: '-' }}
                                    </small>
                                </td>

                                <td>
                                    <div>
                                        <small class="text-muted">Per Bulan:</small><br>
                                        {{ $item->plafon_per_bulan ? 'Rp ' .
                                        number_format($item->plafon_per_bulan,0,',','.') : '-' }}
                                    </div>

                                    <div class="mt-1">
                                        <small class="text-muted">Per Pengajuan:</small><br>
                                        {{ $item->plafon_per_pengajuan ? 'Rp ' .
                                        number_format($item->plafon_per_pengajuan,0,',','.') : '-' }}
                                    </div>
                                </td>

                                <td>
                                    @if($item->perlu_bukti)
                                    <span class="badge bg-info">Wajib</span>
                                    @else
                                    <span class="badge bg-secondary">Tidak</span>
                                    @endif
                                </td>

                                <td>
                                    <span class="badge bg-{{ $item->status == 'aktif' ? 'success' : 'secondary' }}">
                                        {{ ucfirst($item->status) }}
                                    </span>
                                </td>

                                <td class="text-center">
                                    {{-- toggle status --}}
                                    <form action="{{ route('admin.kategori-reimbursement.toggle', $item->id) }}"
                                        method="POST" style="display:inline;">
                                        @csrf
                                        <button
                                            class="btn btn-sm btn-{{ $item->status == 'aktif' ? 'secondary' : 'success' }}">
                                            <i data-feather="refresh-cw"></i>
                                        </button>
                                    </form>

                                    {{-- edit --}}
                                    <button class="btn btn-sm btn-warning" data-bs-toggle="modal"
                                        data-bs-target="#modalEdit{{ $item->id }}">
                                        <i data-feather="edit"></i>
                                    </button>

                                    {{-- delete --}}
                                    <form action="{{ route('admin.kategori-reimbursement.destroy', $item->id) }}"
                                        method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger"
                                            onclick="return confirm('Yakin hapus data?')">
                                            <i data-feather="trash-2"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>

                            {{-- MODAL EDIT --}}
                            <div class="modal fade" id="modalEdit{{ $item->id }}">
                                <div class="modal-dialog">
                                    <form method="POST"
                                        action="{{ route('admin.kategori-reimbursement.update', $item->id) }}">
                                        @csrf
                                        @method('PUT')

                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5>Edit Kategori</h5>
                                            </div>

                                            <div class="modal-body">
                                                <input type="text" name="nama" class="form-control mb-2"
                                                    value="{{ $item->nama }}" placeholder="Nama">

                                                <textarea name="deskripsi" class="form-control mb-2"
                                                    placeholder="Deskripsi">{{ $item->deskripsi }}</textarea>

                                                <input type="number" name="plafon_per_bulan" class="form-control mb-2"
                                                    value="{{ $item->plafon_per_bulan }}"
                                                    placeholder="Plafon per bulan">

                                                <input type="number" name="plafon_per_pengajuan"
                                                    class="form-control mb-2" value="{{ $item->plafon_per_pengajuan }}"
                                                    placeholder="Plafon per pengajuan">

                                                <select name="status" class="form-control mb-2">
                                                    <option value="aktif" {{ $item->status=='aktif'?'selected':''
                                                        }}>Aktif</option>
                                                    <option value="nonaktif" {{ $item->status=='nonaktif'?'selected':''
                                                        }}>Nonaktif</option>
                                                </select>

                                                <label>
                                                    <input type="checkbox" name="perlu_bukti" value="1" {{
                                                        $item->perlu_bukti ? 'checked' : '' }}>
                                                    Perlu Bukti
                                                </label>
                                            </div>

                                            <div class="modal-footer">
                                                <button class="btn btn-primary">Update</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">
                                    Belum ada data
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</main>

{{-- MODAL TAMBAH --}}
<div class="modal fade" id="modalTambah">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('admin.kategori-reimbursement.store') }}">
            @csrf

            <div class="modal-content">
                <div class="modal-header">
                    <h5>Tambah Kategori</h5>
                </div>

                <div class="modal-body">
                    <input type="text" name="nama" class="form-control mb-2" placeholder="Nama">

                    <textarea name="deskripsi" class="form-control mb-2" placeholder="Deskripsi"></textarea>

                    <input type="number" name="plafon_per_bulan" class="form-control mb-2"
                        placeholder="Plafon per bulan">

                    <input type="number" name="plafon_per_pengajuan" class="form-control mb-2"
                        placeholder="Plafon per pengajuan">

                    <select name="status" class="form-control mb-2">
                        <option value="aktif">Aktif</option>
                        <option value="nonaktif">Nonaktif</option>
                    </select>

                    <label>
                        <input type="checkbox" name="perlu_bukti" value="1" checked>
                        Perlu Bukti
                    </label>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection