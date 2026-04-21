@extends('admin.layouts.app')

@section('title', 'Reimbursement')

@section('content')
<main>
    <header class="page-header page-header-dark bg-gradient-primary-to-secondary pb-10">
        <div class="container-xl px-4">
            <div class="page-header-content pt-4 d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="page-header-title">
                        <div class="page-header-icon"><i data-feather="credit-card"></i></div>
                        Reimbursement
                    </h1>
                    <div class="page-header-subtitle">Ringkasan pengajuan reimbursement karyawan dan status approval-nya.</div>
                </div>
                <button class="btn btn-white btn-sm" data-bs-toggle="modal" data-bs-target="#modalTambah">
                    <i data-feather="plus"></i> Tambah
                </button>
            </div>
        </div>
    </header>

    <div class="container-xl px-4 mt-n10">
        <div class="card mb-4">
            <div class="card-header">Daftar Reimbursement</div>
            <div class="card-body">
                <form method="GET" class="row g-2 mb-4">
                    <div class="col-md-4">
                        <select name="karyawan_id" class="form-select">
                            <option value="">Semua karyawan</option>
                            @foreach($karyawanList as $karyawan)
                            <option value="{{ $karyawan->id }}" {{ request('karyawan_id') == $karyawan->id ? 'selected' : '' }}>{{ $karyawan->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="kategori_reimbursement_id" class="form-select">
                            <option value="">Semua kategori</option>
                            @foreach($kategoriList as $kategori)
                            <option value="{{ $kategori->id }}" {{ request('kategori_reimbursement_id') == $kategori->id ? 'selected' : '' }}>{{ $kategori->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="status" class="form-select">
                            <option value="">Semua status</option>
                            @foreach(['pending', 'disetujui', 'ditolak', 'dibayar'] as $status)
                            <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>{{ ucfirst($status) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 d-grid">
                        <button class="btn btn-primary">Filter</button>
                    </div>
                </form>

                <div class="table-responsive">
                    <table id="datatablesSimple" class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Karyawan</th>
                                <th>Kategori</th>
                                <th>Judul</th>
                                <th>Tanggal</th>
                                <th>Jumlah</th>
                                <th>Status</th>
                                <th>Disetujui Oleh</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($reimbursement as $index => $item)
                            @php
                            $badge = match($item->status) {
                                'pending' => 'warning',
                                'disetujui' => 'info',
                                'ditolak' => 'danger',
                                'dibayar' => 'success',
                                default => 'secondary',
                            };
                            @endphp
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $item->karyawan->nama ?? '-' }}</td>
                                <td>{{ $item->kategoriReimbursement->nama ?? '-' }}</td>
                                <td>
                                    <div class="fw-semibold">{{ $item->judul }}</div>
                                    <div class="small text-muted">{{ $item->deskripsi ?: 'Tanpa deskripsi' }}</div>
                                    @if($item->catatan_approval)
                                    <div class="small text-muted">Catatan: {{ $item->catatan_approval }}</div>
                                    @endif
                                </td>
                                <td>
                                    <div>Pengajuan: {{ $item->tanggal_pengajuan->format('d/m/Y') }}</div>
                                    <div class="small text-muted">Transaksi: {{ $item->tanggal_transaksi->format('d/m/Y') }}</div>
                                </td>
                                <td>
                                    <div>Ajukan: Rp {{ number_format($item->jumlah_diajukan, 0, ',', '.') }}</div>
                                    <div class="small text-muted">Setuju: {{ $item->jumlah_disetujui !== null ? 'Rp ' . number_format($item->jumlah_disetujui, 0, ',', '.') : '-' }}</div>
                                </td>
                                <td><span class="badge bg-{{ $badge }} text-capitalize">{{ $item->status }}</span></td>
                                <td>{{ $item->penyetuju->nik ?? '-' }}</td>
                                <td class="text-center">
                                    @if($item->bukti)
                                    <a href="{{ asset('storage/' . $item->bukti) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                        <i data-feather="paperclip"></i>
                                    </a>
                                    @endif

                                    @if($item->status === 'pending')
                                    <button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#modalApprove{{ $item->id }}">
                                        <i data-feather="check"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#modalReject{{ $item->id }}">
                                        <i data-feather="x"></i>
                                    </button>
                                    @endif

                                    @if($item->status === 'disetujui')
                                    <form action="{{ route('admin.reimbursement.bayar', $item->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button class="btn btn-sm btn-primary" onclick="return confirm('Tandai reimbursement ini sudah dibayar?')">
                                            <i data-feather="dollar-sign"></i>
                                        </button>
                                    </form>
                                    @endif

                                    <form action="{{ route('admin.reimbursement.destroy', $item->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Yakin hapus data reimbursement?')">
                                            <i data-feather="trash-2"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>

                            <div class="modal fade" id="modalApprove{{ $item->id }}">
                                <div class="modal-dialog">
                                    <form method="POST" action="{{ route('admin.reimbursement.approve', $item->id) }}">
                                        @csrf
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Approve Reimbursement</h5>
                                            </div>
                                            <div class="modal-body">
                                                <label class="form-label small mb-1">Jumlah Disetujui</label>
                                                <input type="number" name="jumlah_disetujui" class="form-control mb-3" value="{{ old('jumlah_disetujui', $item->jumlah_diajukan) }}" min="0" max="{{ $item->jumlah_diajukan }}" step="1000">

                                                <label class="form-label small mb-1">Catatan</label>
                                                <textarea name="catatan_approval" class="form-control" rows="3">{{ old('catatan_approval') }}</textarea>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                                                <button class="btn btn-success">Approve</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <div class="modal fade" id="modalReject{{ $item->id }}">
                                <div class="modal-dialog">
                                    <form method="POST" action="{{ route('admin.reimbursement.reject', $item->id) }}">
                                        @csrf
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Tolak Reimbursement</h5>
                                            </div>
                                            <div class="modal-body">
                                                <label class="form-label small mb-1">Catatan</label>
                                                <textarea name="catatan_approval" class="form-control" rows="3" placeholder="Alasan penolakan">{{ old('catatan_approval') }}</textarea>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                                                <button class="btn btn-danger">Tolak</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted py-4">Belum ada data reimbursement.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</main>

<div class="modal fade" id="modalTambah">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('admin.reimbursement.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Reimbursement</h5>
                </div>
                <div class="modal-body">
                    <select name="karyawan_id" class="form-select mb-2">
                        <option value="">Pilih karyawan</option>
                        @foreach($karyawanList as $karyawan)
                        <option value="{{ $karyawan->id }}" {{ old('karyawan_id') == $karyawan->id ? 'selected' : '' }}>{{ $karyawan->nama }}</option>
                        @endforeach
                    </select>

                    <select name="kategori_reimbursement_id" class="form-select mb-2">
                        <option value="">Pilih kategori</option>
                        @foreach($kategoriList as $kategori)
                        <option value="{{ $kategori->id }}" {{ old('kategori_reimbursement_id') == $kategori->id ? 'selected' : '' }}>{{ $kategori->nama }}</option>
                        @endforeach
                    </select>

                    <input type="date" name="tanggal_transaksi" class="form-control mb-2" value="{{ old('tanggal_transaksi') }}">
                    <input type="text" name="judul" class="form-control mb-2" value="{{ old('judul') }}" placeholder="Judul">
                    <input type="number" name="jumlah_diajukan" class="form-control mb-2" value="{{ old('jumlah_diajukan') }}" placeholder="Jumlah diajukan" min="1" step="1000">
                    <textarea name="deskripsi" class="form-control mb-2" rows="3" placeholder="Deskripsi">{{ old('deskripsi') }}</textarea>
                    <input type="file" name="bukti" class="form-control" accept=".jpg,.jpeg,.png,.pdf">
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
