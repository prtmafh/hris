<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Karir - PT. Tidarjaya Solidindo</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="karir PT Tidarjaya Solidindo, lowongan kerja, rekrutmen karyawan" name="keywords">
    <meta content="Informasi lowongan kerja aktif di PT. Tidarjaya Solidindo." name="description">

    <link href="{{ asset('landing/img/favicon.ico') }}" rel="icon">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="{{ asset('landing/lib/flaticon/font/flaticon.css') }}" rel="stylesheet">
    <link href="{{ asset('landing/lib/animate/animate.min.css') }}" rel="stylesheet">
    <link href="{{ asset('landing/lib/owlcarousel/assets/owl.carousel.min.css') }}" rel="stylesheet">
    <link href="{{ asset('landing/lib/lightbox/css/lightbox.min.css') }}" rel="stylesheet">
    <link href="{{ asset('landing/lib/slick/slick.css') }}" rel="stylesheet">
    <link href="{{ asset('landing/lib/slick/slick-theme.css') }}" rel="stylesheet">
    <link href="{{ asset('landing/css/style.css') }}" rel="stylesheet">
    <style>
        .career-hero {
            position: relative;
            min-height: 360px;
            display: flex;
            align-items: center;
            background: linear-gradient(rgba(3, 15, 35, 0.72), rgba(3, 15, 35, 0.72)),
            url("{{ asset('landing/img/carousel-2.jpg') }}") center/cover no-repeat;
            color: #ffffff;
        }

        .career-hero h1 {
            font-size: 52px;
            font-weight: 700;
            margin-bottom: 16px;
        }

        .career-card {
            height: 100%;
            border: 1px solid #e8e8e8;
            border-radius: 6px;
            background: #ffffff;
            transition: 0.2s ease;
        }

        .career-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.08);
        }

        .career-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin: 14px 0;
        }

        .career-meta span {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 10px;
            border-radius: 4px;
            background: #f5f6f8;
            color: #555;
            font-size: 13px;
        }

        .career-section {
            padding: 80px 0;
        }

        .career-empty {
            padding: 48px 24px;
            border: 1px dashed #d8d8d8;
            border-radius: 6px;
            background: #ffffff;
        }

        .tracking-box {
            padding: 32px;
            border-radius: 6px;
            background: #f8f9fa;
            border: 1px solid #ececec;
        }

        .tracking-item {
            padding: 20px;
            border-radius: 6px;
            border: 1px solid #e8e8e8;
            background: #ffffff;
        }

        @media (max-width: 767.98px) {
            .career-hero h1 {
                font-size: 36px;
            }
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <div class="top-bar">
            <div class="container-fluid">
                <div class="row align-items-center">
                    <div class="col-lg-4 col-md-12">
                        <div class="logo">
                            <a href="{{ route('tsi-group') }}">
                                <h1>Tidarjaya</h1>
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-8 col-md-7 d-none d-lg-block">
                        <div class="row">
                            <div class="col-4">
                                <div class="top-bar-item">
                                    <div class="top-bar-icon">
                                        <i class="flaticon-calendar"></i>
                                    </div>
                                    <div class="top-bar-text">
                                        <h3>Halaman</h3>
                                        <p>Karir</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="top-bar-item">
                                    <div class="top-bar-icon">
                                        <i class="flaticon-worker"></i>
                                    </div>
                                    <div class="top-bar-text">
                                        <h3>Rekrutmen</h3>
                                        <p>Karyawan</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="top-bar-item">
                                    <div class="top-bar-icon">
                                        <i class="flaticon-send-mail"></i>
                                    </div>
                                    <div class="top-bar-text">
                                        <h3>Perusahaan</h3>
                                        <p>PT. Tidarjaya Solidindo</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="nav-bar">
            <div class="container-fluid">
                <nav class="navbar navbar-expand-lg bg-dark navbar-dark">
                    <a href="#" class="navbar-brand">MENU</a>
                    <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#navbarCollapse">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    <div class="collapse navbar-collapse justify-content-between" id="navbarCollapse">
                        <div class="navbar-nav mr-auto">
                            <a href="{{ route('tsi-group') }}" class="nav-item nav-link">Beranda</a>
                            <a href="{{ route('tsi-group') }}#tentang" class="nav-item nav-link">Tentang</a>
                            <a href="{{ route('tsi-group') }}#layanan" class="nav-item nav-link">Layanan</a>
                            <a href="{{ route('tsi-group') }}#komitmen" class="nav-item nav-link">Komitmen</a>
                            <a href="{{ route('tsi-group.karir') }}" class="nav-item nav-link active">Karir</a>
                            <a href="{{ route('tsi-group') }}#kontak" class="nav-item nav-link">Kontak</a>
                        </div>
                        <div class="ml-auto">
                            <a class="btn" href="{{ route('tsi-group') }}">Company Profile</a>
                        </div>
                    </div>
                </nav>
            </div>
        </div>

        <section class="career-hero">
            <div class="container">
                <div class="row">
                    <div class="col-lg-8">
                        <p class="mb-2">Karir PT. Tidarjaya Solidindo</p>
                        <h1 class="text-white">Bergabung Bersama Tim Profesional Kami</h1>
                        <p class="lead mb-0">
                            Temukan posisi yang sesuai dan ikut mendukung layanan operasional, maintenance,
                            serta konstruksi yang responsif dan berorientasi solusi.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <section class="career-section">
            <div class="container">
                <div class="tracking-box mb-5">
                    <div class="row align-items-end">
                        <div class="col-lg-5 mb-3 mb-lg-0">
                            <div class="section-header text-left mb-0">
                                <p class="mb-2">Tracking Lamaran</p>
                                <h2 class="mb-0">Cek Status dengan Email</h2>
                            </div>
                        </div>
                        <div class="col-lg-7">
                            <form action="{{ route('tsi-group.karir.tracking') }}" method="POST">
                                @csrf
                                <div class="input-group">
                                    <input type="email" name="email" class="form-control"
                                        placeholder="Masukkan email yang digunakan saat melamar"
                                        value="{{ old('email', $trackingEmail) }}" required>
                                    <div class="input-group-append">
                                        <button class="btn" type="submit">Cek Status</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    @if($trackingEmail)
                    <div class="mt-4">
                        @if($trackingPelamar->isEmpty())
                        <div class="alert alert-warning mb-0">
                            Tidak ada lamaran yang ditemukan untuk email {{ $trackingEmail }}.
                        </div>
                        @else
                        <div class="row">
                            @foreach($trackingPelamar as $tracking)
                            @php
                            $trackingStatusClass = [
                                'pending' => 'secondary',
                                'screening' => 'info',
                                'interview' => 'primary',
                                'offering' => 'warning',
                                'diterima' => 'success',
                                'ditolak' => 'danger',
                            ][$tracking->status] ?? 'secondary';
                            @endphp
                            <div class="col-lg-6 mb-3">
                                <div class="tracking-item">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <h4 class="h5 mb-1">{{ $tracking->lowongan->judul ?? '-' }}</h4>
                                            <div class="text-muted">
                                                {{ optional(optional($tracking->lowongan)->jabatan)->nama_jabatan ?? '-' }}
                                            </div>
                                        </div>
                                        <span class="badge badge-{{ $trackingStatusClass }}">
                                            {{ ucfirst($tracking->status) }}
                                        </span>
                                    </div>
                                    <div class="career-meta">
                                        <span><i class="fa fa-calendar"></i> Lamar
                                            {{ optional($tracking->applied_at)->format('d/m/Y') }}</span>
                                        @if($tracking->jadwal_interview)
                                        <span><i class="fa fa-clock"></i> Interview
                                            {{ $tracking->jadwal_interview->format('d/m/Y H:i') }}</span>
                                        @endif
                                    </div>
                                    @if($tracking->catatan_hr)
                                    <p class="mb-0 text-muted">{!! nl2br(e($tracking->catatan_hr)) !!}</p>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @endif
                    </div>
                    @endif
                </div>

                <div class="section-header text-center">
                    <p>Lowongan Aktif</p>
                    <h2>Kesempatan Karir</h2>
                </div>

                @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
                @endif

                @if($errors->any())
                <div class="alert alert-danger">
                    <strong>Lamaran belum dapat dikirim.</strong>
                    <div>Periksa kembali data dan dokumen yang Anda unggah.</div>
                </div>
                @endif

                @if($lowongan->isEmpty())
                <div class="career-empty text-center">
                    <h4>Belum Ada Lowongan Aktif</h4>
                    <p class="text-muted mb-0">
                        Saat ini belum ada posisi yang dibuka. Silakan cek kembali halaman ini secara berkala.
                    </p>
                </div>
                @else
                <div class="row">
                    @foreach($lowongan as $item)
                    <div class="col-lg-6 mb-4">
                        <div class="career-card p-4">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div>
                                    <h3 class="h4 mb-1">{{ $item->judul }}</h3>
                                    <div class="text-muted">{{ $item->jabatan->nama_jabatan ?? 'Posisi tersedia' }}
                                    </div>
                                </div>
                                <span class="badge badge-success">Aktif</span>
                            </div>

                            <div class="career-meta">
                                <span><i class="fa fa-users"></i> Kuota {{ $item->kuota }} orang</span>
                                <span><i class="fa fa-calendar-alt"></i> Tutup
                                    {{ optional($item->tanggal_tutup)->format('d/m/Y') }}</span>
                            </div>

                            <p class="text-muted">
                                {{ \Illuminate\Support\Str::limit(strip_tags($item->deskripsi), 180) }}
                            </p>

                            <div class="accordion" id="careerAccordion{{ $item->id }}">
                                <div class="card mb-2">
                                    <div class="card-header p-0">
                                        <a class="card-link collapsed d-block px-3 py-2" data-toggle="collapse"
                                            href="#qualification{{ $item->id }}">
                                            Kualifikasi
                                        </a>
                                    </div>
                                    <div id="qualification{{ $item->id }}" class="collapse"
                                        data-parent="#careerAccordion{{ $item->id }}">
                                        <div class="card-body">
                                            {!! nl2br(e($item->kualifikasi)) !!}
                                        </div>
                                    </div>
                                </div>

                                @if($item->tanggung_jawab)
                                <div class="card">
                                    <div class="card-header p-0">
                                        <a class="card-link collapsed d-block px-3 py-2" data-toggle="collapse"
                                            href="#responsibility{{ $item->id }}">
                                            Tanggung Jawab
                                        </a>
                                    </div>
                                    <div id="responsibility{{ $item->id }}" class="collapse"
                                        data-parent="#careerAccordion{{ $item->id }}">
                                        <div class="card-body">
                                            {!! nl2br(e($item->tanggung_jawab)) !!}
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>

                            <div class="mt-4 d-flex flex-wrap align-items-center justify-content-between">
                                <small class="text-muted">Dibuka
                                    {{ optional($item->tanggal_buka)->format('d/m/Y') }}</small>
                                <button class="btn" data-toggle="modal" data-target="#modalLamar{{ $item->id }}">
                                    Lamar Sekarang
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="modal fade" id="modalLamar{{ $item->id }}" tabindex="-1" role="dialog"
                        aria-hidden="true">
                        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Lamar {{ $item->judul }}</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <form action="{{ route('tsi-group.karir.lamar', $item->id) }}" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label font-weight-bold">Nama Lengkap <span
                                                        class="text-danger">*</span></label>
                                                <input type="text" name="nama" class="form-control"
                                                    value="{{ old('nama') }}" required>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label font-weight-bold">Email <span
                                                        class="text-danger">*</span></label>
                                                <input type="email" name="email" class="form-control"
                                                    value="{{ old('email') }}" required>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label font-weight-bold">No. HP</label>
                                                <input type="text" name="no_hp" class="form-control"
                                                    value="{{ old('no_hp') }}">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label font-weight-bold">Tanggal Lahir</label>
                                                <input type="date" name="tanggal_lahir" class="form-control"
                                                    value="{{ old('tanggal_lahir') }}">
                                            </div>
                                            <div class="col-md-12 mb-3">
                                                <label class="form-label font-weight-bold">Alamat</label>
                                                <textarea name="alamat" class="form-control"
                                                    rows="3">{{ old('alamat') }}</textarea>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label font-weight-bold">CV <span
                                                        class="text-danger">*</span></label>
                                                <input type="file" name="cv" class="form-control"
                                                    accept=".pdf,.doc,.docx" required>
                                                <small class="text-muted">Format PDF/DOC/DOCX, maksimal 4 MB.</small>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label font-weight-bold">Foto</label>
                                                <input type="file" name="foto" class="form-control"
                                                    accept=".jpg,.jpeg,.png">
                                                <small class="text-muted">Format JPG/PNG, maksimal 2 MB.</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-light" data-dismiss="modal">Batal</button>
                                        <button type="submit" class="btn">Kirim Lamaran</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>
        </section>

        <div class="footer wow fadeIn" data-wow-delay="0.3s">
            <div class="container">
                <div class="row">
                    <div class="col-md-6 col-lg-4">
                        <div class="footer-contact">
                            <h2>PT. Tidarjaya Solidindo</h2>
                            <p><i class="fa fa-map-marker-alt"></i>Indonesia</p>
                            <p><i class="fa fa-envelope"></i>Email resmi perusahaan</p>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4">
                        <div class="footer-link">
                            <h2>Halaman</h2>
                            <a href="{{ route('tsi-group') }}">Company Profile</a>
                            <a href="{{ route('tsi-group') }}#tentang">Tentang Kami</a>
                            <a href="{{ route('tsi-group') }}#layanan">Layanan</a>
                            <a href="{{ route('tsi-group.karir') }}">Karir</a>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4">
                        <div class="newsletter">
                            <h2>Informasi Rekrutmen</h2>
                            <p>
                                Informasi lowongan ditampilkan berdasarkan kebutuhan rekrutmen aktif perusahaan.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="container copyright">
                <div class="row">
                    <div class="col-md-6">
                        <p>&copy; <a href="#">PT. Tidarjaya Solidindo</a>, All Right Reserved.</p>
                    </div>
                    <div class="col-md-6">
                        <p>Dikelola oleh <a href="#">PT. Tidarjaya Solidindo</a></p>
                    </div>
                </div>
            </div>
        </div>

        <a href="#" class="back-to-top"><i class="fa fa-chevron-up"></i></a>
    </div>

    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('landing/lib/easing/easing.min.js') }}"></script>
    <script src="{{ asset('landing/lib/wow/wow.min.js') }}"></script>
    <script src="{{ asset('landing/lib/owlcarousel/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('landing/lib/isotope/isotope.pkgd.min.js') }}"></script>
    <script src="{{ asset('landing/lib/lightbox/js/lightbox.min.js') }}"></script>
    <script src="{{ asset('landing/lib/waypoints/waypoints.min.js') }}"></script>
    <script src="{{ asset('landing/lib/counterup/counterup.min.js') }}"></script>
    <script src="{{ asset('landing/lib/slick/slick.min.js') }}"></script>
    <script src="{{ asset('landing/js/main.js') }}"></script>
</body>

</html>
