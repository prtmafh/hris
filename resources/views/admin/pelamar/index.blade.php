@extends('admin.layouts.app')

@section('title', 'Pelamar')

@section('content')
<main>
    <header class="page-header page-header-dark bg-gradient-primary-to-secondary pb-10">
        <div class="container-xl px-4">
            <div class="page-header-content pt-4">
                <div class="row align-items-center justify-content-between">
                    <div class="col-auto mt-4">
                        <h1 class="page-header-title">
                            <div class="page-header-icon"><i data-feather="users"></i></div>
                            Pelamar
                        </h1>
                        <div class="page-header-subtitle">Kelola data pelamar dari halaman karir</div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div class="container-xl px-4 mt-n10">
        <div class="card mb-4">
            <div class="card-body">
                <form method="GET" action="{{ route('admin.pelamar') }}" class="row g-2 align-items-end">
                    <div class="col-md-5">
                        <label class="form-label">Lowongan</label>
                        <select name="lowongan_id" class="form-select">
                            <option value="">Semua Lowongan</option>
                            @foreach($lowongan as $item)
                            <option value="{{ $item->id }}" {{ request('lowongan_id') == $item->id ? 'selected' : '' }}>
                                {{ $item->judul }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="">Semua Status</option>
                            @foreach(['pending', 'screening', 'interview', 'offering', 'diterima', 'ditolak'] as $status)
                            <option value="{{ $status }}" {{ request('status') === $status ? 'selected' : '' }}>
                                {{ ucfirst($status) }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <button class="btn btn-primary w-100" type="submit">
                            <i data-feather="filter" class="me-1"></i> Filter
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header">Daftar Pelamar</div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="datatablesSimple" class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Pelamar</th>
                                <th>Lowongan</th>
                                <th>Dokumen</th>
                                <th>Status</th>
                                <th>Tanggal Lamar</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pelamar as $index => $item)
                            @php
                            $statusClass = [
                                'pending' => 'secondary',
                                'screening' => 'info',
                                'interview' => 'primary',
                                'offering' => 'warning',
                                'diterima' => 'success',
                                'ditolak' => 'danger',
                            ][$item->status] ?? 'secondary';
                            @endphp
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    <div class="fw-bold">{{ $item->nama }}</div>
                                    <small class="text-muted">{{ $item->email }}</small>
                                    <div class="small text-muted">{{ $item->no_hp ?: '-' }}</div>
                                </td>
                                <td>
                                    <div class="fw-semibold">{{ $item->lowongan->judul ?? '-' }}</div>
                                    <small class="text-muted">{{ optional(optional($item->lowongan)->jabatan)->nama_jabatan ?? '-' }}</small>
                                </td>
                                <td>
                                    <a href="{{ asset('storage/' . $item->cv) }}" target="_blank"
                                        class="btn btn-sm btn-outline-primary mb-1">
                                        <i data-feather="file-text"></i> CV
                                    </a>
                                    @if($item->foto)
                                    <a href="{{ asset('storage/' . $item->foto) }}" target="_blank"
                                        class="btn btn-sm btn-outline-secondary mb-1">
                                        <i data-feather="image"></i> Foto
                                    </a>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-{{ $statusClass }}">{{ ucfirst($item->status) }}</span>
                                    @if($item->jadwal_interview)
                                    <div class="small text-muted mt-1">
                                        Interview: {{ $item->jadwal_interview->format('d/m/Y H:i') }}
                                    </div>
                                    @endif
                                </td>
                                <td>{{ optional($item->applied_at)->format('d/m/Y H:i') }}</td>
                                <td class="text-center">
                                    <button class="btn btn-datatable btn-icon btn-info" data-bs-toggle="modal"
                                        data-bs-target="#modalDetail{{ $item->id }}" title="Detail">
                                        <i data-feather="eye"></i>
                                    </button>
                                    <button class="btn btn-datatable btn-icon btn-warning mx-1" data-bs-toggle="modal"
                                        data-bs-target="#modalEdit{{ $item->id }}" title="Update Status">
                                        <i data-feather="edit"></i>
                                    </button>
                                    <button class="btn btn-datatable btn-icon btn-primary" data-bs-toggle="modal"
                                        data-bs-target="#modalPanggilan{{ $item->id }}" title="Kirim Panggilan">
                                        <i data-feather="mail"></i>
                                    </button>
                                    <button class="btn btn-datatable btn-icon btn-success mx-1" data-bs-toggle="modal"
                                        data-bs-target="#modalUpdateProses{{ $item->id }}" title="Kirim Update Proses">
                                        <i data-feather="send"></i>
                                    </button>
                                    <button class="btn btn-datatable btn-icon btn-danger"
                                        onclick="confirmDeletePelamar({{ $item->id }})" title="Hapus">
                                        <i data-feather="trash-2"></i>
                                    </button>

                                    <form id="delete-pelamar-{{ $item->id }}"
                                        action="{{ route('admin.pelamar.destroy', $item->id) }}" method="POST"
                                        class="d-none">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </td>
                            </tr>

                            <div class="modal fade" id="modalDetail{{ $item->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-lg modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Detail Pelamar</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <label class="text-muted small">Nama</label>
                                                    <div class="fw-bold">{{ $item->nama }}</div>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label class="text-muted small">Email</label>
                                                    <div>{{ $item->email }}</div>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label class="text-muted small">No. HP</label>
                                                    <div>{{ $item->no_hp ?: '-' }}</div>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label class="text-muted small">Tanggal Lahir</label>
                                                    <div>{{ optional($item->tanggal_lahir)->format('d/m/Y') ?: '-' }}</div>
                                                </div>
                                                <div class="col-md-12 mb-3">
                                                    <label class="text-muted small">Alamat</label>
                                                    <div>{{ $item->alamat ?: '-' }}</div>
                                                </div>
                                                <div class="col-md-12 mb-3">
                                                    <label class="text-muted small">Lowongan</label>
                                                    <div class="fw-bold">{{ $item->lowongan->judul ?? '-' }}</div>
                                                </div>
                                                <div class="col-md-12">
                                                    <label class="text-muted small">Catatan HR</label>
                                                    <div>{{ $item->catatan_hr ?: '-' }}</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-light"
                                                data-bs-dismiss="modal">Tutup</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="modal fade" id="modalEdit{{ $item->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Update Pelamar</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <form action="{{ route('admin.pelamar.update', $item->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold">Status</label>
                                                    <select name="status" class="form-select" required>
                                                        @foreach(['pending', 'screening', 'interview', 'offering', 'diterima', 'ditolak'] as $status)
                                                        <option value="{{ $status }}" {{ $item->status === $status ? 'selected' : '' }}>
                                                            {{ ucfirst($status) }}
                                                        </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold">Jadwal Interview</label>
                                                    <input type="datetime-local" name="jadwal_interview"
                                                        class="form-control"
                                                        value="{{ $item->jadwal_interview ? $item->jadwal_interview->format('Y-m-d\\TH:i') : '' }}">
                                                </div>
                                                <div class="mb-0">
                                                    <label class="form-label fw-bold">Catatan HR</label>
                                                    <textarea name="catatan_hr" class="form-control" rows="4"
                                                        placeholder="Catatan proses rekrutmen">{{ $item->catatan_hr }}</textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-light"
                                                    data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <div class="modal fade" id="modalPanggilan{{ $item->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Kirim Email Panggilan</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <form action="{{ route('admin.pelamar.kirim-panggilan', $item->id) }}"
                                            method="POST">
                                            @csrf
                                            <div class="modal-body">
                                                <div class="alert alert-info small">
                                                    Email akan dikirim ke <strong>{{ $item->email }}</strong> dan status
                                                    pelamar otomatis menjadi <strong>Interview</strong>.
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold">Jadwal Interview <span
                                                            class="text-danger">*</span></label>
                                                    <input type="datetime-local" name="jadwal_interview"
                                                        class="form-control"
                                                        value="{{ $item->jadwal_interview ? $item->jadwal_interview->format('Y-m-d\\TH:i') : '' }}"
                                                        required>
                                                </div>
                                                <div class="mb-0">
                                                    <label class="form-label fw-bold">Isi Pesan <span
                                                            class="text-danger">*</span></label>
                                                    <textarea name="pesan" class="form-control" rows="6"
                                                        required>Mohon hadir tepat waktu dan membawa dokumen pendukung yang diperlukan. Jika ada kendala, silakan menghubungi pihak HR.</textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-light"
                                                    data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-primary">
                                                    <i data-feather="send" class="me-1"></i> Kirim Email
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <div class="modal fade" id="modalUpdateProses{{ $item->id }}" tabindex="-1"
                                aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Kirim Update Proses Lamaran</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <form action="{{ route('admin.pelamar.kirim-update-proses', $item->id) }}"
                                            method="POST">
                                            @csrf
                                            <div class="modal-body">
                                                <div class="alert alert-success small">
                                                    Email akan dikirim ke <strong>{{ $item->email }}</strong>. Status,
                                                    jadwal, dan catatan juga akan tampil di tracking pelamar.
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold">Status Proses <span
                                                            class="text-danger">*</span></label>
                                                    <select name="status" class="form-select" required>
                                                        @foreach(['pending', 'screening', 'interview', 'offering', 'diterima', 'ditolak'] as $status)
                                                        <option value="{{ $status }}" {{ $item->status === $status ? 'selected' : '' }}>
                                                            {{ ucfirst($status) }}
                                                        </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold">Jadwal Interview</label>
                                                    <input type="datetime-local" name="jadwal_interview"
                                                        class="form-control"
                                                        value="{{ $item->jadwal_interview ? $item->jadwal_interview->format('Y-m-d\\TH:i') : '' }}">
                                                    <small class="text-muted">Isi jika update proses berkaitan dengan
                                                        interview.</small>
                                                </div>
                                                <div class="mb-0">
                                                    <label class="form-label fw-bold">Isi Pesan <span
                                                            class="text-danger">*</span></label>
                                                    <textarea name="pesan" class="form-control" rows="6"
                                                        required>Terima kasih telah mengikuti proses rekrutmen PT. Tidarjaya Solidindo. Berikut kami sampaikan pembaruan status lamaran Anda. Silakan cek informasi ini dan ikuti arahan selanjutnya dari pihak HR.</textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-light"
                                                    data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-success">
                                                    <i data-feather="send" class="me-1"></i> Kirim Update
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">Belum ada data pelamar.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection

@push('scripts')
<script>
    function confirmDeletePelamar(id) {
        Swal.fire({
            title: 'Hapus Pelamar?',
            text: 'Data dan dokumen pelamar akan dihapus permanen.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-pelamar-' + id).submit();
            }
        });
    }
</script>
@endpush
