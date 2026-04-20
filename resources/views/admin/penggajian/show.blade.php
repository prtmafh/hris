@extends('admin.layouts.app')

@section('title', 'Detail Penggajian')

@section('content')
@php
$namaBulan = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
$pemasukan = $penggajian->details->where('tipe', 'pemasukan');
$potongan = $penggajian->details->where('tipe', 'potongan');
$totalPemasukan = $pemasukan->sum('jumlah');
$totalPotongan = $potongan->sum('jumlah');
@endphp
<main>
    <header class="page-header page-header-dark bg-gradient-primary-to-secondary pb-10">
        <div class="container-xl px-4">
            <div class="page-header-content pt-4">
                <div class="row align-items-center justify-content-between">
                    <div class="col-auto mt-4">
                        <h1 class="page-header-title">
                            <div class="page-header-icon"><i data-feather="file-text"></i></div>
                            Detail Penggajian
                        </h1>
                        <div class="page-header-subtitle">
                            {{ $penggajian->karyawan->nama }}
                            &mdash; {{ $namaBulan[$penggajian->periode_bulan] }} {{ $penggajian->periode_tahun }}
                        </div>
                    </div>
                    <div class="col-auto mt-4 d-flex gap-2">
                        <a href="{{ route('admin.penggajian') }}" class="btn btn-white btn-sm">
                            <i class="fas fa-arrow-left me-1"></i> Kembali
                        </a>
                        @if($penggajian->status === 'proses')
                        <form method="POST" action="{{ route('admin.penggajian.bayar', $penggajian->id) }}"
                            id="formTandaiBayar">
                            @csrf
                            <button type="button" class="btn btn-success btn-sm" onclick="confirmTandaiBayar(event)">
                                <i class="fas fa-check-circle me-1"></i> Tandai Dibayar
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div class="container-xl px-4 mt-n10">

        {{-- @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm mb-4" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif --}}

        <div class="row g-4">

            {{-- Kolom Kiri: Info Karyawan --}}
            <div class="col-lg-4">
                <div class="card shadow-sm">
                    <div class="card-header py-3">
                        <div class="d-flex align-items-center gap-2">
                            <i class="fas fa-id-card text-primary"></i>
                            <span class="fw-semibold">Informasi Karyawan</span>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <ul class="list-group list-group-flush small">
                            <li class="list-group-item d-flex justify-content-between align-items-start px-4 py-3">
                                <span class="text-muted">Nama</span>
                                <span class="fw-semibold text-end">{{ $penggajian->karyawan->nama }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-start px-4 py-3">
                                <span class="text-muted">NIK</span>
                                <span class="fw-semibold text-end">{{ $penggajian->karyawan->nik ?? '-' }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-start px-4 py-3">
                                <span class="text-muted">Jabatan</span>
                                <span class="fw-semibold text-end text-capitalize">
                                    {{ $penggajian->karyawan->jabatan->nama_jabatan ?? '-' }}
                                </span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-start px-4 py-3">
                                <span class="text-muted">Status Gaji</span>
                                <span class="fw-semibold text-end text-capitalize">
                                    {{ $penggajian->karyawan->status_gaji }}
                                </span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-start px-4 py-3">
                                <span class="text-muted">Periode</span>
                                <span class="fw-semibold text-end">
                                    {{ $namaBulan[$penggajian->periode_bulan] }} {{ $penggajian->periode_tahun }}
                                </span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center px-4 py-3">
                                <span class="text-muted">Status</span>
                                @if($penggajian->status === 'dibayar')
                                <span class="badge bg-success rounded-pill px-3">Dibayar</span>
                                @else
                                <span class="badge bg-warning text-dark rounded-pill px-3">Proses</span>
                                @endif
                            </li>
                            @if($penggajian->tgl_dibayar)
                            <li class="list-group-item d-flex justify-content-between align-items-start px-4 py-3">
                                <span class="text-muted">Tgl Dibayar</span>
                                <span class="fw-semibold text-end">
                                    {{ \Carbon\Carbon::parse($penggajian->tgl_dibayar)->translatedFormat('d M Y') }}
                                </span>
                            </li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>

            {{-- Kolom Kanan --}}
            <div class="col-lg-8">

                {{-- Kartu Ringkasan --}}
                <div class="row g-3 mb-4">
                    <div class="col-sm-4">
                        <div class="card shadow-sm border-0 h-100">
                            <div class="card-body text-center py-4">
                                <div class="text-xs text-muted text-uppercase fw-semibold mb-2"
                                    style="letter-spacing:.06em;">Hari Hadir</div>
                                <div class="fs-3 fw-bold text-primary lh-1">{{ $penggajian->total_hadir }}</div>
                                <div class="text-muted small mt-1">hari kerja</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="card shadow-sm border-0 h-100">
                            <div class="card-body text-center py-4">
                                <div class="text-xs text-muted text-uppercase fw-semibold mb-2"
                                    style="letter-spacing:.06em;">Total Lembur</div>
                                <div class="fw-bold text-success lh-1" style="font-size:1.1rem;">
                                    Rp {{ number_format($penggajian->total_lembur, 0, ',', '.') }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="card shadow-sm border-0 h-100">
                            <div class="card-body text-center py-4">
                                <div class="text-xs text-muted text-uppercase fw-semibold mb-2"
                                    style="letter-spacing:.06em;">Total Potongan</div>
                                <div class="fw-bold text-danger lh-1" style="font-size:1.1rem;">
                                    Rp {{ number_format($penggajian->potongan, 0, ',', '.') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Tabel Rincian Komponen --}}
                <div class="card shadow-sm">
                    <div class="card-header py-3 d-flex align-items-center gap-2">
                        <i class="fas fa-list-alt text-primary"></i>
                        <span class="fw-semibold">Rincian Komponen Gaji</span>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4 py-3 text-muted small text-uppercase fw-semibold"
                                        style="letter-spacing:.05em;">Keterangan</th>
                                    <th class="text-end py-3 text-muted small text-uppercase fw-semibold"
                                        style="letter-spacing:.05em;">Jumlah</th>
                                    <th class="text-center pe-4 py-3 text-muted small text-uppercase fw-semibold"
                                        style="width:120px;letter-spacing:.05em;">Tipe</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- Pemasukan --}}
                                @if($pemasukan->isNotEmpty())
                                <tr style="background:#f8fffe;">
                                    <td colspan="3" class="ps-4 py-2">
                                        <small class="text-success fw-bold text-uppercase"
                                            style="letter-spacing:.06em;">
                                            <i class="fas fa-plus-circle me-1"></i>Pemasukan
                                        </small>
                                    </td>
                                </tr>
                                @foreach($pemasukan as $d)
                                <tr>
                                    <td class="ps-4">{{ $d->keterangan }}</td>
                                    <td class="text-end fw-semibold text-success">
                                        Rp {{ number_format($d->jumlah, 0, ',', '.') }}
                                    </td>
                                    <td class="text-center pe-4">
                                        <span class="badge bg-success-soft text-success rounded-pill">Pemasukan</span>
                                    </td>
                                </tr>
                                @endforeach
                                <tr class="border-top">
                                    <td class="ps-4 text-muted small">Subtotal Pemasukan</td>
                                    <td class="text-end fw-bold text-success">
                                        Rp {{ number_format($totalPemasukan, 0, ',', '.') }}
                                    </td>
                                    <td></td>
                                </tr>
                                @endif

                                {{-- Potongan --}}
                                @if($potongan->isNotEmpty())
                                <tr style="background:#fff8f8;">
                                    <td colspan="3" class="ps-4 py-2">
                                        <small class="text-danger fw-bold text-uppercase" style="letter-spacing:.06em;">
                                            <i class="fas fa-minus-circle me-1"></i>Potongan
                                        </small>
                                    </td>
                                </tr>
                                @foreach($potongan as $d)
                                <tr>
                                    <td class="ps-4">{{ $d->keterangan }}</td>
                                    <td class="text-end fw-semibold text-danger">
                                        Rp {{ number_format($d->jumlah, 0, ',', '.') }}
                                    </td>
                                    <td class="text-center pe-4">
                                        <span class="badge bg-danger-soft text-danger rounded-pill">Potongan</span>
                                    </td>
                                </tr>
                                @endforeach
                                <tr class="border-top">
                                    <td class="ps-4 text-muted small">Subtotal Potongan</td>
                                    <td class="text-end fw-bold text-danger">
                                        Rp {{ number_format($totalPotongan, 0, ',', '.') }}
                                    </td>
                                    <td></td>
                                </tr>
                                @endif

                                @if($pemasukan->isEmpty() && $potongan->isEmpty())
                                <tr>
                                    <td colspan="3" class="text-center text-muted py-5">
                                        <i class="fas fa-inbox fa-2x mb-2 d-block text-muted opacity-50"></i>
                                        Belum ada rincian komponen gaji.
                                    </td>
                                </tr>
                                @endif
                            </tbody>
                            <tfoot>
                                <tr style="background:#f0f4ff;">
                                    <th class="ps-4 py-3 fw-bold">
                                        <i class="fas fa-wallet text-primary me-2"></i>Total Gaji Bersih
                                    </th>
                                    <th class="text-end py-3 text-primary fw-bold fs-6">
                                        Rp {{ number_format($penggajian->total_gaji, 0, ',', '.') }}
                                    </th>
                                    <th class="pe-4"></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
</main>
@endsection

@push('scripts')
<script>
    function confirmTandaiBayar(event) {
        event.preventDefault();
        Swal.fire({
            title: 'Konfirmasi Pembayaran',
            text: 'Tandai gaji ini sebagai sudah dibayar?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Tandai Dibayar',
            cancelButtonText: 'Batal',
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('formTandaiBayar').submit();
            }
        });
        return false;
    }
</script>
@endpush