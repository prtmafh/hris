@extends('karyawan.layouts.app')

@section('title', 'Slip Gaji')

@section('content')
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
        $pemasukan = $penggajian->details->where('tipe', 'pemasukan')->values();
        $potongan = $penggajian->details->where('tipe', 'potongan')->values();
        $totalPemasukan = $pemasukan->sum('jumlah');
        $totalPotongan = $potongan->sum('jumlah');
        $jumlahBaris = max($pemasukan->count(), $potongan->count(), 1);
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
                                <i data-feather="arrow-left" class="me-1"></i>Kembali
                            </a>
                            <a href="{{ route('karyawan.slip_gaji.pdf', $penggajian->id) }}" class="btn btn-sm btn-success"
                                target="_blank">
                                <i data-feather="download" class="me-1"></i>Unduh PDF
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <div class="container-xl px-4 pb-4">
            <div class="card shadow-sm mx-auto" style="max-width: 980px;">
                <div class="card-body p-4 p-md-5">
                    <div class="d-flex flex-column flex-md-row align-items-md-center border-bottom border-dark pb-3 mb-4">
                        <img src="{{ asset('assets/img/logotsi.png') }}" alt="TSI Group" style="width:50px;height:auto;"
                            class="me-md-4 mb-3 mb-md-0">
                        <div>
                            <h2 class="fw-bold mb-1">PT. TIDARJAYA SOLIDINDO</h2>
                            <div class="small text-muted">Jl. Abdul Ghani No. 105, Mustikajaya, Kota Bekasi</div>
                            <div class="small text-muted">Slip Gaji Karyawan</div>
                        </div>
                        <div class="ms-md-auto text-md-end mt-3 mt-md-0">
                            <div class="fw-bold">{{ $namaBulan[$penggajian->periode_bulan] }}
                                {{ $penggajian->periode_tahun }}</div>
                            <span
                                class="badge {{ $penggajian->status === 'dibayar' ? 'bg-green-soft text-green' : 'bg-yellow-soft text-yellow' }}">
                                {{ $penggajian->status === 'dibayar' ? 'Sudah Dibayar' : 'Dalam Proses' }}
                            </span>
                        </div>
                    </div>

                    <div class="row g-3 small mb-4">
                        <div class="col-md-6">
                            <table class="table table-sm table-borderless mb-0">
                                <tr>
                                    <td class="text-muted" style="width:110px;">Nama</td>
                                    <td style="width:12px;">:</td>
                                    <td class="fw-semibold">{{ $k->nama }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Jabatan</td>
                                    <td>:</td>
                                    <td class="fw-semibold">{{ $k->jabatan->nama_jabatan ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Periode</td>
                                    <td>:</td>
                                    <td class="fw-semibold">{{ $namaBulan[$penggajian->periode_bulan] }}
                                        {{ $penggajian->periode_tahun }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-sm table-borderless mb-0">
                                <tr>
                                    <td class="text-muted" style="width:110px;">NIK</td>
                                    <td style="width:12px;">:</td>
                                    <td class="fw-semibold">{{ $k->nik ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Status Gaji</td>
                                    <td>:</td>
                                    <td class="fw-semibold text-capitalize">{{ $k->status_gaji }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Kehadiran</td>
                                    <td>:</td>
                                    <td class="fw-semibold">{{ $penggajian->total_hadir }} hari</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-sm align-middle">
                            <thead class="table-dark">
                                <tr>
                                    <th colspan="2" style="width:50%;">PENERIMAAN</th>
                                    <th colspan="2" style="width:50%;">POTONGAN</th>
                                </tr>
                                <tr class="table-light">
                                    <th>Keterangan</th>
                                    <th class="text-end">Jumlah</th>
                                    <th>Keterangan</th>
                                    <th class="text-end">Jumlah</th>
                                </tr>
                            </thead>
                            <tbody>
                                @for ($i = 0; $i < $jumlahBaris; $i++)
                                    @php
                                        $masuk = $pemasukan->get($i);
                                        $keluar = $potongan->get($i);
                                    @endphp
                                    <tr>
                                        <td>{{ $masuk?->keterangan ?? '-' }}</td>
                                        <td class="text-end text-success fw-semibold">
                                            {{ $masuk ? 'Rp ' . number_format($masuk->jumlah, 0, ',', '.') : '-' }}
                                        </td>
                                        <td>{{ $keluar?->keterangan ?? '-' }}</td>
                                        <td class="text-end text-danger fw-semibold">
                                            {{ $keluar ? 'Rp ' . number_format($keluar->jumlah, 0, ',', '.') : '-' }}
                                        </td>
                                    </tr>
                                @endfor
                            </tbody>
                            <tfoot>
                                <tr class="table-light fw-bold">
                                    <td>TOTAL PENGHASILAN BRUTO</td>
                                    <td class="text-end text-success">Rp {{ number_format($totalPemasukan, 0, ',', '.') }}
                                    </td>
                                    <td>TOTAL POTONGAN</td>
                                    <td class="text-end text-danger">Rp {{ number_format($totalPotongan, 0, ',', '.') }}
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <div
                        class="border-top border-dark mt-3 pt-3 d-flex flex-column flex-sm-row justify-content-between align-items-sm-center">
                        <div>
                            <div class="small text-muted">Total penghasilan bruto dikurangi total potongan</div>
                            @if ($penggajian->tgl_dibayar)
                                <div class="small text-muted">
                                    Dibayar
                                    {{ \Carbon\Carbon::parse($penggajian->tgl_dibayar)->translatedFormat('d F Y') }}
                                </div>
                            @endif
                        </div>
                        <div class="text-sm-end mt-3 mt-sm-0">
                            <div class="small fw-bold">TOTAL DITERIMA KARYAWAN</div>
                            <div class="h3 fw-bold text-primary mb-0">Rp
                                {{ number_format($penggajian->total_gaji, 0, ',', '.') }}</div>
                        </div>
                    </div>

                    <div class="text-center small text-muted mt-5">
                        Slip gaji ini diterbitkan secara otomatis oleh sistem dan sah tanpa tanda tangan.
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
