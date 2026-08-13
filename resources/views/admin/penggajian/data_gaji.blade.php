@extends('admin.layouts.app')

@section('title', 'Data Penggajian')

@section('content')
    <main>

        <header class="page-header page-header-compact page-header-light border-bottom bg-white mb-4">
            <div class="container-xl px-4">
                <div class="page-header-content">
                    <div class="row align-items-center justify-content-between pt-3">

                        <div class="col-auto mb-3">
                            <h1 class="page-header-title">
                                <div class="page-header-icon">
                                    <i data-feather="dollar-sign"></i>
                                </div>
                                Data Penggajian
                            </h1>
                        </div>

                        <div class="col-auto mb-3">
                            <button class="btn btn-sm btn-light text-primary" data-bs-toggle="modal"
                                data-bs-target="#modalGenerateGaji" data-jenis="bulanan">Generate Gaji Bulanan</button>
                            <button class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                data-bs-target="#modalGenerateGaji" data-jenis="harian">Generate Gaji Harian</button>
                        </div>

                    </div>
                </div>
            </div>
        </header>


        <div class="container-xl px-4">

            {{-- @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm">
            {{ session('success') }}
            <button class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif
        --}}

            {{-- FILTER --}}
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">

                    <div>
                        <div class="fw-semibold">Filter Data Penggajian</div>
                        {{-- <div class="small text-muted">Cari data penggajian dengan cepat</div> --}}
                    </div>

                    @if ($hasFilter)
                        <a href="{{ route('admin.penggajian') }}" class="btn btn-light btn-sm">
                            <i data-feather="x"></i>
                        </a>
                    @endif

                </div>

                <div class="card-body">

                    <form method="GET" action="{{ route('admin.penggajian') }}" class="row gx-2 gy-2 align-items-end">

                        <div class="col-md-2">
                            <label class="small mb-1">Bulan</label>
                            <select name="bulan" class="form-select form-select-sm">
                                <option value="">Semua</option>
                                @for ($i = 1; $i <= 12; $i++)
                                    <option value="{{ $i }}" {{ request('bulan') == $i ? 'selected' : '' }}>
                                        {{ $i }}
                                    </option>
                                @endfor
                            </select>
                        </div>

                        <div class="col-md-2">
                            <label class="form-label small mb-1">Tahun</label>
                            <select name="tahun" class="form-select form-select-sm">
                                @foreach ($daftarTahun as $t)
                                    <option value="{{ $t }}" {{ $tahun == $t ? 'selected' : '' }}>
                                        {{ $t }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-2">
                            <label class="small mb-1">Karyawan</label>
                            <select name="karyawan_id" class="form-select form-select-sm">
                                <option value="">Semua</option>

                                @foreach ($karyawanList as $k)
                                    <option value="{{ $k->id }}"
                                        {{ request('karyawan_id') == $k->id ? 'selected' : '' }}>
                                        {{ $k->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-2">
                            <label class="small mb-1">Jenis</label>
                            <select name="jenis_gaji" class="form-select form-select-sm">
                                <option value="">Semua</option>
                                <option value="bulanan" @selected(request('jenis_gaji') === 'bulanan')>Bulanan</option>
                                <option value="harian" @selected(request('jenis_gaji') === 'harian')>Harian</option>
                            </select>
                        </div>

                        <div class="col-md-2">
                            <label class="small mb-1">Status</label>
                            <select name="status" class="form-select form-select-sm">

                                <option value="">Semua</option>

                                <option value="proses" {{ request('status') == 'proses' ? 'selected' : '' }}>
                                    Proses
                                </option>

                                <option value="dibayar" {{ request('status') == 'dibayar' ? 'selected' : '' }}>
                                    Dibayar
                                </option>

                            </select>
                        </div>

                        <div class="col-md-2 d-flex gap-1">
                            <button class="btn btn-primary btn-sm w-100">
                                <i data-feather="search"></i>
                            </button>
                        </div>

                    </form>

                </div>
            </div>


            {{-- TABLE --}}
            <div class="card">

                <div class="card-header">
                    Daftar Gaji Karyawan
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table id="datatablesSimple" class="table ">

                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Karyawan</th>
                                    <th>Jenis Karyawan</th>
                                    <th>Periode</th>
                                    <th>Status</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>

                            <tbody>

                                @forelse($penggajian as $index => $g)
                                    <tr>

                                        <td>
                                            {{ $index + 1 }}
                                        </td>

                                        <td>
                                            <div class="d-flex align-items-center">

                                                <div class="avatar me-2">
                                                    <img class="avatar-img img-fluid"
                                                        src="{{ $g->karyawan->foto
                                                            ? asset('storage/' . $g->karyawan->foto)
                                                            : 'https://ui-avatars.com/api/?name=' . urlencode($g->karyawan->nama) }}">
                                                </div>

                                                <div>
                                                    <div class="fw-semibold text-capitalize">
                                                        {{ $g->karyawan->nama }}
                                                    </div>
                                                    {{--
                                            <div class="small text-muted">
                                                Payroll #{{ $g->id }}
                                            </div> --}}
                                                </div>

                                            </div>
                                        </td>
                                        <td>
                                            @if ($g->karyawan->status_gaji === 'harian')
                                                <span class="badge bg-blue-soft text-blue">Harian</span>
                                            @else
                                                <span class="badge bg-purple-soft text-purple">Bulanan</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="fw-semibold">
                                                @if ($g->karyawan->status_gaji === 'harian' && $g->tanggal_mulai && $g->tanggal_selesai)
                                                    {{ $g->tanggal_mulai->format('d/m/Y') }} -
                                                    {{ $g->tanggal_selesai->format('d/m/Y') }}
                                                @else
                                                    {{ $g->periode_bulan }}/{{ $g->periode_tahun }}
                                                @endif
                                            </span>
                                        </td>

                                        <td>
                                            @if ($g->status == 'dibayar')
                                                <span class="badge bg-green-soft text-green">
                                                    Dibayar
                                                </span>
                                            @else
                                                <span class="badge bg-yellow-soft text-yellow">
                                                    Proses
                                                </span>
                                            @endif
                                        </td>

                                        <td class="text-center">

                                            <a href="{{ route('admin.penggajian.show', $g->id) }}"
                                                class="btn btn-datatable btn-icon btn-transparent-dark">
                                                <i data-feather="eye"></i>
                                            </a>

                                            <button type="button"
                                                class="btn btn-datatable btn-icon btn-transparent-dark text-danger"
                                                title="Hapus data penggajian" onclick="confirmDelete({{ $g->id }})">
                                                <i data-feather="trash-2"></i>
                                            </button>

                                            <form id="delete-form-{{ $g->id }}"
                                                action="{{ route('admin.penggajian.destroy', $g->id) }}" method="POST"
                                                class="d-none">
                                                @csrf
                                                @method('DELETE')
                                            </form>

                                        </td>



                                    </tr>

                                @empty

                                    <tr>
                                        <td colspan="6" class="text-center text-muted py-4">
                                            Belum ada data penggajian
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
    {{-- MODAL GENERATE --}}
    <div class="modal fade" id="modalGenerateGaji" tabindex="-1">

        <div class="modal-dialog modal-dialog-centered">


            <form id="formGenerateGaji" method="POST" action="{{ route('admin.penggajian.generate') }}"
                class="modal-content">
                @csrf
                <input type="hidden" name="jenis_gaji" id="jenisGaji" value="bulanan">

                <div class="modal-header">
                    <h5 class="modal-title">
                        <i data-feather="cpu"></i>
                        Generate Gaji <span id="labelJenisGaji">Bulanan</span>
                    </h5>

                    <button class="btn-close" data-bs-dismiss="modal"></button>
                </div>


                <div class="modal-body">

                    <div class="alert alert-primary-soft mb-4">
                        Hanya karyawan aktif dengan jenis gaji terpilih yang akan diproses.
                    </div>

                    <div id="periodeBulanan" class="row gx-3">

                        <div class="col-md-6">
                            <label class="small mb-1">
                                Bulan
                            </label>
                            <select name="bulan" class="form-select" required>

                                @php
                                    $namaBulan = [
                                        '',
                                        'Januari',
                                        'Februari',
                                        'Maret',
                                        'April',
                                        'Mei',
                                        'Juni',
                                        'Juli',
                                        'Agustus',
                                        'September',
                                        'Oktober',
                                        'November',
                                        'Desember',
                                    ];
                                @endphp

                                @for ($i = 1; $i <= 12; $i++)
                                    <option value="{{ $i }}" {{ $i == now()->month ? 'selected' : '' }}>
                                        {{ $namaBulan[$i] }}
                                    </option>
                                @endfor

                            </select>
                        </div>


                        <div class="col-md-6">
                            <label class="small mb-1">
                                Tahun
                            </label>

                            <select name="tahun" class="form-select" required>

                                @for ($y = now()->year; $y >= now()->year - 3; $y--)
                                    <option value="{{ $y }}" {{ $y == now()->year ? 'selected' : '' }}>
                                        {{ $y }}
                                    </option>
                                @endfor

                            </select>
                        </div>

                    </div>

                    <div id="periodeHarian" class="d-none mt-4">
                        <div class="mb-4">
                            <label class="small mb-2">Tanggal Mulai</label>
                            <input type="date" name="tanggal_mulai" class="form-control"
                                value="{{ now()->startOfMonth()->toDateString() }}">
                        </div>
                        <div class="mb-3">
                            <label class="small mb-2">Tanggal Selesai</label>
                            <input type="date" name="tanggal_selesai" class="form-control"
                                value="{{ now()->toDateString() }}">
                        </div>
                        <small class="text-muted d-block mt-3">
                            Tanggal mulai dan selesai harus berada dalam bulan yang sama.
                        </small>
                    </div>

                </div>


                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                        Batal
                    </button>

                    <button type="submit" class="btn btn-primary">
                        Generate
                    </button>
                </div>

            </form>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        const modalGenerateGaji = document.getElementById('modalGenerateGaji');
        modalGenerateGaji.addEventListener('show.bs.modal', (event) => {
            const jenis = event.relatedTarget.dataset.jenis;
            document.getElementById('jenisGaji').value = jenis;
            document.getElementById('labelJenisGaji').textContent = jenis === 'harian' ? 'Harian' : 'Bulanan';
            document.getElementById('periodeBulanan').classList.toggle('d-none', jenis === 'harian');
            document.getElementById('periodeHarian').classList.toggle('d-none', jenis !== 'harian');
        });

        document.getElementById('formGenerateGaji').addEventListener('submit', function(event) {
            event.preventDefault();
            const jenis = document.getElementById('jenisGaji').value;
            const periode = jenis === 'harian' ?
                `${this.querySelector('[name="tanggal_mulai"]').value} sampai ${this.querySelector('[name="tanggal_selesai"]').value}` :
                `${this.querySelector('[name="bulan"] option:checked').text.trim()} ${this.querySelector('[name="tahun"]').value}`;
            Swal.fire({
                title: `Generate Gaji Karyawan ${jenis === 'harian' ? 'Harian' : 'Bulanan'}?`,
                text: `Periode: ${periode}. Hanya karyawan aktif dengan status gaji ${jenis} yang akan diproses.`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, Generate',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) this.submit();
            });
        });
    </script>
@endpush
