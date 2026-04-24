<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Export Data Absensi</title>
</head>
<body>
    <table>
        <tr>
            <td colspan="7"><strong>Data Absensi Karyawan</strong></td>
        </tr>
        <tr>
            <td colspan="7">Diexport pada: {{ $exportedAt->format('d-m-Y H:i:s') }}</td>
        </tr>
        <tr>
            <td colspan="7">
                Filter:
                Dari {{ $filters['tanggal_dari'] ? \Carbon\Carbon::parse($filters['tanggal_dari'])->format('d-m-Y') : 'Semua Tanggal' }},
                Sampai {{ $filters['tanggal_sampai'] ? \Carbon\Carbon::parse($filters['tanggal_sampai'])->format('d-m-Y') : 'Semua Tanggal' }},
                Karyawan {{ $filters['karyawan'] ?? 'Semua' }},
                Status {{ $filters['status'] ? ucfirst($filters['status']) : 'Semua' }}
            </td>
        </tr>
    </table>

    <table border="1">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Karyawan</th>
                <th>Jabatan</th>
                <th>Tanggal</th>
                <th>Jam Masuk</th>
                <th>Jam Keluar</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($absensi as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->karyawan->nama }}</td>
                    <td>{{ optional($item->karyawan->jabatan)->nama_jabatan ?? '-' }}</td>
                    <td>{{ $item->tanggal->format('d-m-Y') }}</td>
                    <td>{{ $item->jam_masuk ?? '-' }}</td>
                    <td>{{ $item->jam_keluar ?? '-' }}</td>
                    <td>{{ ucfirst($item->status) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7">Tidak ada data absensi</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
