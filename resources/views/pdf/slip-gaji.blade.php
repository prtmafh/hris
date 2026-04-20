<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Slip Gaji</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 10px;
            color: #1a202c;
            background: #ffffff;
        }

        .page {
            padding: 24px 28px;
            width: 100%;
        }

        /* ── Header ── */
        .header-table {
            width: 100%;
            border-bottom: 3px solid #1a56db;
            padding-bottom: 12px;
            margin-bottom: 14px;
        }
        .company-name {
            font-size: 17px;
            font-weight: bold;
            color: #1a56db;
        }
        .company-sub { font-size: 8.5px; color: #64748b; margin-top: 2px; }
        .slip-title  { font-size: 12px; font-weight: bold; color: #1e293b; text-align: right; }
        .slip-period { font-size: 8.5px; color: #64748b; text-align: right; margin-top: 3px; }

        .badge {
            display: inline-block;
            padding: 2px 10px;
            border-radius: 20px;
            font-size: 8px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.4px;
        }
        .badge-success { background: #dcfce7; color: #166534; border: 1px solid #bbf7d0; }
        .badge-warning { background: #fef9c3; color: #854d0e; border: 1px solid #fef08a; }

        /* ── Info Karyawan ── */
        .info-box {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 5px;
            padding: 10px 14px;
            margin-bottom: 14px;
        }
        .info-section-title {
            font-size: 7.5px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: #64748b;
            border-bottom: 1px solid #e2e8f0;
            padding-bottom: 5px;
            margin-bottom: 8px;
        }
        .info-table { width: 100%; border-collapse: collapse; }
        .info-table td { padding: 2.5px 4px; font-size: 9px; vertical-align: top; }
        .info-label { color: #64748b; width: 130px; }
        .info-sep   { width: 10px; color: #94a3b8; }
        .info-value { font-weight: bold; color: #1e293b; }

        /* ── Section label ── */
        .section-label {
            font-size: 8px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.7px;
            padding: 4px 10px;
            border-radius: 4px;
            margin-bottom: 5px;
            display: inline-block;
        }
        .label-income { color: #166534; background: #dcfce7; }
        .label-deduct { color: #991b1b; background: #fee2e2; }

        /* ── Komponen table ── */
        .komponen-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
        }
        .komponen-table th {
            background: #f1f5f9;
            font-size: 8px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #475569;
            padding: 5px 10px;
            text-align: left;
            border-bottom: 1px solid #e2e8f0;
        }
        .komponen-table th.right { text-align: right; }
        .komponen-table td {
            padding: 5px 10px;
            font-size: 9px;
            color: #334155;
            border-bottom: 1px solid #f1f5f9;
        }
        .komponen-table td.right { text-align: right; }
        .subtotal-row td {
            background: #f8fafc;
            font-weight: bold;
            font-size: 9px;
            border-top: 1px solid #e2e8f0;
            border-bottom: none;
        }
        .green { color: #166534; }
        .red   { color: #991b1b; }

        /* ── Total Box ── */
        .total-table {
            width: 100%;
            background: #1a56db;
            border-radius: 6px;
            margin-top: 6px;
        }
        .total-table td {
            padding: 11px 16px;
            color: #ffffff;
            vertical-align: middle;
        }
        .total-label  { font-size: 11px; font-weight: bold; }
        .total-amount { font-size: 15px; font-weight: bold; text-align: right; }

        /* ── Footer ── */
        .footer {
            margin-top: 20px;
            padding-top: 8px;
            border-top: 1px dashed #cbd5e1;
        }
        .footer-table { width: 100%; }
        .footer-note  { font-size: 7.5px; color: #94a3b8; line-height: 1.6; }
        .ttd-area     { text-align: right; }
        .ttd-label    { font-size: 8px; color: #475569; }
        .ttd-space    { height: 38px; }
        .ttd-line     { font-size: 8.5px; font-weight: bold; color: #1e293b; border-top: 1px solid #475569; padding-top: 4px; display: inline-block; min-width: 110px; }
    </style>
</head>
<body>
@php
$namaBulan   = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
$pemasukan   = $penggajian->details->where('tipe', 'pemasukan');
$potongan    = $penggajian->details->where('tipe', 'potongan');
$k           = $penggajian->karyawan;
$totalIn     = $pemasukan->sum('jumlah');
$totalOut    = $potongan->sum('jumlah');
@endphp

<div class="page">

    {{-- ── HEADER ── --}}
    <table class="header-table">
        <tr>
            <td style="vertical-align:middle;">
                <div class="company-name">TSI GROUP</div>
                <div class="company-sub">Sistem Manajemen Sumber Daya Manusia</div>
            </td>
            <td style="vertical-align:middle; text-align:right; width:45%;">
                <div class="slip-title">SLIP GAJI KARYAWAN</div>
                <div class="slip-period">Periode: {{ $namaBulan[$penggajian->periode_bulan] }} {{ $penggajian->periode_tahun }}</div>
                <div style="margin-top:5px;">
                    <span class="badge {{ $penggajian->status === 'dibayar' ? 'badge-success' : 'badge-warning' }}">
                        {{ $penggajian->status === 'dibayar' ? 'Sudah Dibayar' : 'Dalam Proses' }}
                    </span>
                </div>
            </td>
        </tr>
    </table>

    {{-- ── INFO KARYAWAN ── --}}
    <div class="info-box">
        <div class="info-section-title">Informasi Karyawan</div>
        <table class="info-table">
            <tr>
                <td class="info-label">Nama Karyawan</td>
                <td class="info-sep">:</td>
                <td class="info-value">{{ $k->nama }}</td>
                <td class="info-label">Jabatan</td>
                <td class="info-sep">:</td>
                <td class="info-value">{{ $k->jabatan->nama_jabatan ?? '-' }}</td>
            </tr>
            <tr>
                <td class="info-label">NIK</td>
                <td class="info-sep">:</td>
                <td class="info-value">{{ $k->nik ?? '-' }}</td>
                <td class="info-label">Status Gaji</td>
                <td class="info-sep">:</td>
                <td class="info-value" style="text-transform:capitalize;">{{ $k->status_gaji }}</td>
            </tr>
            <tr>
                <td class="info-label">Hari Hadir</td>
                <td class="info-sep">:</td>
                <td class="info-value">{{ $penggajian->total_hadir }} hari</td>
                <td class="info-label">Tanggal Dibayar</td>
                <td class="info-sep">:</td>
                <td class="info-value">
                    @if($penggajian->tgl_dibayar)
                        {{ \Carbon\Carbon::parse($penggajian->tgl_dibayar)->translatedFormat('d F Y') }}
                    @else
                        -
                    @endif
                </td>
            </tr>
        </table>
    </div>

    {{-- ── PEMASUKAN ── --}}
    <div class="section-label label-income">+ Pemasukan</div>
    <table class="komponen-table">
        <thead>
            <tr>
                <th>Keterangan</th>
                <th class="right" style="width:38%;">Jumlah</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pemasukan as $d)
            <tr>
                <td>{{ $d->keterangan }}</td>
                <td class="right green">Rp {{ number_format($d->jumlah, 0, ',', '.') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="2" style="color:#94a3b8;font-style:italic;text-align:center;">Tidak ada komponen pemasukan.</td>
            </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr class="subtotal-row">
                <td>Subtotal Pemasukan</td>
                <td class="right green">Rp {{ number_format($totalIn, 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>

    {{-- ── POTONGAN ── --}}
    <div class="section-label label-deduct">- Potongan</div>
    <table class="komponen-table">
        <thead>
            <tr>
                <th>Keterangan</th>
                <th class="right" style="width:38%;">Jumlah</th>
            </tr>
        </thead>
        <tbody>
            @forelse($potongan as $d)
            <tr>
                <td>{{ $d->keterangan }}</td>
                <td class="right red">Rp {{ number_format($d->jumlah, 0, ',', '.') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="2" style="color:#94a3b8;font-style:italic;text-align:center;">Tidak ada potongan.</td>
            </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr class="subtotal-row">
                <td>Subtotal Potongan</td>
                <td class="right red">Rp {{ number_format($totalOut, 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>

    {{-- ── TOTAL BERSIH ── --}}
    <table class="total-table">
        <tr>
            <td class="total-label">Gaji Bersih Diterima</td>
            <td class="total-amount">Rp {{ number_format($penggajian->total_gaji, 0, ',', '.') }}</td>
        </tr>
    </table>

    {{-- ── FOOTER ── --}}
    <div class="footer">
        <table class="footer-table">
            <tr>
                <td style="vertical-align:top; width:58%;">
                    <p class="footer-note">
                        Slip gaji ini diterbitkan secara otomatis oleh sistem dan sah tanpa tanda tangan.<br>
                        Dicetak pada: {{ \Carbon\Carbon::now()->translatedFormat('d F Y, H:i') }} WIB
                    </p>
                </td>
                <td style="vertical-align:top; text-align:right;">
                    <p class="ttd-label">Mengetahui,</p>
                    <div class="ttd-space"></div>
                    <span class="ttd-line">HRD / Pimpinan</span>
                </td>
            </tr>
        </table>
    </div>

</div>
</body>
</html>
