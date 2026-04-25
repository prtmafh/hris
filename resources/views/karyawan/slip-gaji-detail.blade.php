@extends('karyawan.layouts.app')

@section('title', 'Slip Gaji')

@section('content')
@php
$namaBulan = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
$pemasukan = $penggajian->details->where('tipe', 'pemasukan');
$potongan = $penggajian->details->where('tipe', 'potongan');
$totalPemasukan = $pemasukan->sum('jumlah');
$totalPotongan = $potongan->sum('jumlah');
$k = $penggajian->karyawan;
@endphp

<main>
    <header class="page-header page-header-compact page-header-light border-bottom bg-white mb-4 py-2">
        <div class="container-xl px-4">
            <div class="page-header-content">
                <div class="row align-items-center justify-content-between pt-3">
                    <div class="col-auto mb-3">
                        <h1 class="page-header-title">
                            <div class="page-header-icon"><i data-feather="file-text"></i></div>
                            Slip Gaji — {{ $namaBulan[$penggajian->periode_bulan] }} {{ $penggajian->periode_tahun }}
                        </h1>
                    </div>
                    <div class="col-auto mb-3 d-flex gap-2">
                        <a href="{{ route('karyawan.slip_gaji') }}" class="btn btn-sm btn-light">
                            <i data-feather="arrow-left" class="me-1"></i> Kembali
                        </a>
                        <a href="{{ route('karyawan.slip_gaji.pdf', $penggajian->id) }}"
                            class="btn btn-sm btn-light text-success" target="_blank">
                            <i data-feather="download" class="me-1"></i> Unduh PDF
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div class="container-xl px-4 pb-4">
        <div style="max-width: 760px; margin: 0 auto;">

            {{-- ── Slip Card ── --}}
            <div class="card shadow">

                {{-- Header Slip --}}
                <div class="card-body border-bottom pb-4 pt-4 px-4">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="fw-bold text-primary" style="font-size:1.2rem;letter-spacing:0.3px;">
                                TSI GROUP
                            </div>
                            <div class="text-muted small">Sistem Manajemen Sumber Daya Manusia</div>
                        </div>
                        <div class="col-auto text-end">
                            <div class="text-muted small text-uppercase fw-semibold mb-1" style="letter-spacing:.06em;">
                                Slip Gaji Karyawan</div>
                            <div class="fw-semibold small">
                                Periode: {{ $namaBulan[$penggajian->periode_bulan] }} {{ $penggajian->periode_tahun }}
                            </div>
                            @if($penggajian->status === 'dibayar')
                            <span class="badge bg-green-soft text-green rounded-pill mt-1 px-3">Sudah Dibayar</span>
                            @else
                            <span class="badge bg-yellow-soft text-yellow rounded-pill mt-1 px-3">Dalam Proses</span>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Info Karyawan --}}
                <div class="card-body border-bottom py-3 px-4" style="background:#f8fafc;">
                    <div class="small fw-semibold text-uppercase text-muted mb-3"
                        style="letter-spacing:.07em; font-size:.7rem;">
                        <i class="fas fa-id-card me-1"></i> Informasi Karyawan
                    </div>
                    <div class="row g-3 small">
                        <div class="col-sm-6">
                            <div class="text-muted mb-1">Nama Karyawan</div>
                            <div class="fw-semibold">{{ $k->nama }}</div>
                        </div>
                        <div class="col-sm-6">
                            <div class="text-muted mb-1">NIK</div>
                            <div class="fw-semibold">{{ $k->nik ?? '-' }}</div>
                        </div>
                        <div class="col-sm-6">
                            <div class="text-muted mb-1">Jabatan</div>
                            <div class="fw-semibold">{{ $k->jabatan->nama_jabatan ?? '-' }}</div>
                        </div>
                        <div class="col-sm-6">
                            <div class="text-muted mb-1">Status Gaji</div>
                            <div class="fw-semibold text-capitalize">{{ $k->status_gaji }}</div>
                        </div>
                        <div class="col-sm-6">
                            <div class="text-muted mb-1">Hari Hadir</div>
                            <div class="fw-semibold">{{ $penggajian->total_hadir }} hari</div>
                        </div>
                        @if($penggajian->tgl_dibayar)
                        <div class="col-sm-6">
                            <div class="text-muted mb-1">Tanggal Dibayar</div>
                            <div class="fw-semibold">
                                {{ \Carbon\Carbon::parse($penggajian->tgl_dibayar)->translatedFormat('d F Y') }}
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                {{-- Komponen Gaji --}}
                <div class="card-body px-4 pt-4 pb-2">

                    {{-- Pemasukan --}}
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <span class="badge bg-success-soft text-success px-3 py-2 rounded-pill"
                            style="font-size:.7rem;letter-spacing:.05em;">
                            <i class="fas fa-plus-circle me-1"></i> PEMASUKAN
                        </span>
                    </div>
                    <table class="table table-hover align-middle small mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="py-2 text-muted fw-semibold" style="font-size:.7rem;letter-spacing:.05em;">
                                    KETERANGAN</th>
                                <th class="py-2 text-end text-muted fw-semibold"
                                    style="font-size:.7rem;letter-spacing:.05em;">JUMLAH</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pemasukan as $d)
                            <tr>
                                <td class="py-2">{{ $d->keterangan }}</td>
                                <td class="py-2 text-end fw-semibold text-success">
                                    Rp {{ number_format($d->jumlah, 0, ',', '.') }}
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="2" class="text-center text-muted py-3 fst-italic">
                                    Tidak ada komponen pemasukan.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                        <tfoot>
                            <tr class="table-success">
                                <td class="py-2 fw-bold">Subtotal Pemasukan</td>
                                <td class="py-2 text-end fw-bold text-success">
                                    Rp {{ number_format($totalPemasukan, 0, ',', '.') }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>

                    {{-- Potongan --}}
                    <div class="d-flex align-items-center gap-2 mt-4 mb-2">
                        <span class="badge bg-danger-soft text-danger px-3 py-2 rounded-pill"
                            style="font-size:.7rem;letter-spacing:.05em;">
                            <i class="fas fa-minus-circle me-1"></i> POTONGAN
                        </span>
                    </div>
                    <table class="table table-hover align-middle small mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="py-2 text-muted fw-semibold" style="font-size:.7rem;letter-spacing:.05em;">
                                    KETERANGAN</th>
                                <th class="py-2 text-end text-muted fw-semibold"
                                    style="font-size:.7rem;letter-spacing:.05em;">JUMLAH</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($potongan as $d)
                            <tr>
                                <td class="py-2">{{ $d->keterangan }}</td>
                                <td class="py-2 text-end fw-semibold text-danger">
                                    Rp {{ number_format($d->jumlah, 0, ',', '.') }}
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="2" class="text-center text-muted py-3 fst-italic">
                                    Tidak ada potongan.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                        <tfoot>
                            <tr class="table-danger">
                                <td class="py-2 fw-bold">Subtotal Potongan</td>
                                <td class="py-2 text-end fw-bold text-danger">
                                    Rp {{ number_format($totalPotongan, 0, ',', '.') }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                {{-- Total Bersih --}}
                <div class="card-footer bg-gradient-primary-to-secondary text-white rounded-bottom px-4 py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="small opacity-75 mb-1">Total Gaji Bersih Diterima</div>
                            <div class="fw-bold fs-5">
                                Rp {{ number_format($penggajian->total_gaji, 0, ',', '.') }}
                            </div>
                        </div>
                        <div class="text-end">
                            <i class="fas fa-wallet fa-2x opacity-50"></i>
                        </div>
                    </div>
                </div>

            </div>

            {{-- Footer note --}}
            <div class="text-center mt-3 mb-2">
                <p class="text-muted small mb-1">
                    Slip gaji ini diterbitkan secara otomatis oleh sistem dan sah tanpa tanda tangan.
                </p>
                <a href="{{ route('karyawan.slip_gaji.pdf', $penggajian->id) }}"
                    class="btn btn-sm btn-light text-success rounded-pill px-4 mt-1" target="_blank">
                    <i data-feather="download" class="me-1"></i> Unduh Slip PDF
                </a>
            </div>

        </div>
    </div>
</main>
@endsection