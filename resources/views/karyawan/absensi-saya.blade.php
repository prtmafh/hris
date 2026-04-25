@extends('karyawan.layouts.app')

@section('title', 'Absensi Saya')

@section('content')
<main>
    <header class="page-header page-header-compact page-header-light border-bottom bg-white mb-4 py-2">
        <div class="container-xl px-4">
            <div class="page-header-content">
                <div class="row align-items-center justify-content-between pt-3">
                    <div class="col-auto mb-3">
                        <h1 class="page-header-title">
                            <div class="page-header-icon"><i data-feather="calendar"></i></div>
                            Absensi Saya
                        </h1>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div class="container-xl px-4">
        <div class="card mb-4">
            <div class="card-header">
                <div class="fw-bold">Filter Absensi</div>
                <div class="small text-muted">Pilih bulan dan tahun untuk melihat riwayat kehadiran Anda.</div>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('karyawan.absensi') }}" class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label class="form-label small mb-1">Bulan</label>
                        <select name="bulan" class="form-select form-select-sm">
                            @foreach(range(1, 12) as $b)
                            <option value="{{ $b }}" {{ $bulan==$b ? 'selected' : '' }}>
                                {{ \Carbon\Carbon::create()->month($b)->translatedFormat('F') }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small mb-1">Tahun</label>
                        <select name="tahun" class="form-select form-select-sm">
                            @foreach($daftarTahun as $t)
                            <option value="{{ $t }}" {{ $tahun==$t ? 'selected' : '' }}>{{ $t }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4 d-flex gap-2">
                        <button type="submit" class="btn btn-primary btn-sm w-100">
                            <i data-feather="search"></i> Tampilkan
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-header d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2">
                <div>
                    <div class="fw-bold">Riwayat Absensi</div>
                    <div class="small text-muted">
                        Periode {{ \Carbon\Carbon::create()->month($bulan)->translatedFormat('F') }} {{ $tahun }}
                        @if($statusGaji === 'harian')
                        &mdash; <span class="text-primary">Karyawan Harian (per sesi)</span>
                        @endif
                    </div>
                </div>
                <span class="badge bg-primary">{{ $absensi->count() }} hari</span>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    @if($statusGaji === 'harian')
                    {{-- Tabel khusus karyawan harian: breakdown per sesi --}}
                    <table id="datatablesSimple" data-simple-datatable class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>Sesi</th>
                                <th>Jam Masuk</th>
                                <th>Jam Pulang</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $no = 1; @endphp
                            @forelse($absensi as $a)
                            @if($a->sesi->isEmpty())
                            <tr>
                                <td class="text-muted">{{ $no++ }}</td>
                                <td>
                                    <div class="fw-semibold">
                                        {{ \Carbon\Carbon::parse($a->tanggal)->translatedFormat('d F Y') }}
                                    </div>
                                </td>
                                <td><span class="text-muted">-</span></td>
                                <td>-</td>
                                <td>-</td>
                                <td>
                                    @php
                                    $badge = match($a->status) {
                                    'hadir' => 'green',
                                    'terlambat' => 'yellow',
                                    'izin' => 'blue',
                                    'alpha' => 'red',
                                    default => 'secondary',
                                    };
                                    @endphp
                                    <span class="badge bg-{{ $badge }}-soft text-{{ $badge }} text-capitalize">{{
                                        $a->status }}</span>
                                </td>
                            </tr>
                            @else
                            @foreach($a->sesi as $idx => $sesi)
                            @php
                            $badge = match($sesi->status) {
                            'hadir' => 'success',
                            'terlambat' => 'warning',
                            'izin' => 'primary',
                            'alpha' => 'danger',
                            default => 'secondary',
                            };
                            @endphp
                            <tr>
                                <td class="text-muted">{{ $idx === 0 ? $no++ : '' }}</td>
                                <td>
                                    @if($idx === 0)
                                    <div class="fw-semibold">
                                        {{ \Carbon\Carbon::parse($a->tanggal)->translatedFormat('d F Y') }}
                                    </div>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-secondary bg-opacity-25 text-dark">Sesi {{ $sesi->sesi_ke
                                        }}</span>
                                </td>
                                <td>{{ $sesi->jam_checkin ? \Carbon\Carbon::parse($sesi->jam_checkin)->format('H:i') :
                                    '-' }}</td>
                                <td>{{ $sesi->jam_checkout ? \Carbon\Carbon::parse($sesi->jam_checkout)->format('H:i') :
                                    '-' }}</td>
                                <td>
                                    <span class="badge bg-{{ $badge }}-soft text-{{ $badge }} text-capitalize">{{
                                        $sesi->status }}</span>
                                </td>
                            </tr>
                            @endforeach
                            @endif
                            @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">
                                    Belum ada data absensi untuk periode ini.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                    @else
                    {{-- Tabel karyawan bulanan (jam masuk/pulang per hari) --}}
                    <table id="datatablesSimple" data-simple-datatable class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>Jam Masuk</th>
                                <th>Jam Pulang</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($absensi as $i => $a)
                            @php
                            $badge = match($a->status) {
                            'hadir' => 'success',
                            'terlambat' => 'warning',
                            'izin' => 'primary',
                            'alpha' => 'danger',
                            default => 'secondary',
                            };
                            @endphp
                            <tr>
                                <td class="text-muted">{{ $i + 1 }}</td>
                                <td>
                                    <div class="fw-semibold">
                                        {{ \Carbon\Carbon::parse($a->tanggal)->translatedFormat('d F Y') }}
                                    </div>
                                </td>
                                <td>{{ $a->jam_masuk ? \Carbon\Carbon::parse($a->jam_masuk)->format('H:i') : '-' }}</td>
                                <td>{{ $a->jam_keluar ? \Carbon\Carbon::parse($a->jam_keluar)->format('H:i') : '-' }}
                                </td>
                                <td>
                                    <span class="badge bg-{{ $badge }} text-capitalize">{{ $a->status }}</span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">
                                    Belum ada data absensi untuk periode ini.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                    @endif
                </div>
            </div>
        </div>
    </div>
</main>
@endsection