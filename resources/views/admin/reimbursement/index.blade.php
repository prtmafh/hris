@extends('admin.layouts.app')

@section('title','Reimbursement')

@section('content')
<main>

    <header class="page-header page-header-compact page-header-light border-bottom bg-white mb-4">
        <div class="container-xl px-4">
            <div class="page-header-content">
                <div class="row align-items-center justify-content-between pt-3">
                    <div class="col-auto mb-3">
                        <h1 class="page-header-title">
                            <div class="page-header-icon">
                                <i data-feather="credit-card"></i>
                            </div>
                            Expense Reimbursement
                        </h1>
                    </div>
                    <div class="col-auto mb-3">
                        <button class="btn btn-sm btn-light text-primary" data-bs-toggle="modal"
                            data-bs-target="#modalTambah">
                            <i data-feather="plus"></i>
                            Tambah Pengajuan
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div class="container-xl px-4">

        {{-- FILTER --}}
        <div class="card mb-4">
            <div class="card-header">Filter Pengajuan</div>
            <div class="card-body">
                <form method="GET" class="row gx-2 gy-2 align-items-end">
                    <div class="col-md-4">
                        <label class="small mb-1">Karyawan</label>
                        <select name="karyawan_id" class="form-select form-select-sm">
                            <option value="">Semua karyawan</option>
                            @foreach($karyawanList as $karyawan)
                            <option value="{{ $karyawan->id }}" {{ request('karyawan_id')==$karyawan->id ? 'selected' :
                                '' }}>
                                {{ $karyawan->nama }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="small mb-1">Kategori</label>
                        <select name="kategori_reimbursement_id" class="form-select form-select-sm">
                            <option value="">Semua kategori</option>
                            @foreach($kategoriList as $kategori)
                            <option value="{{ $kategori->id }}" {{ request('kategori_reimbursement_id')==$kategori->id ?
                                'selected' : '' }}>
                                {{ $kategori->nama }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="small mb-1">Status</label>
                        <select name="status" class="form-select form-select-sm">
                            <option value="">Semua status</option>
                            @foreach(['pending','disetujui','ditolak','dibayar'] as $status)
                            <option value="{{ $status }}" {{ request('status')==$status ? 'selected' : '' }}>
                                {{ ucfirst($status) }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 d-grid">
                        <button class="btn btn-primary btn-sm">Filter</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- TABLE --}}
        <div class="card">
            <div class="card-header">Daftar Pengajuan Reimbursement</div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="datatablesSimple" class="table table-hover align-middle">

                        <thead>
                            <tr>
                                <th width="50">No</th>
                                <th>Karyawan</th>
                                <th>Kategori</th>
                                <th>Nominal Diajukan</th>
                                <th>Tgl. Pengajuan</th>
                                <th>Status</th>
                                <th width="180" class="text-center">Aksi</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($reimbursement as $index => $item)

                            @php
                            $badge = match($item->status) {
                            'pending' => 'yellow',
                            'disetujui' => 'blue',
                            'ditolak' => 'red',
                            'dibayar' => 'green',
                            default => 'secondary'
                            };
                            @endphp

                            <tr>

                                {{-- No --}}
                                <td class="fw-semibold text-muted">{{ $index + 1 }}</td>

                                {{-- Karyawan --}}
                                <td>
                                    <div class="fw-semibold">{{ $item->karyawan->nama ?? '-' }}</div>
                                    <div class="small text-muted">ID #{{ $item->id }}</div>
                                </td>

                                {{-- Kategori --}}
                                <td>{{ $item->kategoriReimbursement->nama ?? '-' }}</td>

                                {{-- Nominal Diajukan --}}
                                <td class="fw-semibold">
                                    Rp {{ number_format($item->jumlah_diajukan, 0, ',', '.') }}
                                </td>

                                {{-- Tgl. Pengajuan --}}
                                <td>{{ $item->tanggal_pengajuan->format('d M Y') }}</td>

                                {{-- Status --}}
                                <td>
                                    <span class="badge bg-{{ $badge }}-soft text-{{ $badge }}">
                                        {{ ucfirst($item->status) }}
                                    </span>
                                </td>

                                {{-- Aksi --}}
                                <td class="text-center">

                                    {{-- Detail --}}
                                    <button class="btn btn-datatable btn-icon btn-transparent-dark me-1"
                                        data-bs-toggle="modal" data-bs-target="#modalDetail{{ $item->id }}"
                                        title="Lihat Detail">
                                        <i data-feather="eye"></i>
                                    </button>

                                    {{-- Bukti --}}
                                    @if($item->bukti)
                                    <a href="{{ asset('storage/' . $item->bukti) }}" target="_blank"
                                        class="btn btn-datatable btn-icon btn-transparent-dark me-1"
                                        title="Lihat Bukti">
                                        <i data-feather="paperclip"></i>
                                    </a>
                                    @endif

                                    {{-- Approve & Reject --}}
                                    @if($item->status === 'pending')
                                    <button class="btn btn-datatable btn-icon btn-transparent-dark text-success me-1"
                                        data-bs-toggle="modal" data-bs-target="#modalApprove{{ $item->id }}"
                                        title="Setujui">
                                        <i data-feather="check"></i>
                                    </button>
                                    <button class="btn btn-datatable btn-icon btn-transparent-dark text-danger me-1"
                                        data-bs-toggle="modal" data-bs-target="#modalReject{{ $item->id }}"
                                        title="Tolak">
                                        <i data-feather="x"></i>
                                    </button>
                                    @endif

                                    {{-- Bayar --}}
                                    @if($item->status === 'disetujui')
                                    <form action="{{ route('admin.reimbursement.bayar', $item->id) }}" method="POST"
                                        class="d-inline">
                                        @csrf
                                        <button
                                            class="btn btn-datatable btn-icon btn-transparent-dark text-primary me-1"
                                            onclick="return confirm('Tandai reimbursement ini sudah dibayar?')"
                                            title="Tandai Dibayar">
                                            <i data-feather="dollar-sign"></i>
                                        </button>
                                    </form>
                                    @endif

                                    {{-- Hapus --}}
                                    <form action="{{ route('admin.reimbursement.destroy', $item->id) }}" method="POST"
                                        class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-datatable btn-icon btn-transparent-dark text-danger"
                                            onclick="return confirm('Yakin hapus data reimbursement ini?')"
                                            title="Hapus">
                                            <i data-feather="trash-2"></i>
                                        </button>
                                    </form>

                                </td>
                            </tr>


                            {{-- MODAL DETAIL --}}
                            <div class="modal fade" id="modalDetail{{ $item->id }}" tabindex="-1">

                                <div class="modal-dialog modal-xl modal-dialog-centered">
                                    <div class="modal-content border-0 shadow-lg">

                                        {{-- HEADER --}}
                                        <div class="modal-header border-bottom bg-white">
                                            <h5 class="modal-title d-flex align-items-center">
                                                <i data-feather="file-text" class="me-2"></i>
                                                Detail Reimbursement
                                            </h5>

                                            <button type="button" class="btn-close" data-bs-dismiss="modal">
                                            </button>
                                        </div>



                                        <div class="modal-body p-4">

                                            <div class="row">

                                                {{-- LEFT SUMMARY --}}
                                                <div class="col-lg-4">

                                                    <div class="card mb-4 border">
                                                        <div class="card-body text-center">

                                                            <div class="avatar avatar-xl mb-3">
                                                                <img class="avatar-img rounded-circle"
                                                                    src="https://ui-avatars.com/api/?name={{ urlencode($item->karyawan->nama ?? 'User') }}">
                                                            </div>

                                                            <div class="fw-bold fs-5">
                                                                {{ $item->karyawan->nama ?? '-' }}
                                                            </div>

                                                            <div class="small text-muted mb-3">
                                                                Request #{{ $item->id }}
                                                            </div>

                                                            <span class="badge bg-{{ $badge }}-soft text-{{ $badge }}">
                                                                {{ ucfirst($item->status) }}
                                                            </span>

                                                        </div>
                                                    </div>



                                                    <div class="card border">
                                                        <div class="card-header">
                                                            Financial Summary
                                                        </div>

                                                        <div class="card-body">

                                                            <div
                                                                class="d-flex justify-content-between border-bottom py-2">
                                                                <span class="small text-muted">
                                                                    Nominal Diajukan
                                                                </span>

                                                                <strong>
                                                                    Rp {{
                                                                    number_format($item->jumlah_diajukan,0,',','.') }}
                                                                </strong>
                                                            </div>


                                                            <div
                                                                class="d-flex justify-content-between border-bottom py-2">
                                                                <span class="small text-muted">
                                                                    Disetujui
                                                                </span>

                                                                <strong class="text-success">
                                                                    @if($item->jumlah_disetujui)
                                                                    Rp {{
                                                                    number_format($item->jumlah_disetujui,0,',','.') }}
                                                                    @else
                                                                    -
                                                                    @endif
                                                                </strong>
                                                            </div>


                                                            <div class="d-flex justify-content-between py-2">
                                                                <span class="small text-muted">
                                                                    Approver
                                                                </span>

                                                                <strong>
                                                                    {{ $item->penyetuju->nik ?? '-' }}
                                                                </strong>
                                                            </div>

                                                        </div>
                                                    </div>

                                                </div>



                                                {{-- RIGHT DETAIL --}}
                                                <div class="col-lg-8">


                                                    <div class="card mb-4 border">
                                                        <div class="card-header">
                                                            Informasi Pengajuan
                                                        </div>

                                                        <div class="card-body">

                                                            <div class="row g-4">

                                                                <div class="col-md-6">
                                                                    <label class="small text-muted d-block mb-1">
                                                                        Kategori
                                                                    </label>

                                                                    <div class="fw-semibold">
                                                                        {{ $item->kategoriReimbursement->nama ?? '-' }}
                                                                    </div>
                                                                </div>


                                                                <div class="col-md-6">
                                                                    <label class="small text-muted d-block mb-1">
                                                                        Judul Pengajuan
                                                                    </label>

                                                                    <div class="fw-semibold">
                                                                        {{ $item->judul }}
                                                                    </div>
                                                                </div>


                                                                <div class="col-md-6">
                                                                    <label class="small text-muted d-block mb-1">
                                                                        Tanggal Pengajuan
                                                                    </label>

                                                                    <div class="fw-semibold">
                                                                        {{ $item->tanggal_pengajuan->format('d M Y') }}
                                                                    </div>
                                                                </div>


                                                                <div class="col-md-6">
                                                                    <label class="small text-muted d-block mb-1">
                                                                        Tanggal Transaksi
                                                                    </label>

                                                                    <div class="fw-semibold">
                                                                        {{ $item->tanggal_transaksi->format('d M Y') }}
                                                                    </div>
                                                                </div>


                                                                <div class="col-12">
                                                                    <label class="small text-muted d-block mb-1">
                                                                        Deskripsi
                                                                    </label>

                                                                    <div class="p-3 bg-light rounded">
                                                                        {{ $item->deskripsi ?: 'Tidak ada deskripsi
                                                                        pengajuan' }}
                                                                    </div>
                                                                </div>

                                                            </div>

                                                        </div>
                                                    </div>



                                                    @if($item->catatan_approval)
                                                    <div class="card mb-4 border">
                                                        <div class="card-header">
                                                            Catatan Approval
                                                        </div>

                                                        <div class="card-body">

                                                            <div class="alert alert-warning-soft mb-0">
                                                                {{ $item->catatan_approval }}
                                                            </div>

                                                        </div>
                                                    </div>
                                                    @endif



                                                    @if($item->bukti)
                                                    <div class="card border">
                                                        <div class="card-header">
                                                            Lampiran Bukti
                                                        </div>

                                                        <div class="card-body">

                                                            <a href="{{ asset('storage/'.$item->bukti) }}"
                                                                target="_blank" class="btn btn-light border">
                                                                <i data-feather="paperclip" class="me-1"></i>
                                                                Lihat Lampiran Bukti
                                                            </a>

                                                        </div>
                                                    </div>
                                                    @endif


                                                </div>
                                            </div>

                                        </div>



                                        {{-- FOOTER --}}
                                        <div class="modal-footer bg-light">
                                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                                                Tutup
                                            </button>
                                        </div>

                                    </div>
                                </div>
                            </div>


                            {{-- MODAL APPROVE --}}
                            <div class="modal fade" id="modalApprove{{ $item->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <form method="POST" action="{{ route('admin.reimbursement.approve', $item->id) }}">
                                        @csrf
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Setujui Reimbursement</h5>
                                                <button type="button" class="btn-close"
                                                    data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <label class="small mb-1">Jumlah Disetujui</label>
                                                <input type="number" name="jumlah_disetujui" class="form-control mb-3"
                                                    value="{{ old('jumlah_disetujui', $item->jumlah_diajukan) }}">
                                                <label class="small mb-1">Catatan</label>
                                                <textarea name="catatan_approval" class="form-control"
                                                    rows="3"></textarea>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-light"
                                                    data-bs-dismiss="modal">Batal</button>
                                                <button class="btn btn-success">Setujui</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            {{-- MODAL REJECT --}}
                            <div class="modal fade" id="modalReject{{ $item->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <form method="POST" action="{{ route('admin.reimbursement.reject', $item->id) }}">
                                        @csrf
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Tolak Reimbursement</h5>
                                                <button type="button" class="btn-close"
                                                    data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <label class="small mb-1">Catatan Penolakan</label>
                                                <textarea name="catatan_approval" class="form-control"
                                                    rows="3"></textarea>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-light"
                                                    data-bs-dismiss="modal">Batal</button>
                                                <button class="btn btn-danger">Tolak</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-5">
                                    Belum ada data reimbursement
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
<div class="modal fade" id="modalTambah" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('admin.reimbursement.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Reimbursement</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">

                    <label class="small mb-1">Karyawan</label>
                    <select name="karyawan_id" class="form-select mb-3">
                        <option value="">Pilih karyawan</option>
                        @foreach($karyawanList as $karyawan)
                        <option value="{{ $karyawan->id }}">{{ $karyawan->nama }}</option>
                        @endforeach
                    </select>

                    <label class="small mb-1">Kategori</label>
                    <select name="kategori_reimbursement_id" class="form-select mb-3">
                        <option value="">Pilih kategori</option>
                        @foreach($kategoriList as $kategori)
                        <option value="{{ $kategori->id }}">{{ $kategori->nama }}</option>
                        @endforeach
                    </select>

                    <label class="small mb-1">Tanggal Transaksi</label>
                    <input type="date" name="tanggal_transaksi" class="form-control mb-3">

                    <label class="small mb-1">Judul Pengajuan</label>
                    <input type="text" name="judul" class="form-control mb-3" placeholder="Judul pengajuan">

                    <label class="small mb-1">Jumlah Diajukan</label>
                    <input type="number" name="jumlah_diajukan" class="form-control mb-3" placeholder="0">

                    <label class="small mb-1">Deskripsi</label>
                    <textarea name="deskripsi" class="form-control mb-3" rows="3"
                        placeholder="Deskripsi (opsional)"></textarea>

                    <label class="small mb-1">Bukti</label>
                    <input type="file" name="bukti" class="form-control">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection