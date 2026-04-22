<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <title>Update Proses Lamaran</title>
</head>

<body style="font-family: Arial, sans-serif; color: #222; line-height: 1.6;">
    <h2>Update Proses Lamaran PT. Tidarjaya Solidindo</h2>

    <p>Yth. {{ $pelamar->nama }},</p>

    <p>
        Kami menyampaikan pembaruan proses lamaran Anda untuk posisi
        <strong>{{ optional($pelamar->lowongan)->judul ?? '-' }}</strong>.
    </p>

    <p>
        Status lamaran saat ini:
        <strong>{{ ucfirst($pelamar->status) }}</strong>
    </p>

    @if($pelamar->jadwal_interview)
    <p>
        Jadwal interview:
        <br>
        <strong>{{ $pelamar->jadwal_interview->format('d/m/Y H:i') }}</strong>
    </p>
    @endif

    <p>{!! nl2br(e($pesan)) !!}</p>

    <p>
        Anda juga dapat mengecek status lamaran melalui halaman karir menggunakan email yang digunakan saat melamar.
    </p>

    <p>
        Hormat kami,<br>
        HR PT. Tidarjaya Solidindo
    </p>
</body>

</html>
