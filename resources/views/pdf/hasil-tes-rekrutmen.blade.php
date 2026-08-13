<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Hasil Tes Rekrutmen</title>
    <style>
        @page { margin: 28px 34px; }
        * { box-sizing: border-box; }
        body { font-family: DejaVu Sans, sans-serif; color: #263238; font-size: 11px; line-height: 1.45; }
        .header { border-bottom: 3px solid #0d6efd; padding-bottom: 14px; margin-bottom: 20px; }
        .company { color: #0d6efd; font-size: 21px; font-weight: bold; letter-spacing: .5px; }
        .title { margin-top: 3px; color: #546e7a; font-size: 14px; }
        .meta { width: 100%; margin-bottom: 18px; border-collapse: collapse; }
        .meta td { padding: 5px 8px; border-bottom: 1px solid #e7edf3; }
        .meta .label { width: 22%; color: #6c757d; }
        .recommendation { margin-bottom: 20px; padding: 13px 15px; border: 1px solid #b9d5fb; background: #eef5ff; }
        .recommendation-label { color: #607d8b; font-size: 9px; font-weight: bold; text-transform: uppercase; }
        .recommendation-value { margin-top: 3px; color: #0d6efd; font-size: 16px; font-weight: bold; }
        table.results { width: 100%; border-collapse: collapse; }
        .results th { padding: 8px 7px; color: #fff; background: #0d6efd; text-align: left; }
        .results td { padding: 8px 7px; border: 1px solid #dfe6ec; vertical-align: top; }
        .results tr:nth-child(even) td { background: #f7f9fb; }
        .center { text-align: center; }
        .status { font-weight: bold; }
        .footer-note { margin-top: 18px; color: #6c757d; font-size: 9px; }
        .signature { width: 100%; margin-top: 36px; }
        .signature td { width: 50%; text-align: center; vertical-align: top; }
        .signature-space { height: 55px; }
        .line { display: inline-block; min-width: 160px; border-top: 1px solid #263238; padding-top: 4px; }
    </style>
</head>
<body>
    @php
        $rekomendasiLabel = match($rekomendasi) {
            'direkomendasikan' => 'DIREKOMENDASIKAN',
            'tidak_direkomendasikan' => 'TIDAK DIREKOMENDASIKAN',
            'dipertimbangkan' => 'PERLU DIPERTIMBANGKAN',
            default => 'BELUM ADA REKOMENDASI',
        };
    @endphp

    <div class="header">
        <div class="company">TSI GROUP</div>
        <div class="title">Laporan Hasil Tes Rekrutmen</div>
    </div>

    <table class="meta">
        <tr><td class="label">Nama Pelamar</td><td><strong>{{ $pelamar->nama }}</strong></td></tr>
        <tr><td class="label">Email</td><td>{{ $pelamar->email }}</td></tr>
        <tr><td class="label">Posisi Dilamar</td><td>{{ $pelamar->lowongan->judul ?? '-' }}</td></tr>
        <tr><td class="label">Jabatan</td><td>{{ $pelamar->lowongan->jabatan->nama_jabatan ?? '-' }}</td></tr>
        <tr><td class="label">Tanggal Laporan</td><td>{{ now()->format('d/m/Y H:i') }}</td></tr>
    </table>

    <div class="recommendation">
        <div class="recommendation-label">Output Akhir Seleksi</div>
        <div class="recommendation-value">{{ $rekomendasiLabel }}</div>
    </div>

    <table class="results">
        <thead>
            <tr>
                <th class="center" style="width: 5%">No</th>
                <th style="width: 18%">Jenis Tes</th>
                <th style="width: 12%">Tanggal</th>
                <th class="center" style="width: 9%">Nilai</th>
                <th style="width: 15%">Hasil</th>
                <th style="width: 15%">Penguji</th>
                <th>Catatan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pelamar->hasilTes as $index => $hasil)
                <tr>
                    <td class="center">{{ $index + 1 }}</td>
                    <td><strong>{{ $hasil->jenis_tes }}</strong></td>
                    <td>{{ $hasil->tanggal_tes->format('d/m/Y') }}</td>
                    <td class="center">{{ $hasil->nilai !== null ? rtrim(rtrim(number_format((float) $hasil->nilai, 2, ',', '.'), '0'), ',') : '-' }}</td>
                    <td class="status">{{ match($hasil->hasil) {
                        'lulus' => 'Lulus',
                        'tidak_lulus' => 'Tidak Lulus',
                        default => 'Dipertimbangkan',
                    } }}</td>
                    <td>{{ $hasil->penguji ?: '-' }}</td>
                    <td>{{ $hasil->catatan ?: '-' }}</td>
                </tr>
            @empty
                <tr><td colspan="7" class="center">Belum ada hasil tes.</td></tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer-note">
        Rekomendasi dihasilkan dari seluruh hasil tes: semua tes lulus berarti direkomendasikan;
        terdapat hasil tidak lulus berarti tidak direkomendasikan; hasil lainnya perlu dipertimbangkan oleh HR.
    </div>

    <table class="signature">
        <tr><td></td><td>HR / Penanggung Jawab</td></tr>
        <tr><td></td><td class="signature-space"></td></tr>
        <tr><td></td><td><span class="line">(................................)</span></td></tr>
    </table>
</body>
</html>
