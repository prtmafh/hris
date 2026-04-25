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
                            data-bs-target="#modalGenerateGaji">
                            <i data-feather="cpu" class="me-1"></i>
                            Generate Gaji
                        </button>
                    </div>

                </div>
            </div>
        </div>
    </header>


    <div class="container-xl px-4">

        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm">
            {{ session('success') }}
            <button class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif


        {{-- FILTER --}}
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">

                <div>
                    <div class="fw-semibold">Filter Data</div>
                    <div class="small text-muted">Cari data penggajian dengan cepat</div>
                </div>

                @if($hasFilter)
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
                            @for($i=1;$i<=12;$i++) <option value="{{ $i }}" {{ request('bulan')==$i ? 'selected' : ''
                                }}>
                                {{ $i }}
                                </option>
                                @endfor
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label class="small mb-1">Tahun</label>
                        <input type="number" name="tahun" class="form-control form-control-sm"
                            value="{{ request('tahun') }}">
                    </div>

                    <div class="col-md-4">
                        <label class="small mb-1">Karyawan</label>
                        <select name="karyawan_id" class="form-select form-select-sm">
                            <option value="">Semua</option>

                            @foreach($karyawanList as $k)
                            <option value="{{ $k->id }}" {{ request('karyawan_id')==$k->id ? 'selected':'' }}>
                                {{ $k->nama }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label class="small mb-1">Status</label>
                        <select name="status" class="form-select form-select-sm">

                            <option value="">Semua</option>

                            <option value="proses" {{ request('status')=='proses' ? 'selected' :'' }}>
                                Proses
                            </option>

                            <option value="dibayar" {{ request('status')=='dibayar' ? 'selected' :'' }}>
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

                <table id="datatablesSimple" class="table ">

                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Karyawan</th>
                            <th>Periode</th>
                            <th>Status</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($penggajian as $index => $g)
                        <tr>

                            <td>
                                {{ $penggajian->firstItem() + $index }}
                            </td>

                            <td>
                                <div class="d-flex align-items-center">

                                    <div class="avatar me-2">
                                        <img class="avatar-img img-fluid" src="{{ $g->karyawan->foto
                                        ? asset('storage/'.$g->karyawan->foto)
                                        : 'https://ui-avatars.com/api/?name='.urlencode($g->karyawan->nama) }}">
                                    </div>

                                    <div>
                                        <div class="fw-semibold text-capitalize">
                                            {{ $g->karyawan->nama }}
                                        </div>

                                        <div class="small text-muted">
                                            Payroll #{{ $g->id }}
                                        </div>
                                    </div>

                                </div>
                            </td>

                            <td>
                                <span class="fw-semibold">
                                    {{ $g->periode_bulan }}/{{ $g->periode_tahun }}
                                </span>
                            </td>

                            <td>
                                @if($g->status=='dibayar')
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

                                <a href="{{ route('admin.penggajian.show',$g->id) }}"
                                    class="btn btn-datatable btn-icon btn-transparent-dark">
                                    <i data-feather="eye"></i>
                                </a>

                            </td>

                        </tr>

                        @empty

                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">
                                Belum ada data penggajian
                            </td>
                        </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>
        </div>

    </div>
</main>



{{-- MODAL GENERATE (LOGIC TIDAK DIUBAH, HANYA STYLE) --}}
<div class="modal fade" id="modalGenerateGaji" tabindex="-1">

    <div class="modal-dialog modal-dialog-centered">
        <form method="POST" action="{{ route('admin.penggajian.generate') }}">
            @csrf

            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">
                        <i data-feather="cpu"></i>
                        Generate Gaji Otomatis
                    </h5>

                    <button class="btn-close" data-bs-dismiss="modal"></button>
                </div>


                <div class="modal-body">

                    <div class="alert alert-primary-soft mb-4">
                        Sistem akan generate penggajian otomatis untuk periode terpilih.
                    </div>

                    <div class="row gx-3">

                        <div class="col-md-6">
                            <label class="small mb-1">
                                Bulan
                            </label>

                            <select name="bulan" class="form-select" required>

                                @php
                                $namaBulan=['','Januari','Februari','Maret','April','Mei','Juni',
                                'Juli','Agustus','September','Oktober','November','Desember'];
                                @endphp

                                @for($i=1;$i<=12;$i++) <option value="{{ $i }}" {{ $i==now()->month?'selected':'' }}>
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

                                @for($y=now()->year;$y>=now()->year-3;$y--)
                                <option value="{{ $y }}" {{ $y==now()->year?'selected':'' }}>
                                    {{ $y }}
                                </option>
                                @endfor

                            </select>
                        </div>

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

            </div>
        </form>
    </div>
</div>

@endsection