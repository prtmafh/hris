@extends('admin.layouts.app')

@section('title','Kategori Reimbursement')

@section('content')
<main>

    <header class="page-header page-header-compact page-header-light border-bottom bg-white mb-4">
        <div class="container-xl px-4">
            <div class="page-header-content">
                <div class="row align-items-center justify-content-between pt-3">

                    <div class="col-auto mb-3">
                        <h1 class="page-header-title">
                            <div class="page-header-icon">
                                <i data-feather="tag"></i>
                            </div>
                            Kategori Reimbursement
                        </h1>
                    </div>

                    <div class="col-auto mb-3">
                        <button class="btn btn-sm btn-light text-primary" data-bs-toggle="modal"
                            data-bs-target="#modalTambah">
                            <i data-feather="plus"></i>
                            Tambah
                        </button>
                    </div>

                </div>
            </div>
        </div>
    </header>


    <div class="container-xl px-4">

        <div class="card">
            <div class="card-header">
                Data Kategori Reimbursement
            </div>

            <div class="card-body">

                <table id="datatablesSimple" class="table table-hover align-middle">

                    <thead>
                        <tr>
                            <th width="60">#</th>
                            <th>Kategori Reimbursement</th>
                            <th style="min-width:260px">
                                Benefit & Plafon
                            </th>
                            <th>Persyaratan Dokumen</th>
                            <th>Status</th>
                            <th width="150" class="text-center">
                                Aksi
                            </th>
                        </tr>
                    </thead>


                    <tbody>

                        @forelse($data as $i => $item)
                        <tr>

                            {{-- nomor --}}
                            <td class="text-muted fw-semibold">
                                {{ $i+1 }}
                            </td>


                            {{-- kategori --}}
                            <td>

                                <div class="fw-semibold">
                                    {{ $item->nama }}
                                </div>

                                <div class="small text-muted mt-1">
                                    {{ $item->deskripsi ?: 'Tidak ada deskripsi kategori' }}
                                </div>

                            </td>


                            {{-- plafon --}}
                            <td>

                                <div class="mb-3 pb-2 border-bottom">
                                    <div class="small text-muted text-uppercase">
                                        Plafon Bulanan
                                    </div>

                                    <div class="fw-semibold">
                                        @if($item->plafon_per_bulan)
                                        Rp {{ number_format($item->plafon_per_bulan,0,',','.') }}
                                        @else
                                        Tidak dibatasi
                                        @endif
                                    </div>
                                </div>


                                <div>
                                    <div class="small text-muted text-uppercase">
                                        Maksimum per Pengajuan
                                    </div>

                                    <div class="fw-semibold">
                                        @if($item->plafon_per_pengajuan)
                                        Rp {{ number_format($item->plafon_per_pengajuan,0,',','.') }}
                                        @else
                                        Tidak dibatasi
                                        @endif
                                    </div>
                                </div>

                            </td>



                            {{-- bukti --}}
                            <td>

                                @if($item->perlu_bukti)

                                <span class="badge bg-blue-soft text-blue">
                                    Dokumen Wajib
                                </span>

                                <div class="small text-muted mt-2">
                                    Pengajuan harus melampirkan bukti
                                </div>

                                @else

                                <span class="badge bg-secondary-soft text-secondary">
                                    Tanpa Dokumen
                                </span>

                                <div class="small text-muted mt-2">
                                    Tidak wajib lampiran
                                </div>

                                @endif

                            </td>



                            {{-- status --}}
                            <td>

                                @if($item->status=='aktif')
                                <span class="badge bg-green-soft text-green">
                                    Aktif
                                </span>

                                <div class="small text-muted mt-2">
                                    Dapat digunakan
                                </div>

                                @else

                                <span class="badge bg-red-soft text-red">
                                    Nonaktif
                                </span>

                                <div class="small text-muted mt-2">
                                    Tidak digunakan
                                </div>

                                @endif

                            </td>



                            {{-- aksi --}}
                            <td class="text-center">

                                <form action="{{ route('admin.kategori-reimbursement.toggle',$item->id) }}"
                                    method="POST" class="d-inline">
                                    @csrf

                                    <button class="btn btn-datatable btn-icon btn-transparent-dark me-1"
                                        title="Ubah Status">
                                        <i data-feather="refresh-cw"></i>
                                    </button>
                                </form>


                                <button class="btn btn-datatable btn-icon btn-transparent-dark me-1"
                                    data-bs-toggle="modal" data-bs-target="#modalEdit{{ $item->id }}" title="Edit">
                                    <i data-feather="edit"></i>
                                </button>


                                <form action="{{ route('admin.kategori-reimbursement.destroy',$item->id) }}"
                                    method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')

                                    <button class="btn btn-datatable btn-icon btn-transparent-dark text-danger"
                                        title="Hapus" onclick="return confirm('Yakin hapus data?')">
                                        <i data-feather="trash-2"></i>
                                    </button>
                                </form>

                            </td>

                        </tr>



                        {{-- MODAL EDIT ASLI TETAP --}}
                        <div class="modal fade" id="modalEdit{{ $item->id }}">
                            <div class="modal-dialog">
                                <form method="POST"
                                    action="{{ route('admin.kategori-reimbursement.update',$item->id) }}">
                                    @csrf
                                    @method('PUT')

                                    <div class="modal-content">

                                        <div class="modal-header">
                                            <h5 class="modal-title">
                                                Edit Kategori
                                            </h5>

                                            <button class="btn-close" data-bs-dismiss="modal">
                                            </button>
                                        </div>

                                        <div class="modal-body">

                                            <div class="mb-3">
                                                <label class="small mb-1">
                                                    Nama
                                                </label>

                                                <input type="text" name="nama" class="form-control"
                                                    value="{{ $item->nama }}">
                                            </div>


                                            <div class="mb-3">
                                                <label class="small mb-1">
                                                    Deskripsi
                                                </label>

                                                <textarea name="deskripsi"
                                                    class="form-control">{{ $item->deskripsi }}</textarea>
                                            </div>


                                            <div class="mb-3">
                                                <label class="small mb-1">
                                                    Plafon per Bulan
                                                </label>

                                                <input type="number" name="plafon_per_bulan" class="form-control"
                                                    value="{{ $item->plafon_per_bulan }}">
                                            </div>


                                            <div class="mb-3">
                                                <label class="small mb-1">
                                                    Plafon per Pengajuan
                                                </label>

                                                <input type="number" name="plafon_per_pengajuan" class="form-control"
                                                    value="{{ $item->plafon_per_pengajuan }}">
                                            </div>


                                            <div class="mb-3">
                                                <label class="small mb-1">
                                                    Status
                                                </label>

                                                <select name="status" class="form-control">
                                                    <option value="aktif" {{ $item->status=='aktif'?'selected':'' }}>
                                                        Aktif
                                                    </option>

                                                    <option value="nonaktif" {{ $item->status=='nonaktif'?'selected':''
                                                        }}>
                                                        Nonaktif
                                                    </option>
                                                </select>
                                            </div>


                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="perlu_bukti"
                                                    value="1" {{ $item->perlu_bukti?'checked':'' }}>

                                                <label class="form-check-label">
                                                    Perlu Bukti
                                                </label>
                                            </div>

                                        </div>

                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                                                Batal
                                            </button>

                                            <button class="btn btn-primary">
                                                Update
                                            </button>
                                        </div>

                                    </div>
                                </form>
                            </div>
                        </div>



                        @empty

                        <tr>
                            <td colspan="6" class="text-center text-muted py-5">
                                Belum ada data kategori reimbursement
                            </td>
                        </tr>

                        @endforelse

                    </tbody>

                </table>

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
                    <h5 class="modal-title">
                        Tambah Kategori
                    </h5>

                    <button class="btn-close" data-bs-dismiss="modal"></button>
                </div>


                <div class="modal-body">

                    <div class="mb-3">
                        <label class="small mb-1">
                            Nama
                        </label>

                        <input type="text" name="nama" class="form-control">
                    </div>


                    <div class="mb-3">
                        <label class="small mb-1">
                            Deskripsi
                        </label>

                        <textarea name="deskripsi" class="form-control"></textarea>
                    </div>


                    <div class="mb-3">
                        <label class="small mb-1">
                            Plafon per Bulan
                        </label>

                        <input type="number" name="plafon_per_bulan" class="form-control">
                    </div>


                    <div class="mb-3">
                        <label class="small mb-1">
                            Plafon per Pengajuan
                        </label>

                        <input type="number" name="plafon_per_pengajuan" class="form-control">
                    </div>


                    <div class="mb-3">
                        <label class="small mb-1">
                            Status
                        </label>

                        <select name="status" class="form-control">
                            <option value="aktif">
                                Aktif
                            </option>

                            <option value="nonaktif">
                                Nonaktif
                            </option>
                        </select>
                    </div>


                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="perlu_bukti" value="1" checked>

                        <label class="form-check-label">
                            Perlu Bukti
                        </label>
                    </div>

                </div>


                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                        Batal
                    </button>

                    <button class="btn btn-primary">
                        Simpan
                    </button>
                </div>

            </div>

        </form>
    </div>
</div>

@endsection