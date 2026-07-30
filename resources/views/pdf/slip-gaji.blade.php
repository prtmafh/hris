<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Slip Gaji</title>
    <style>
        @page {
            margin: 14mm;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 10px;
            color: #111;
            margin: 0;
        }

        .slip {
            border: 1.5px solid #111;
            padding: 18px 20px 22px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        .header {
            border-bottom: 2px solid #111;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }

        .header-logo {
            width: 30px;
            height: auto;
            vertical-align: middle;
            padding-bottom: 2px;
        }

        .company {
            vertical-align: middle;
            padding-left: 0px;
        }

        .company-name {
            font-family: "Times New Roman", serif;
            font-size: 22px;
            font-weight: bold;
        }

        .company-info {
            font-size: 9px;
            line-height: 1.45;
            margin-top: 2px;
        }

        .slip-title {
            width: 125px;
            text-align: right;
            vertical-align: middle;
        }

        .slip-title strong {
            display: block;
            font-size: 15px;
        }

        .status {
            display: inline-block;
            margin-top: 4px;
            padding: 3px 7px;
            border: 1px solid #777;
            font-size: 8px;
        }

        .info {
            margin-bottom: 18px;
        }

        .info td {
            padding: 2px 0;
            vertical-align: top;
        }

        .info .label {
            width: 65px;
        }

        .info .separator {
            width: 12px;
        }

        .info .gap {
            width: 55px;
        }

        .components {
            table-layout: fixed;
        }

        .components col.desc {
            width: 31%;
        }

        .components col.amount {
            width: 19%;
        }

        .components th {
            background: #e5e5e5;
            border: 1px solid #555;
            padding: 7px 8px;
            font-size: 11px;
            text-align: left;
        }

        .components th.amount,
        .components td.amount {
            text-align: right;
        }

        .components td {
            border-left: 1px solid #777;
            border-right: 1px solid #777;
            padding: 6px 8px;
            vertical-align: top;
        }

        .components tbody tr:last-child td {
            padding-bottom: 14px;
        }

        .components tfoot td {
            border: 1px solid #555;
            border-top: 1.5px solid #111;
            padding: 8px;
            font-weight: bold;
            font-size: 10px;
        }

        .net {
            margin-top: 13px;
            border-top: 1.5px solid #111;
            padding-top: 10px;
        }

        .net-label {
            font-size: 12px;
            font-weight: bold;
        }

        .net-amount {
            font-size: 18px;
            font-weight: bold;
            text-align: right;
        }

        .explanation {
            font-size: 8px;
            color: #555;
            padding-top: 3px;
        }

        .signature {
            margin-top: 55px;
        }

        .signature td {
            width: 50%;
            text-align: center;
            vertical-align: bottom;
        }

        .signature-space {
            height: 45px;
        }

        .signature-name {
            font-weight: bold;
            text-decoration: underline;
        }

        .footer {
            margin-top: 18px;
            padding-top: 7px;
            border-top: 1px solid #aaa;
            text-align: center;
            font-size: 8px;
            color: #666;
        }
    </style>
</head>

<body>
    @php
        $pemasukan = $penggajian->details->where('tipe', 'pemasukan')->values();
        $potongan = $penggajian->details->where('tipe', 'potongan')->values();
        $totalPemasukan = $pemasukan->sum('jumlah');
        $totalPotongan = $potongan->sum('jumlah');
        $jumlahBaris = max($pemasukan->count(), $potongan->count(), 1);
        $k = $penggajian->karyawan;
    @endphp

    <div class="slip">
        <table class="header">
            <tr>
                <td style="width:45px;">
                    <img src="{{ public_path('assets/img/logotsi.png') }}" class="header-logo" alt="TSI Group">
                </td>
                <td class="company">
                    <div class="company-name">PT. TIDARJAYA SOLIDINDO</div>
                    <div class="company-info">
                        Jl. Abdul Ghani No. 105, Mustikajaya, Kota Bekasi<br>
                        {{-- Slip gaji karyawan --}}
                    </div>
                </td>
                <td class="slip-title">
                    {{-- <strong>SLIP GAJI</strong> --}}
                    <strong>{{ $namaBulan[$penggajian->periode_bulan] }} {{ $penggajian->periode_tahun }}</strong>
                    {{-- <div class="status">{{ $penggajian->status === 'dibayar' ? 'SUDAH DIBAYAR' : 'DALAM PROSES' }}</div> --}}
                </td>
            </tr>
        </table>

        <table class="info">
            <tr>
                <td class="label">Nama</td>
                <td class="separator">:</td>
                <td>{{ $k->nama }}</td>
                <td class="gap"></td>
                <td class="label">NIK</td>
                <td class="separator">:</td>
                <td>{{ $k->nik ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Jabatan</td>
                <td class="separator">:</td>
                <td>{{ $k->jabatan->nama_jabatan ?? '-' }}</td>
                <td class="gap"></td>
                <td class="label">Status Gaji</td>
                <td class="separator">:</td>
                <td>{{ ucfirst($k->status_gaji) }}</td>
            </tr>
            <tr>
                <td class="label">Periode</td>
                <td class="separator">:</td>
                <td>{{ $namaBulan[$penggajian->periode_bulan] }} {{ $penggajian->periode_tahun }}</td>
                <td class="gap"></td>
                <td class="label">Kehadiran</td>
                <td class="separator">:</td>
                <td>{{ $penggajian->total_hadir }} hari</td>
            </tr>
        </table>

        <table class="components">
            <colgroup>
                <col class="desc">
                <col class="amount">
                <col class="desc">
                <col class="amount">
            </colgroup>
            <thead>
                <tr>
                    <th colspan="2">PENERIMAAN</th>
                    <th colspan="2">POTONGAN</th>
                </tr>
                <tr>
                    <th>Keterangan</th>
                    <th class="amount">Jumlah</th>
                    <th>Keterangan</th>
                    <th class="amount">Jumlah</th>
                </tr>
            </thead>
            <tbody>
                @for ($i = 0; $i < $jumlahBaris; $i++)
                    @php
                        $masuk = $pemasukan->get($i);
                        $keluar = $potongan->get($i);
                    @endphp
                    <tr>
                        <td>{{ $masuk?->keterangan ?? '' }}</td>
                        <td class="amount">{{ $masuk ? 'Rp ' . number_format($masuk->jumlah, 0, ',', '.') : '' }}</td>
                        <td>{{ $keluar?->keterangan ?? '' }}</td>
                        <td class="amount">{{ $keluar ? 'Rp ' . number_format($keluar->jumlah, 0, ',', '.') : '' }}
                        </td>
                    </tr>
                @endfor
            </tbody>
            <tfoot>
                <tr>
                    <td>TOTAL PENGHASILAN BRUTO</td>
                    <td class="amount">Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</td>
                    <td>TOTAL POTONGAN</td>
                    <td class="amount">Rp {{ number_format($totalPotongan, 0, ',', '.') }}</td>
                </tr>
            </tfoot>
        </table>

        <table class="net">
            <tr>
                <td>
                    <div class="net-label">TOTAL DITERIMA KARYAWAN</div>
                    <div class="explanation">Total penghasilan bruto dikurangi total potongan</div>
                </td>
                <td class="net-amount">Rp {{ number_format($penggajian->total_gaji, 0, ',', '.') }}</td>
            </tr>
        </table>

        <table class="signature">
            <tr>
                <td>Dibuat oleh,<br>PT. Tidarjaya Solidindo</td>
                <td>Disetujui oleh,</td>
            </tr>
            <tr>
                <td colspan="2" class="signature-space"></td>
            </tr>
            <tr>
                <td><span class="signature-name">Manager Keuangan</span></td>
                <td>
                    <span class="signature-name">{{ $k->nama }}</span><br>
                    {{ $k->jabatan->nama_jabatan ?? 'Karyawan' }}
                </td>
            </tr>
        </table>

        <div class="footer">
            Slip gaji ini diterbitkan otomatis oleh sistem.
            @if ($penggajian->tgl_dibayar)
                Tanggal pembayaran: {{ \Carbon\Carbon::parse($penggajian->tgl_dibayar)->format('d/m/Y') }}.
            @endif
        </div>
    </div>
</body>

</html>
