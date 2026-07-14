@extends('karyawan.layouts.app')

@section('title', 'Reimbursement Saya')

@section('content')
<main>
    <header class="page-header page-header-compact page-header-light border-bottom bg-white mb-4 py-2">
        <div class="container-xl px-4">
            <div class="page-header-content">
                <div class="row align-items-center justify-content-between pt-3">
                    <div class="col-auto mb-3">
                        <h1 class="page-header-title">
                            <div class="page-header-icon"><i data-feather="credit-card"></i></div>
                            Reimbursement Saya
                        </h1>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div class="container-xl px-4">
        <div class="row g-4">
            <div class="col-xl-4">
                <div class="card">
                    <div class="card-header">
                        <div class="fw-bold">Form Pengajuan</div>
                        <div class="small text-muted">Lengkapi data reimbursement untuk diproses admin.</div>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('karyawan.reimbursement.store') }}"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label small mb-1">Kategori</label>
                                <select name="kategori_reimbursement_id"
                                    class="form-select @error('kategori_reimbursement_id') is-invalid @enderror">
                                    <option value="">Pilih kategori</option>
                                    @foreach($kategoriList as $kategori)
                                    <option value="{{ $kategori->id }}" {{ old('kategori_reimbursement_id')==$kategori->
                                        id ? 'selected' : '' }}>
                                        {{ $kategori->nama }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('kategori_reimbursement_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label small mb-1">Tanggal Transaksi</label>
                                <input type="date" name="tanggal_transaksi"
                                    class="form-control @error('tanggal_transaksi') is-invalid @enderror"
                                    value="{{ old('tanggal_transaksi') }}">
                                @error('tanggal_transaksi')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label small mb-1">Judul</label>
                                <input type="text" name="judul"
                                    class="form-control @error('judul') is-invalid @enderror" value="{{ old('judul') }}"
                                    placeholder="Contoh: Transport meeting klien">
                                @error('judul')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label small mb-1">Jumlah Diajukan</label>
                                <input type="number" name="jumlah_diajukan"
                                    class="form-control @error('jumlah_diajukan') is-invalid @enderror"
                                    value="{{ old('jumlah_diajukan') }}">
                                @error('jumlah_diajukan')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label small mb-1">Deskripsi</label>
                                <textarea name="deskripsi" rows="3"
                                    class="form-control @error('deskripsi') is-invalid @enderror"
                                    placeholder="Tulis keterangan singkat">{{ old('deskripsi') }}</textarea>
                                @error('deskripsi')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label class="form-label small mb-1">
                                    Bukti
                                </label>

                                <input type="file" name="bukti[]" multiple
                                    class="form-control @error('bukti') is-invalid @enderror @error('bukti.*') is-invalid @enderror"
                                    accept=".jpg,.jpeg,.png,.pdf">

                                @error('bukti')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                @error('bukti.*')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div id="file-list" class="mt-2 mb-4 small text-muted"></div>

                            <button class="btn btn-primary w-100">
                                <i data-feather="send"></i> Kirim Pengajuan
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-xl-8">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div>
                            <div class="fw-bold">Riwayat Reimbursement</div>
                            {{-- <div class="small text-muted">Daftar pengajuan yang pernah Anda buat.</div> --}}
                        </div>
                        <span class="badge bg-primary">{{ $reimbursement->count() }} data</span>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="datatablesSimple" data-simple-datatable
                                class="table table-hover align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Kategori</th>
                                        <th>Judul</th>
                                        <th>Jumlah</th>
                                        <th>Status</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($reimbursement as $item)
                                    @php
                                    $badge = match($item->status) {
                                    'pending' => 'yellow',
                                    'disetujui' => 'blue',
                                    'ditolak' => 'red',
                                    'dibayar' => 'green',
                                    default => 'secondary',
                                    };
                                    @endphp
                                    <tr>
                                        <td>{{ $item->tanggal_pengajuan->format('d/m/Y') }}</td>
                                        <td>{{ $item->kategoriReimbursement->nama ?? '-' }}</td>
                                        <td>
                                            <div class="fw-semibold">{{ $item->judul }}</div>
                                            <div class="small text-muted">{{ $item->catatan_approval ?: $item->deskripsi
                                                }}</div>
                                        </td>
                                        <td>
                                            <div>Ajukan: Rp {{ number_format($item->jumlah_diajukan, 0, ',', '.') }}
                                            </div>
                                            <div class="small text-muted">Setuju: {{ $item->jumlah_disetujui !== null ?
                                                'Rp ' . number_format($item->jumlah_disetujui, 0, ',', '.') : '-' }}
                                            </div>
                                        </td>
                                        <td><span
                                                class="badge bg-{{ $badge }}-soft text-{{ $badge }} text-capitalize">{{
                                                $item->status }}</span></td>
                                        <td class="text-center">
                                            @if($item->buktiFile->isNotEmpty())

                                            <div class="dropdown d-inline">
                                                <button class="btn btn-datatable btn-icon btn-transparent-dark"
                                                    data-bs-toggle="dropdown">
                                                    <i data-feather="paperclip"></i>
                                                </button>

                                                <ul class="dropdown-menu">

                                                    @foreach($item->buktiFile as $index => $bukti)

                                                    <li>
                                                        <a class="dropdown-item" target="_blank"
                                                            href="{{ asset('storage/'.$bukti->file) }}">
                                                            Bukti {{ $index + 1 }}
                                                        </a>
                                                    </li>

                                                    @endforeach

                                                </ul>
                                            </div>

                                            @endif
                                            {{-- @if($item->buktiFile)
                                            <a href="{{ asset('storage/' . $item->buktiFile->file) }}" target="_blank"
                                                class="btn btn-datatable btn-icon btn-transparent-dark me-1"
                                                title="Lihat Bukti">
                                                <i data-feather="paperclip"></i>
                                            </a>
                                            @endif --}}
                                            @if($item->bukti)
                                            <a href="{{ asset('storage/' . $item->bukti) }}" target="_blank"
                                                class="btn btn-datatable btn-icon btn-transparent-dark me-1"
                                                title="Lihat Bukti">
                                                <i data-feather="paperclip"></i>
                                            </a>
                                            @endif
                                            @if($item->status === 'pending')
                                            <button class="btn btn-datatable btn-icon btn-transparent-dark text-danger"
                                                onclick="confirmDelete({{ $item->id }})" title="Hapus">
                                                <i data-feather="trash-2"></i>
                                            </button>
                                            <form id="delete-form-{{ $item->id }}"
                                                action="{{ route('karyawan.reimbursement.destroy', $item->id) }}"
                                                method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                            @endif
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted py-4">Belum ada pengajuan
                                            reimbursement.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>

                        </div>
                    </div>
                </div>
                {{-- @if($item->buktiFile->count())
                <div class="modal fade" id="buktiModal{{ $item->id }}" tabindex="-1">

                    <div class="modal-dialog modal-lg">

                        <div class="modal-content">

                            <div class="modal-header">
                                <h5 class="modal-title">
                                    Bukti Reimbursement
                                </h5>

                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>

                            <div class="modal-body">

                                <div class="row">

                                    @foreach($item->bukti as $bukti)

                                    <div class="col-md-4 mb-3">

                                        @php
                                        $ext = strtolower(pathinfo($bukti->file, PATHINFO_EXTENSION));
                                        @endphp

                                        @if(in_array($ext,['jpg','jpeg','png','gif','webp']))

                                        <a href="{{ asset('storage/'.$bukti->file) }}" target="_blank">

                                            <img src="{{ asset('storage/'.$bukti->file) }}"
                                                class="img-fluid rounded border">

                                        </a>

                                        @else

                                        <a href="{{ asset('storage/'.$bukti->file) }}" target="_blank"
                                            class="btn btn-outline-danger w-100">

                                            <i data-feather="file-text"></i>
                                            Lihat PDF

                                        </a>

                                        @endif

                                    </div>

                                    @endforeach

                                </div>

                            </div>

                        </div>

                    </div>

                </div>
                @endif --}}
            </div>
        </div>
    </div>
</main>
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
    const input = document.querySelector('input[name="bukti[]"]');
    const list = document.getElementById('file-list');

    input.addEventListener('change', function () {
        list.innerHTML = '';

        if (this.files.length === 0) return;

        let html = '<strong>File dipilih:</strong><ul class="mb-0 mt-1">';

        Array.from(this.files).forEach(file => {
            html += `<li>${file.name}</li>`;
        });

        html += '</ul>';

        list.innerHTML = html;
    });
});
</script>
@endpush
@endsection