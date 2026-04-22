<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <title>Panggilan Rekrutmen</title>
</head>

<body style="font-family: Arial, sans-serif; color: #222; line-height: 1.6;">
    <h2>Panggilan Rekrutmen PT. Tidarjaya Solidindo</h2>

    <p>Yth. {{ $pelamar->nama }},</p>

    <p>
        Terima kasih telah melamar untuk posisi
        <strong>{{ optional($pelamar->lowongan)->judul ?? '-' }}</strong>
        di PT. Tidarjaya Solidindo.
    </p>

    @if($pelamar->jadwal_interview)
    <p>
        Kami mengundang Anda untuk mengikuti proses interview pada:
        <br>
        <strong>{{ $pelamar->jadwal_interview->format('d/m/Y H:i') }}</strong>
    </p>
    @endif

    <p>{!! nl2br(e($pesan)) !!}</p>

    <p>
        Silakan cek status lamaran Anda melalui halaman karir menggunakan email yang digunakan saat melamar.
    </p>

    <p>
        Hormat kami,<br>
        HR PT. Tidarjaya Solidindo
    </p>
</body>

</html>
