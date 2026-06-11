{{-- resources/views/pdf/slip-gaji.blade.php --}}
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Slip Gaji</title>
    <style>
        @page {
            size: 180mm 120mm;
            margin: 0;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            width: 165mm;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 10px;
            line-height: 1.15;
            color: #000;
        }

        .slip {
            width: 165mm;
            padding: 5mm 7mm;
        }

        .top-table,
        .info-table,
        .detail-table,
        .total-table {
            width: 100%;
            border-collapse: collapse;
        }

        .company-name {
            font-size: 20px;
            font-weight: 700;
            line-height: 1.05;
        }

        .company-address {
            margin-top: 3px;
            font-size: 11px;
            line-height: 1.2;
        }

        /* .brand {
            text-align: right;
            white-space: nowrap;
        }

        .brand-mark {
            display: inline-block;
            width: 23px;
            height: 24px;
            margin-right: 6px;
            vertical-align: middle;
            position: relative;
        }

        .brand-blue,
        .brand-red {
            display: block;
            position: absolute;
            background: #0719b8;
        }

        .brand-blue.one {
            width: 22px;
            height: 4px;
            top: 0;
            right: 0;
        }

        .brand-blue.two {
            width: 6px;
            height: 20px;
            top: 4px;
            right: 6px;
        }

        .brand-blue.three {
            width: 13px;
            height: 5px;
            bottom: 0;
            right: 6px;
        }

        .brand-red {
            width: 8px;
            height: 8px;
            left: 0;
            bottom: 6px;
            background: #e30613;
        }

        .brand-text {
            display: inline-block;
            vertical-align: middle;
            font-family: "Times New Roman", Times, serif;
            font-size: 27px;
            font-weight: 700;
            color: #08119b;
            line-height: 1;
        } */
        .brand {
            text-align: right;
            vertical-align: top;
        }

        .company-logo {
            width: 170px;
            height: auto;
        }

        .title {
            margin-top: 1px;
            padding-bottom: 7px;
            border-bottom: 1px solid #000;
            text-align: center;
            font-size: 20px;
            font-weight: 700;
            letter-spacing: .3px;
        }

        .info-table {
            margin-top: 13px;
            margin-bottom: 18px;
        }

        .info-table td {
            padding: 0;
            font-size: 11px;
        }

        .info-label {
            width: 47px;
        }

        .info-separator {
            width: 9px;
            text-align: center;
        }

        .section-head td {
            background: #d9d9d9;
            padding: 5px 11px;
            font-size: 14px;
            font-weight: 700;
        }

        .section-head .right-col {
            width: 43%;
            padding-left: 9px;
        }

        .items td {
            padding: 8px 11px 2px;
            font-size: 11px;
            vertical-align: top;
        }

        .items .amount {
            width: 115px;
            padding-left: 0;
            text-align: left;
        }

        .items .spacer {
            width: 88px;
        }

        .items .deduction-label {
            width: 155px;
            padding-left: 9px;
        }

        .items .deduction-amount {
            width: 100px;
            text-align: left;
        }

        .summary {
            margin-top: 18px;
            border-top: 1px solid #000;
        }

        .summary td {
            padding: 5px 11px 0;
            font-size: 15px;
            font-weight: 700;
            vertical-align: top;
        }

        .summary .summary-label {
            width: 185px;
        }

        .summary .summary-amount {
            width: 165px;
        }

        .summary .right-label {
            width: 170px;
            padding-left: 9px;
        }

        .summary .right-amount {
            width: 100px;
        }

        .net-box {
            width: 61mm;
            border: 1px solid #000;
            text-align: center;
            padding: 3px;
            margin-top: 8px;
            margin-left: auto;
        }

        .net-label {
            font-size: 11px;
            line-height: 1.1;
        }

        .net-amount {
            margin-top: 4px;
            font-size: 21px;
            font-weight: 700;
            line-height: 1;
        }

        .detail-table,
        .summary {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        .detail-table col.col-desc,
        .summary col.col-desc {
            width: 38%;
        }

        .detail-table col.col-amount,
        .summary col.col-amount {
            width: 17%;
        }

        .detail-table col.col-spacer,
        .summary col.col-spacer {
            width: 8%;
        }

        .detail-table col.col-deduction,
        .summary col.col-deduction {
            width: 25%;
        }

        .detail-table col.col-deduction-amount,
        .summary col.col-deduction-amount {
            width: 12%;
        }

        .amount,
        .deduction-amount {
            text-align: right;
        }

        .summary {
            margin-top: 12px;
            border-top: 1px solid #000;
        }

        .summary td {
            padding: 5px 11px;
            font-size: 14px;
            font-weight: 700;
        }
    </style>

</head>

<body>
    @php
    $pemasukan = $penggajian->details->where('tipe', 'pemasukan')->values();
    $potongan = $penggajian->details->where('tipe', 'potongan')->values();
    $k = $penggajian->karyawan;
    $totalIn = $pemasukan->sum('jumlah');
    $totalOut = $potongan->sum('jumlah');
    $maxRows = max($pemasukan->count(), $potongan->count(), 1);
    $periode = sprintf('01/%02d/%d', $penggajian->periode_bulan, $penggajian->periode_tahun);
    @endphp

    <div class="slip">
        <table class="top-table">
            <tr>
                <td>
                    <div class="company-name">PT. Tidarjaya Solidindo</div>
                    <div class="company-address">
                        Jl. Abdul Ghani No 105, Mustikajaya,<br>
                        Kota Bekasi
                    </div>
                </td>
                <td class="brand">
                    <img src="{{ public_path('assets/img/tsigrouplogo.png') }}" alt="TSI GROUP" class="company-logo">
                </td>
            </tr>
        </table>

        <div class="title">SLIP GAJI</div>

        <table class="info-table">
            <tr>
                <td class="info-label">Nama</td>
                <td class="info-separator">:</td>
                <td>{{ $k->nama }}</td>
            </tr>
            <tr>
                <td class="info-label">NIK</td>
                <td class="info-separator">:</td>
                <td>{{ $k->nik ?? '-' }}</td>
            </tr>
            <tr>
                <td class="info-label">Jabatan</td>
                <td class="info-separator">:</td>
                <td>{{ $k->jabatan->nama_jabatan ?? '-' }}</td>
            </tr>
            <tr>
                <td class="info-label">Periode</td>
                <td class="info-separator">:</td>
                <td>{{ $periode }}</td>
            </tr>
        </table>

        <table class="detail-table">

            <colgroup>
                <col class="col-desc">
                <col class="col-amount">
                <col class="col-spacer">
                <col class="col-deduction">
                <col class="col-deduction-amount">
            </colgroup>

            <tr class="section-head">
                <td colspan="2">Pendapatan</td>
                <td></td>
                <td colspan="2">Potongan</td>
            </tr>

            @for ($i = 0; $i < $maxRows; $i++) @php $income=$pemasukan->get($i);
                $deduction = $potongan->get($i);
                @endphp

                <tr class="items">
                    <td>{{ $income->keterangan ?? '' }}</td>

                    <td class="amount">
                        {{ $income ? number_format($income->jumlah, 0, ',', '.') : '' }}
                    </td>

                    <td></td>

                    <td>
                        {{ $deduction->keterangan ?? '' }}
                    </td>

                    <td class="deduction-amount">
                        {{ $deduction ? number_format($deduction->jumlah, 0, ',', '.') : '' }}
                    </td>
                </tr>
                @endfor

        </table>

        <table class="summary">

            <colgroup>
                <col class="col-desc">
                <col class="col-amount">
                <col class="col-spacer">
                <col class="col-deduction">
                <col class="col-deduction-amount">
            </colgroup>

            <tr>
                <td>Total Pendapatan</td>

                <td class="amount">
                    {{ number_format($totalIn, 0, ',', '.') }}
                </td>

                <td></td>

                <td>Total Potongan</td>

                <td class="deduction-amount">
                    {{ number_format($totalOut, 0, ',', '.') }}
                </td>
            </tr>

        </table>
        <div class="net-box">
            <div class="net-label">Total bersih Bulan ini</div>
            <div class="net-amount">{{ number_format($penggajian->total_gaji, 0, ',', '.') }}</div>
        </div>


    </div>
</body>

</html>