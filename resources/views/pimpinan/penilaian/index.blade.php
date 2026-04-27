@extends('pimpinan.layouts.app')

@section('title', 'Penilaian Karyawan')

@section('content')
<main>
    <header class="page-header page-header-dark bg-gradient-primary-to-secondary pb-10">
        <div class="container-xl px-4">
            <div class="page-header-content pt-4">
                <div class="row align-items-center justify-content-between">
                    <div class="col-auto mt-4">
                        <h1 class="page-header-title">
                            <div class="page-header-icon"><i data-feather="star"></i></div>
                            Penilaian Karyawan
                        </h1>
                        <div class="page-header-subtitle">Evaluasi kinerja karyawan per periode</div>
                    </div>
                    <div class="col-auto mt-4">
                        <a href="{{ route('pimpinan.penilaian.create') }}" class="btn btn-light btn-sm">
                            <i data-feather="plus" style="width:16px;height:16px;"></i> Beri Penilaian
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div class="container-xl px-4 mt-n10">

        <!-- Filter -->
        <div class="card mb-4">
            <div class="card-header"><i data-feather="filter" class="me-2"></i> Filter</div>
            <div class="card-body">
                <form method="GET" action="{{ route('pimpinan.penilaian.index') }}">
                    <div class="row g-3 align-items-end">
                        <div class="col-md-3">
                            <label class="form-label">Bulan</label>
                            <select name="bulan" class="form-select">
                                @foreach(range(1,12) as $b)
                                    <option value="{{ $b }}" {{ $bulan == $b ? 'selected' : '' }}>
                                        {{ \Carbon\Carbon::create()->month($b)->locale('id')->isoFormat('MMMM') }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Tahun</label>
                            <select name="tahun" class="form-select">
                                @foreach($tahunList as $t)
                                    <option value="{{ $t }}" {{ $tahun == $t ? 'selected' : '' }}>{{ $t }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Jabatan</label>
                            <select name="jabatan_id" class="form-select">
                                <option value="">Semua Jabatan</option>
                                @foreach($jabatan as $j)
                                    <option value="{{ $j->id }}" {{ $jabatanId == $j->id ? 'selected' : '' }}>{{ $j->nama_jabatan }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary w-100">Tampilkan</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Rekap Grade -->
        <div class="row mb-4">
            @foreach(['A'=>['success','Sangat Baik'],'B'=>['primary','Baik'],'C'=>['warning','Cukup'],'D'=>['danger','Kurang']] as $grade => [$color, $label])
            <div class="col-6 col-xl-3 mb-3">
                <div class="card border-start-lg border-{{ $color }} h-100">
                    <div class="card-body d-flex align-items-center justify-content-between">
                        <div>
                            <div class="small text-muted">Grade {{ $grade }} — {{ $label }}</div>
                            <div class="h3 fw-bold text-{{ $color }}">{{ $rekapGrade[$grade] }}</div>
                        </div>
                        <span class="badge bg-{{ $color }} fs-4 px-3 py-2">{{ $grade }}</span>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Tabel -->
        <div class="card">
            <div class="card-header card-header-actions">
                Penilaian —
                {{ \Carbon\Carbon::create()->month($bulan)->locale('id')->isoFormat('MMMM') }} {{ $tahun }}
                <span class="badge bg-primary ms-2">{{ $penilaian->count() }} karyawan</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped table-hover mb-0" id="datatablesSimple">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">No</th>
                                <th>Nama Karyawan</th>
                                <th>Jabatan</th>
                                <th>Kehadiran</th>
                                <th>Kedisiplinan</th>
                                <th>Kinerja</th>
                                <th>Nilai Total</th>
                                <th>Grade</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($penilaian as $i => $p)
                            <tr>
                                <td class="ps-4">{{ $i + 1 }}</td>
                                <td class="text-capitalize fw-semibold">{{ $p->karyawan->nama ?? '-' }}</td>
                                <td class="text-muted small">{{ $p->karyawan->jabatan->nama_jabatan ?? '-' }}</td>
                                <td>{{ number_format($p->nilai_kehadiran, 1) }}</td>
                                <td>{{ number_format($p->nilai_kedisiplinan, 1) }}</td>
                                <td>{{ number_format($p->nilai_kinerja, 1) }}</td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="progress flex-grow-1" style="height:6px;min-width:60px;">
                                            <div class="progress-bar
                                                {{ $p->nilai_total >= 90 ? 'bg-success' : ($p->nilai_total >= 75 ? 'bg-primary' : ($p->nilai_total >= 60 ? 'bg-warning' : 'bg-danger')) }}"
                                                style="width:{{ $p->nilai_total }}%"></div>
                                        </div>
                                        <span class="small fw-bold">{{ number_format($p->nilai_total, 1) }}</span>
                                    </div>
                                </td>
                                <td>
                                    @php $gc = ['A'=>'success','B'=>'primary','C'=>'warning','D'=>'danger'][$p->grade] ?? 'secondary'; @endphp
                                    <span class="badge bg-{{ $gc }} fw-bold fs-6 px-2">{{ $p->grade }}</span>
                                </td>
                                <td>
                                    <div class="d-flex gap-1">
                                        <a href="{{ route('pimpinan.penilaian.show', $p->id) }}"
                                            class="btn btn-sm btn-outline-primary" title="Detail">
                                            <i data-feather="eye" style="width:14px;height:14px;"></i>
                                        </a>
                                        <a href="{{ route('pimpinan.penilaian.edit', $p->id) }}"
                                            class="btn btn-sm btn-outline-warning" title="Edit">
                                            <i data-feather="edit-2" style="width:14px;height:14px;"></i>
                                        </a>
                                        <form id="delete-form-{{ $p->id }}"
                                            action="{{ route('pimpinan.penilaian.destroy', $p->id) }}" method="POST">
                                            @csrf @method('DELETE')
                                        </form>
                                        <button class="btn btn-sm btn-outline-danger" onclick="confirmDelete({{ $p->id }})" title="Hapus">
                                            <i data-feather="trash-2" style="width:14px;height:14px;"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted py-5">
                                    <i data-feather="inbox" style="width:40px;height:40px;" class="mb-2"></i>
                                    <p>Belum ada penilaian untuk periode ini</p>
                                    <a href="{{ route('pimpinan.penilaian.create') }}" class="btn btn-primary btn-sm">Beri Penilaian</a>
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
@endsection
