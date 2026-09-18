@extends('admin.layouts.app')

@section('title', 'Data Perusahaan')

@section('content')
    <main>
        <header class="page-header page-header-compact page-header-light border-bottom bg-white mb-4">
            <div class="container-xl px-4">
                <div class="page-header-content">
                    <div class="row align-items-center justify-content-between pt-3">
                        <div class="col-auto mb-3">
                            <h1 class="page-header-title">
                                <div class="page-header-icon"><i data-feather="briefcase"></i></div>Data Perusahaan
                            </h1>
                            {{-- <div class="page-header-subtitle">Kelola data perusahaan dan karyawan berdasarkan perusahaan.
                            </div> --}}
                        </div>
                        <div class="col-auto mb-3"><button class="btn btn-sm btn-light text-primary" data-bs-toggle="modal"
                                data-bs-target="#modalTambahPerusahaan"><i data-feather="plus" class="me-1"></i>Tambah
                                Perusahaan</button></div>
                    </div>
                </div>
            </div>
        </header>
        <div class="container-xl px-4">
            <div class="row g-4">
                @forelse($perusahaan as $item)
                    <div class="col-12 col-md-6 col-xl-4">
                        <div class="card h-100">
                            <div class="card-body">
                                <div class="d-flex align-items-start mb-3">
                                    @if ($item->logo)
                                        <img src="{{ asset('storage/' . $item->logo) }}"
                                            alt="Logo {{ $item->nama_perusahaan }}" class="rounded border me-3"
                                            style="width:64px;height:64px;object-fit:contain;">
                                    @else
                                        <div class="avatar avatar-xl bg-primary-soft text-primary me-3"><i
                                                data-feather="briefcase"></i></div>
                                    @endif
                                    <div class="flex-grow-1">
                                        <div class="d-flex justify-content-between gap-2">
                                            <h5 class="mb-1">{{ $item->nama_perusahaan }}</h5><span
                                                class="badge {{ $item->status === 'aktif' ? 'bg-green-soft text-green' : 'bg-red-soft text-red' }}">{{ ucfirst($item->status) }}</span>
                                        </div>
                                        <div class="small text-muted">{{ $item->nama_singkat ?: '-' }}</div>
                                    </div>
                                </div>
                                <dl class="row small mb-0">
                                    <dt class="col-4 text-muted fw-normal">NIB</dt>
                                    <dd class="col-8">{{ $item->nib }}</dd>
                                    <dt class="col-4 text-muted fw-normal">NPWP</dt>
                                    <dd class="col-8">{{ $item->npwp ?: '-' }}</dd>
                                    <dt class="col-4 text-muted fw-normal">Alamat</dt>
                                    <dd class="col-8">{{ $item->alamat ?: '-' }}</dd>
                                </dl>
                            </div>
                            <div class="card-footer bg-transparent d-flex justify-content-between align-items-center"><span
                                    class="small fw-semibold"><i data-feather="users"
                                        class="me-1"></i>{{ $item->karyawan_count }} Karyawan</span>
                                <div class="btn-group"><button class="btn btn-sm btn-light" data-bs-toggle="modal"
                                        data-bs-target="#modalKaryawan{{ $item->id }}" @disabled($item->status !== 'aktif')
                                        title="Atur Karyawan"><i data-feather="user-plus"></i></button><a
                                        href="{{ route('admin.perusahaan.show', $item) }}"
                                        class="btn btn-sm btn-light">Detail</a><button class="btn btn-sm btn-light"
                                        data-bs-toggle="modal" data-bs-target="#modalEdit{{ $item->id }}"><i
                                            data-feather="edit"></i></button><button
                                        class="btn btn-sm btn-light text-danger"
                                        onclick="hapusPerusahaan({{ $item->id }})"><i
                                            data-feather="trash-2"></i></button></div>
                            </div>
                            <form id="hapus-perusahaan-{{ $item->id }}" method="POST"
                                action="{{ route('admin.perusahaan.destroy', $item) }}" class="d-none">@csrf
                                @method('DELETE')</form>
                        </div>
                    </div>
                @empty <div class="col-12">
                        <div class="card">
                            <div class="card-body text-center text-muted py-5">Belum ada data perusahaan.</div>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </main>

    <div class="modal fade" id="modalTambahPerusahaan" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <form method="POST" action="{{ route('admin.perusahaan.store') }}" enctype="multipart/form-data"
                class="modal-content">@csrf @include('admin.perusahaan.partials.form', ['item' => null, 'modalId' => 'Tambah'])</form>
        </div>
    </div>
    @foreach ($perusahaan as $item)
        <div class="modal fade" id="modalEdit{{ $item->id }}" tabindex="-1">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <form method="POST" action="{{ route('admin.perusahaan.update', $item) }}" enctype="multipart/form-data"
                    class="modal-content">@csrf @method('PUT') @include('admin.perusahaan.partials.form', [
                        'item' => $item,
                        'modalId' => 'Edit' . $item->id,
                    ])</form>
            </div>
        </div>
    @endforeach
    @foreach ($perusahaan as $item)
        <div class="modal fade" id="modalKaryawan{{ $item->id }}" tabindex="-1">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <form method="POST" action="{{ route('admin.perusahaan.karyawan.assign', $item) }}" class="modal-content">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Atur Karyawan — {{ $item->nama_perusahaan }}</h5><button type="button"
                            class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p class="small text-muted">Pilih satu atau beberapa karyawan. Karyawan yang sudah terhubung ke
                            perusahaan lain akan dipindahkan ke perusahaan ini.</p>
                        <div class="input-group mb-3"><span class="input-group-text"><i
                                    data-feather="search"></i></span><input type="search"
                                class="form-control pencarian-karyawan" placeholder="Cari nama, NIK, atau jabatan...">
                        </div>
                        <div class="border rounded overflow-auto" style="max-height:340px;" data-daftar-karyawan>
                            @forelse($karyawanList as $karyawan)
                                <label class="d-flex align-items-center gap-3 p-3 border-bottom opsi-karyawan"><input
                                        class="form-check-input m-0" type="checkbox" name="karyawan_ids[]"
                                        value="{{ $karyawan->id }}" @checked($karyawan->perusahaan_id === $item->id)><span
                                        class="flex-grow-1"><span
                                            class="d-block fw-semibold">{{ $karyawan->nama }}</span><span
                                            class="small text-muted">{{ $karyawan->nik }} ·
                                            {{ $karyawan->jabatan?->nama_jabatan ?? '-' }} @if ($karyawan->perusahaan)
                                                · {{ $karyawan->perusahaan->nama_perusahaan }}
                                            @endif
                                    </span></span></label>@empty <div class="p-4 text-center text-muted">Belum ada
                                    karyawan yang dapat dipilih.</div>
                            @endforelse
                        </div>
                    </div>
                    <div class="modal-footer"><button type="button" class="btn btn-light"
                            data-bs-dismiss="modal">Batal</button><button class="btn btn-primary">Simpan
                            Penempatan</button></div>
                </form>
            </div>
        </div>
    @endforeach
@endsection

@push('scripts')
    <script>
        function hapusPerusahaan(id) {
            Swal.fire({
                title: 'Hapus perusahaan?',
                text: 'Data yang tidak memiliki karyawan akan dihapus.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, Hapus',
                cancelButtonText: 'Batal'
            }).then(r => {
                if (r.isConfirmed) document.getElementById('hapus-perusahaan-' + id).submit();
            });
        }
        document.querySelectorAll('[data-logo-input]').forEach(input => input.addEventListener('change', function() {
            const preview = document.getElementById(this.dataset.logoInput);
            if (this.files[0]) {
                preview.src = URL.createObjectURL(this.files[0]);
                preview.classList.remove('d-none');
            }
        }));
        document.querySelectorAll('.pencarian-karyawan').forEach(input => input.addEventListener('input', function() {
            const keyword = this.value.toLowerCase();
            this.closest('.modal-body').querySelectorAll('.opsi-karyawan').forEach(item => item.classList
                .toggle('d-none', !item.textContent.toLowerCase().includes(keyword)));
        }));
    </script>
@endpush
