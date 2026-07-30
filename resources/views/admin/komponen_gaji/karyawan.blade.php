@extends('admin.layouts.app')

@section('title', 'Komponen Gaji Karyawan')

@section('content')
    <main>
        <header class="page-header page-header-compact page-header-light border-bottom bg-white mb-4">
            <div class="container-xl px-4">
                <div class="page-header-content">
                    <div class="row align-items-center justify-content-between pt-3">
                        <div class="col-auto mb-3">
                            <h1 class="page-header-title">
                                <div class="page-header-icon"><i data-feather="user-check"></i></div>
                                Komponen Gaji Karyawan
                            </h1>
                        </div>
                        @if ($karyawan && $komponenTersedia->isNotEmpty())
                            <div class="col-auto mb-3">
                                <button class="btn btn-sm btn-light text-primary" data-bs-toggle="modal"
                                    data-bs-target="#modalTambah">
                                    <i data-feather="plus" class="me-1"></i>Tambah Komponen
                                </button>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </header>

        <div class="container-xl px-4">
            <div class="card mb-4">
                <div class="card-header">Pilih Karyawan</div>
                <div class="card-body">
                    <form method="GET" action="{{ route('admin.komponen-gaji.karyawan') }}" class="row align-items-end">
                        <div class="col-md-8">
                            <label class="small mb-1">Karyawan</label>
                            <select name="karyawan_id" class="form-select" required>
                                @foreach ($karyawanList as $item)
                                    <option value="{{ $item->id }}" @selected($karyawan?->id === $item->id)>
                                        {{ $item->nama }} — {{ $item->nik }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 mt-2 mt-md-0">
                            <button class="btn btn-primary">Tampilkan</button>
                        </div>
                    </form>
                </div>
            </div>

            @if ($karyawan)
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div>
                            <div class="fw-semibold">{{ $karyawan->nama }}</div>
                            <div class="small text-muted">
                                {{ ucfirst($karyawan->status_gaji) }} · Gaji dasar
                                Rp
                                {{ number_format($karyawan->status_gaji === 'bulanan' ? $karyawan->gaji_pokok : $karyawan->gaji_per_hari, 0, ',', '.') }}
                            </div>
                        </div>
                        <span class="badge bg-primary">{{ $pengaturan->count() }} komponen</span>
                    </div>
                    <div class="card-body">
                        @php
                            $kelompokKomponen = [
                                [
                                    'judul' => 'Pemasukan',
                                    'ikon' => 'trending-up',
                                    'warna' => 'text-green',
                                    'data' => $pengaturan->filter(fn($item) => $item->komponen->tipe === 'pemasukan'),
                                ],
                                [
                                    'judul' => 'Potongan',
                                    'ikon' => 'trending-down',
                                    'warna' => 'text-red',
                                    'data' => $pengaturan->filter(fn($item) => $item->komponen->tipe === 'potongan'),
                                ],
                            ];
                        @endphp

                        <div class="row g-4">
                            @foreach ($kelompokKomponen as $kelompok)
                                <div class="col-12 col-xl-6">
                                    <div class="border rounded h-100 overflow-hidden">
                                        <div
                                            class="d-flex align-items-center justify-content-between bg-light border-bottom px-3 py-3">
                                            <div class="d-flex align-items-center fw-semibold {{ $kelompok['warna'] }}">
                                                <i data-feather="{{ $kelompok['ikon'] }}" class="me-2"></i>
                                                {{ $kelompok['judul'] }}
                                            </div>
                                            <span class="badge bg-secondary-soft text-secondary">
                                                {{ $kelompok['data']->count() }} komponen
                                            </span>
                                        </div>

                                        <div class="table-responsive">
                                            <table class="table table-hover align-middle mb-0">
                                                <thead>
                                                    <tr>
                                                        <th>Komponen &amp; Nilai</th>
                                                        <th>Masa Berlaku</th>
                                                        <th>Status</th>
                                                        <th class="text-center">Aksi</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @forelse($kelompok['data'] as $item)
                                                        <tr>
                                                            <td>
                                                                <div class="fw-semibold text-capitalize">
                                                                    {{ $item->komponen->nama }}
                                                                </div>
                                                                <div class="small text-muted text-nowrap">
                                                                    @if ($item->metode === 'persentase')
                                                                        {{ number_format($item->nilai, 2, ',', '.') }}%
                                                                        dari gaji dasar
                                                                    @else
                                                                        Rp {{ number_format($item->nilai, 0, ',', '.') }}
                                                                    @endif
                                                                </div>
                                                            </td>
                                                            <td class="text-nowrap">
                                                                {{ $item->tanggal_mulai?->format('d/m/Y') ?? 'Tanpa batas awal' }}
                                                                <div class="small text-muted">s.d.
                                                                    {{ $item->tanggal_selesai?->format('d/m/Y') ?? 'seterusnya' }}
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <span
                                                                    class="badge {{ $item->status === 'aktif' ? 'bg-green-soft text-green' : 'bg-secondary-soft text-secondary' }}">
                                                                    {{ ucfirst($item->status) }}
                                                                </span>
                                                            </td>
                                                            <td class="text-center text-nowrap">
                                                                <button
                                                                    class="btn btn-datatable btn-icon btn-transparent-dark"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#modalEdit{{ $item->id }}">
                                                                    <i data-feather="edit"></i>
                                                                </button>
                                                                <button type="button"
                                                                    class="btn btn-datatable btn-icon btn-transparent-dark text-danger"
                                                                    onclick="confirmDelete({{ $item->id }})">
                                                                    <i data-feather="trash-2"></i>
                                                                </button>
                                                                <form id="delete-form-{{ $item->id }}"
                                                                    action="{{ route('admin.komponen-gaji.karyawan.destroy', $item->id) }}"
                                                                    method="POST" class="d-none">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                </form>
                                                            </td>
                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <td colspan="4" class="text-center text-muted py-4">
                                                                Belum ada {{ strtolower($kelompok['judul']) }}.
                                                            </td>
                                                        </tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @else
                <div class="alert alert-warning">Belum ada data karyawan.</div>
            @endif
        </div>
    </main>

    @if ($karyawan && $komponenTersedia->isNotEmpty())
        <div class="modal fade" id="modalTambah" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <form method="POST" action="{{ route('admin.komponen-gaji.karyawan.store') }}" class="modal-content">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Tambah Komponen untuk {{ $karyawan->nama }}</h5><button type="button"
                            class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        @include('admin.komponen_gaji.partials.form-karyawan', [
                            'pengaturanItem' => null,
                            'pilihanKomponen' => $komponenTersedia,
                        ])
                    </div>
                    <div class="modal-footer"><button type="button" class="btn btn-light"
                            data-bs-dismiss="modal">Batal</button><button class="btn btn-primary">Simpan</button></div>
                </form>
            </div>
        </div>
    @endif
    @forelse($pengaturan as $item)
        <div class="modal fade" id="modalEdit{{ $item->id }}" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <form method="POST" action="{{ route('admin.komponen-gaji.karyawan.update', $item->id) }}"
                    class="modal-content">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Komponen Karyawan</h5><button type="button" class="btn-close"
                            data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        @include('admin.komponen_gaji.partials.form-karyawan', [
                            'pengaturanItem' => $item,
                            'pilihanKomponen' => collect([$item->komponen]),
                        ])
                    </div>
                    <div class="modal-footer"><button type="button" class="btn btn-light"
                            data-bs-dismiss="modal">Batal</button><button class="btn btn-primary">Simpan</button></div>
                </form>
            </div>
        </div>
    @empty
    @endforelse
@endsection
