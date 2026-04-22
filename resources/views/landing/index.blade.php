<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>PT. Tidarjaya Solidindo - Jasa Operasional & Konstruksi</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="PT Tidarjaya Solidindo, jasa operasional, logistik, maintenance gedung, renovasi ruangan, konstruksi" name="keywords">
    <meta content="PT. Tidarjaya Solidindo menyediakan jasa logistik, pengadaan kebutuhan perkantoran, maintenance gedung, dan renovasi ruangan untuk mendukung kebutuhan operasional mitra." name="description">

    <!-- Favicon -->
    <link href="{{asset('landing/img/favicon.ico')}}" rel="icon">

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">

    <!-- CSS Libraries -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="{{asset('landing/lib/flaticon/font/flaticon.css')}}" rel="stylesheet">
    <link href="{{asset('landing/lib/animate/animate.min.css')}}" rel="stylesheet">
    <link href="{{asset('landing/lib/owlcarousel/assets/owl.carousel.min.css')}}" rel="stylesheet">
    <link href="{{asset('landing/lib/lightbox/css/lightbox.min.css')}}" rel="stylesheet">
    <link href="{{asset('landing/lib/slick/slick.css')}}" rel="stylesheet">
    <link href="{{asset('landing/lib/slick/slick-theme.css')}}" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="{{asset('landing/css/style.css')}}" rel="stylesheet">
</head>

<body>
    <div class="wrapper">
        <!-- Top Bar Start -->
        <div class="top-bar">
            <div class="container-fluid">
                <div class="row align-items-center">
                    <div class="col-lg-4 col-md-12">
                        <div class="logo">
                            <a href="{{ url('/') }}">
                                <h1>Tidarjaya</h1>
                                <!-- <img src="img/logo.jpg" alt="Logo"> -->
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
                                        <h3>Jam Layanan</h3>
                                        <p>Senin - Jumat</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="top-bar-item">
                                    <div class="top-bar-icon">
                                        <i class="flaticon-call"></i>
                                    </div>
                                    <div class="top-bar-text">
                                        <h3>Layanan</h3>
                                        <p>Jasa & Konstruksi</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="top-bar-item">
                                    <div class="top-bar-icon">
                                        <i class="flaticon-send-mail"></i>
                                    </div>
                                    <div class="top-bar-text">
                                        <h3>Mitra Utama</h3>
                                        <p>Lingkungan Kementerian</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Top Bar End -->

        <!-- Nav Bar Start -->
        <div class="nav-bar">
            <div class="container-fluid">
                <nav class="navbar navbar-expand-lg bg-dark navbar-dark">
                    <a href="#" class="navbar-brand">MENU</a>
                    <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#navbarCollapse">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    <div class="collapse navbar-collapse justify-content-between" id="navbarCollapse">
                        <div class="navbar-nav mr-auto">
                            <a href="#carousel" class="nav-item nav-link active">Beranda</a>
                            <a href="#tentang" class="nav-item nav-link">Tentang</a>
                            <a href="#layanan" class="nav-item nav-link">Layanan</a>
                            <a href="#komitmen" class="nav-item nav-link">Komitmen</a>
                            <a href="{{ route('tsi-group.karir') }}" class="nav-item nav-link">Karir</a>
                            <a href="#kontak" class="nav-item nav-link">Kontak</a>
                        </div>
                        <div class="ml-auto">
                            <a class="btn" href="{{ route('tsi-group.karir') }}">Lihat Karir</a>
                        </div>
                    </div>
                </nav>
            </div>
        </div>
        <!-- Nav Bar End -->


        <!-- Carousel Start -->
        <div id="carousel" class="carousel slide" data-ride="carousel">
            <ol class="carousel-indicators">
                <li data-target="#carousel" data-slide-to="0" class="active"></li>
                <li data-target="#carousel" data-slide-to="1"></li>
                <li data-target="#carousel" data-slide-to="2"></li>
            </ol>
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img src="{{asset('landing/img/carousel-1.jpg')}}" alt="Carousel Image">
                    <div class="carousel-caption">
                        <p class="animated fadeInRight">PT. Tidarjaya Solidindo</p>
                        <h1 class="animated fadeInLeft">Solusi Jasa Operasional dan Konstruksi</h1>
                        <a class="btn animated fadeInUp" href="#layanan">Lihat Layanan</a>
                    </div>
                </div>

                <div class="carousel-item">
                    <img src="{{asset('landing/img/carousel-2.jpg')}}" alt="Carousel Image">
                    <div class="carousel-caption">
                        <p class="animated fadeInRight">Responsif dan Profesional</p>
                        <h1 class="animated fadeInLeft">Mendukung Kelancaran Operasional Mitra</h1>
                        <a class="btn animated fadeInUp" href="#tentang">Profil Perusahaan</a>
                    </div>
                </div>

                <div class="carousel-item">
                    <img src="{{asset('landing/img/carousel-3.jpg')}}" alt="Carousel Image">
                    <div class="carousel-caption">
                        <p class="animated fadeInRight">Tepat Waktu dan Berorientasi Solusi</p>
                        <h1 class="animated fadeInLeft">Renovasi Ruangan Sesuai Kebutuhan Klien</h1>
                        <a class="btn animated fadeInUp" href="#kontak">Konsultasi Kebutuhan</a>
                    </div>
                </div>
            </div>

            <a class="carousel-control-prev" href="#carousel" role="button" data-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="sr-only">Previous</span>
            </a>
            <a class="carousel-control-next" href="#carousel" role="button" data-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="sr-only">Next</span>
            </a>
        </div>
        <!-- Carousel End -->


        <!-- Feature Start-->
        <div class="feature wow fadeInUp" data-wow-delay="0.1s">
            <div class="container-fluid">
                <div class="row align-items-center">
                    <div class="col-lg-4 col-md-12">
                        <div class="feature-item">
                            <div class="feature-icon">
                                <i class="flaticon-worker"></i>
                            </div>
                            <div class="feature-text">
                                <h3>Tenaga Profesional</h3>
                                <p>Pekerjaan ditangani oleh tim yang memahami kebutuhan layanan lapangan dan konstruksi.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-12">
                        <div class="feature-item">
                            <div class="feature-icon">
                                <i class="flaticon-building"></i>
                            </div>
                            <div class="feature-text">
                                <h3>Kualitas Terjaga</h3>
                                <p>Setiap pekerjaan diarahkan pada hasil yang rapi, efisien, dan sesuai arahan mitra.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-12">
                        <div class="feature-item">
                            <div class="feature-icon">
                                <i class="flaticon-call"></i>
                            </div>
                            <div class="feature-text">
                                <h3>Layanan Responsif</h3>
                                <p>Kami membantu kebutuhan operasional dengan komunikasi yang cepat dan solutif.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Feature End-->


        <!-- About Start -->
        <div class="about wow fadeInUp" data-wow-delay="0.1s" id="tentang">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-5 col-md-6">
                        <div class="about-img">
                            <img src="{{asset('landing/img/about.jpg')}}" alt="Image">
                        </div>
                    </div>
                    <div class="col-lg-7 col-md-6">
                        <div class="section-header text-left">
                            <p>Tentang Perusahaan</p>
                            <h2>PT. Tidarjaya Solidindo</h2>
                        </div>
                        <div class="about-text">
                            <p>
                                PT. Tidarjaya Solidindo merupakan perusahaan yang bergerak di bidang jasa dan
                                konstruksi, hadir untuk memberikan solusi menyeluruh dalam memenuhi kebutuhan
                                operasional serta pengembangan infrastruktur mitra kerja.
                            </p>
                            <p>
                                Perusahaan berfokus melayani lingkungan Kementerian sebagai mitra utama, dengan
                                pelaksanaan pekerjaan yang disesuaikan berdasarkan kebutuhan dan arahan yang diberikan.
                                Komitmen kami adalah membangun kepercayaan melalui pelayanan yang profesional,
                                responsif, dan berorientasi pada solusi.
                            </p>
                            <a class="btn" href="#layanan">Pelajari Layanan</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- About End -->


        <!-- Fact Start -->
        <div class="fact">
            <div class="container-fluid">
                <div class="row counters">
                    <div class="col-md-6 fact-left wow slideInLeft">
                        <div class="row">
                            <div class="col-6">
                                <div class="fact-icon">
                                    <i class="flaticon-worker"></i>
                                </div>
                                <div class="fact-text">
                                    <h2 data-toggle="counter-up">3</h2>
                                    <p>Lini Jasa Utama</p>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="fact-icon">
                                    <i class="flaticon-building"></i>
                                </div>
                                <div class="fact-text">
                                    <h2 data-toggle="counter-up">1</h2>
                                    <p>Mitra Fokus</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 fact-right wow slideInRight">
                        <div class="row">
                            <div class="col-6">
                                <div class="fact-icon">
                                    <i class="flaticon-address"></i>
                                </div>
                                <div class="fact-text">
                                    <h2 data-toggle="counter-up">100</h2>
                                    <p>Orientasi Kepuasan</p>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="fact-icon">
                                    <i class="flaticon-crane"></i>
                                </div>
                                <div class="fact-text">
                                    <h2 data-toggle="counter-up">24</h2>
                                    <p>Respons Kebutuhan</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Fact End -->


        <!-- Service Start -->
        <div class="service" id="layanan">
            <div class="container">
                <div class="section-header text-center">
                    <p>Layanan Kami</p>
                    <h2>Solusi Jasa dan Konstruksi</h2>
                </div>
                <div class="row">
                    <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.1s">
                        <div class="service-item">
                            <div class="service-img">
                                <img src="{{asset('landing/img/service-1.jpg')}}" alt="Image">
                                <div class="service-overlay">
                                    <p>
                                        Layanan pengantaran barang untuk mendukung distribusi kebutuhan operasional
                                        mitra secara tepat, tertib, dan sesuai arahan pekerjaan.
                                    </p>
                                </div>
                            </div>
                            <div class="service-text">
                                <h3>Pengantaran Barang</h3>
                                <a class="btn" href="{{asset('landing/img/service-1.jpg')}}"
                                    data-lightbox="service">+</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.2s">
                        <div class="service-item">
                            <div class="service-img">
                                <img src="{{asset('landing/img/service-2.jpg')}}" alt="Image">
                                <div class="service-overlay">
                                    <p>
                                        Pengadaan kebutuhan perkantoran dilakukan sesuai permintaan mitra agar aktivitas
                                        kerja tetap berjalan lancar dan efisien.
                                    </p>
                                </div>
                            </div>
                            <div class="service-text">
                                <h3>Pengadaan Perkantoran</h3>
                                <a class="btn" href="{{asset('landing/img/service-2.jpg')}}"
                                    data-lightbox="service">+</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.3s">
                        <div class="service-item">
                            <div class="service-img">
                                <img src="{{asset('landing/img/service-3.jpg')}}" alt="Image">
                                <div class="service-overlay">
                                    <p>
                                        Penanganan kebocoran, pengecekan fasilitas, dan pekerjaan perawatan gedung lain
                                        dilakukan untuk menjaga kenyamanan lingkungan kerja.
                                    </p>
                                </div>
                            </div>
                            <div class="service-text">
                                <h3>Maintenance Gedung</h3>
                                <a class="btn" href="{{asset('landing/img/service-3.jpg')}}"
                                    data-lightbox="service">+</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.4s">
                        <div class="service-item">
                            <div class="service-img">
                                <img src="{{asset('landing/img/service-4.jpg')}}" alt="Image">
                                <div class="service-overlay">
                                    <p>
                                        Pekerjaan pengecatan ruangan dikerjakan dengan memperhatikan kerapian,
                                        ketahanan hasil, dan kebutuhan pemakaian ruangan.
                                    </p>
                                </div>
                            </div>
                            <div class="service-text">
                                <h3>Pengecatan Ruangan</h3>
                                <a class="btn" href="{{asset('landing/img/service-4.jpg')}}"
                                    data-lightbox="service">+</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.5s">
                        <div class="service-item">
                            <div class="service-img">
                                <img src="{{asset('landing/img/service-5.jpg')}}" alt="Image">
                                <div class="service-overlay">
                                    <p>
                                        Perbaikan ringan dan dukungan teknis lapangan disesuaikan dengan kondisi
                                        pekerjaan agar solusi dapat diberikan secara efektif.
                                    </p>
                                </div>
                            </div>
                            <div class="service-text">
                                <h3>Perawatan Operasional</h3>
                                <a class="btn" href="{{asset('landing/img/service-5.jpg')}}"
                                    data-lightbox="service">+</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.6s">
                        <div class="service-item">
                            <div class="service-img">
                                <img src="{{asset('landing/img/service-6.jpg')}}" alt="Image">
                                <div class="service-overlay">
                                    <p>
                                        Renovasi ruangan dikerjakan dengan mengedepankan kualitas hasil, ketepatan waktu,
                                        serta efisiensi kerja sesuai kebutuhan mitra.
                                    </p>
                                </div>
                            </div>
                            <div class="service-text">
                                <h3>Renovasi Ruangan</h3>
                                <a class="btn" href="{{asset('landing/img/service-6.jpg')}}"
                                    data-lightbox="service">+</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Service End -->


        <!-- Video Start -->
        <div class="video wow fadeIn" data-wow-delay="0.1s">
            <div class="container">
                <button type="button" class="btn-play" data-toggle="modal"
                    data-src="https://www.youtube.com/embed/DWRcNpR6Kdc" data-target="#videoModal">
                    <span></span>
                </button>
            </div>
        </div>

        <div class="modal fade" id="videoModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-body">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <!-- 16:9 aspect ratio -->
                        <div class="embed-responsive embed-responsive-16by9">
                            <iframe class="embed-responsive-item" src="" id="video" allowscriptaccess="always"
                                allow="autoplay"></iframe>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Video End -->


        <!-- Team Start -->
        <div class="team" id="komitmen">
            <div class="container">
                <div class="section-header text-center">
                    <p>Komitmen Kerja</p>
                    <h2>Nilai yang Kami Jaga</h2>
                </div>
                <div class="row">
                    <div class="col-lg-3 col-md-6 wow fadeInUp" data-wow-delay="0.1s">
                        <div class="team-item">
                            <div class="team-img">
                                <img src="{{asset('landing/img/team-1.jpg')}}" alt="Team Image">
                            </div>
                            <div class="team-text">
                                <h2>Responsif</h2>
                                <p>Cepat memahami kebutuhan dan arahan mitra.</p>
                            </div>
                            <div class="team-social">
                                <a class="social-tw" href=""><i class="fab fa-twitter"></i></a>
                                <a class="social-fb" href=""><i class="fab fa-facebook-f"></i></a>
                                <a class="social-li" href=""><i class="fab fa-linkedin-in"></i></a>
                                <a class="social-in" href=""><i class="fab fa-instagram"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 wow fadeInUp" data-wow-delay="0.2s">
                        <div class="team-item">
                            <div class="team-img">
                                <img src="{{asset('landing/img/team-2.jpg')}}" alt="Team Image">
                            </div>
                            <div class="team-text">
                                <h2>Profesional</h2>
                                <p>Menjaga kualitas komunikasi dan pelaksanaan kerja.</p>
                            </div>
                            <div class="team-social">
                                <a class="social-tw" href=""><i class="fab fa-twitter"></i></a>
                                <a class="social-fb" href=""><i class="fab fa-facebook-f"></i></a>
                                <a class="social-li" href=""><i class="fab fa-linkedin-in"></i></a>
                                <a class="social-in" href=""><i class="fab fa-instagram"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 wow fadeInUp" data-wow-delay="0.3s">
                        <div class="team-item">
                            <div class="team-img">
                                <img src="{{asset('landing/img/team-3.jpg')}}" alt="Team Image">
                            </div>
                            <div class="team-text">
                                <h2>Tepat Waktu</h2>
                                <p>Mengutamakan penyelesaian pekerjaan sesuai target.</p>
                            </div>
                            <div class="team-social">
                                <a class="social-tw" href=""><i class="fab fa-twitter"></i></a>
                                <a class="social-fb" href=""><i class="fab fa-facebook-f"></i></a>
                                <a class="social-li" href=""><i class="fab fa-linkedin-in"></i></a>
                                <a class="social-in" href=""><i class="fab fa-instagram"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 wow fadeInUp" data-wow-delay="0.4s">
                        <div class="team-item">
                            <div class="team-img">
                                <img src="{{asset('landing/img/team-4.jpg')}}" alt="Team Image">
                            </div>
                            <div class="team-text">
                                <h2>Solutif</h2>
                                <p>Memberikan dukungan yang sesuai dengan kondisi lapangan.</p>
                            </div>
                            <div class="team-social">
                                <a class="social-tw" href=""><i class="fab fa-twitter"></i></a>
                                <a class="social-fb" href=""><i class="fab fa-facebook-f"></i></a>
                                <a class="social-li" href=""><i class="fab fa-linkedin-in"></i></a>
                                <a class="social-in" href=""><i class="fab fa-instagram"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Team End -->


        <!-- FAQs Start -->
        <div class="faqs">
            <div class="container">
                <div class="section-header text-center">
                    <p>Pertanyaan Umum</p>
                    <h2>Informasi Layanan</h2>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div id="accordion-1">
                            <div class="card wow fadeInLeft" data-wow-delay="0.1s">
                                <div class="card-header">
                                    <a class="card-link collapsed" data-toggle="collapse" href="#collapseOne">
                                        Apa bidang usaha PT. Tidarjaya Solidindo?
                                    </a>
                                </div>
                                <div id="collapseOne" class="collapse" data-parent="#accordion-1">
                                    <div class="card-body">
                                        PT. Tidarjaya Solidindo bergerak di bidang jasa operasional dan konstruksi,
                                        terutama untuk kebutuhan logistik, pengadaan, maintenance gedung, dan renovasi
                                        ruangan.
                                    </div>
                                </div>
                            </div>
                            <div class="card wow fadeInLeft" data-wow-delay="0.2s">
                                <div class="card-header">
                                    <a class="card-link collapsed" data-toggle="collapse" href="#collapseTwo">
                                        Layanan jasa apa saja yang tersedia?
                                    </a>
                                </div>
                                <div id="collapseTwo" class="collapse" data-parent="#accordion-1">
                                    <div class="card-body">
                                        Layanan jasa meliputi pengantaran barang, pengadaan kebutuhan perkantoran,
                                        perawatan gedung, penanganan kebocoran, pengecatan, dan pekerjaan pemeliharaan
                                        lainnya.
                                    </div>
                                </div>
                            </div>
                            <div class="card wow fadeInLeft" data-wow-delay="0.3s">
                                <div class="card-header">
                                    <a class="card-link collapsed" data-toggle="collapse" href="#collapseThree">
                                        Apa fokus layanan konstruksi perusahaan?
                                    </a>
                                </div>
                                <div id="collapseThree" class="collapse" data-parent="#accordion-1">
                                    <div class="card-body">
                                        Fokus konstruksi perusahaan adalah renovasi ruangan dengan memperhatikan kualitas
                                        hasil, ketepatan waktu, dan efisiensi pelaksanaan.
                                    </div>
                                </div>
                            </div>
                            <div class="card wow fadeInLeft" data-wow-delay="0.4s">
                                <div class="card-header">
                                    <a class="card-link collapsed" data-toggle="collapse" href="#collapseFour">
                                        Siapa mitra utama yang dilayani?
                                    </a>
                                </div>
                                <div id="collapseFour" class="collapse" data-parent="#accordion-1">
                                    <div class="card-body">
                                        Saat ini perusahaan berfokus melayani lingkungan Kementerian sebagai mitra utama,
                                        dengan pekerjaan yang menyesuaikan kebutuhan dan arahan yang diberikan.
                                    </div>
                                </div>
                            </div>
                            <div class="card wow fadeInLeft" data-wow-delay="0.5s">
                                <div class="card-header">
                                    <a class="card-link collapsed" data-toggle="collapse" href="#collapseFive">
                                        Bagaimana proses pekerjaan disesuaikan?
                                    </a>
                                </div>
                                <div id="collapseFive" class="collapse" data-parent="#accordion-1">
                                    <div class="card-body">
                                        Setiap pekerjaan disusun berdasarkan kebutuhan mitra, kondisi lapangan, arahan
                                        pelaksanaan, serta target waktu yang telah disepakati.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div id="accordion-2">
                            <div class="card wow fadeInRight" data-wow-delay="0.1s">
                                <div class="card-header">
                                    <a class="card-link collapsed" data-toggle="collapse" href="#collapseSix">
                                        Apakah layanan maintenance mencakup pekerjaan kecil?
                                    </a>
                                </div>
                                <div id="collapseSix" class="collapse" data-parent="#accordion-2">
                                    <div class="card-body">
                                        Ya. Layanan maintenance dapat mencakup pekerjaan perawatan ringan seperti
                                        pengecatan, perbaikan kebocoran, dan dukungan pemeliharaan ruangan lainnya.
                                    </div>
                                </div>
                            </div>
                            <div class="card wow fadeInRight" data-wow-delay="0.2s">
                                <div class="card-header">
                                    <a class="card-link collapsed" data-toggle="collapse" href="#collapseSeven">
                                        Apa keunggulan pelayanan perusahaan?
                                    </a>
                                </div>
                                <div id="collapseSeven" class="collapse" data-parent="#accordion-2">
                                    <div class="card-body">
                                        Perusahaan mengutamakan pelayanan yang responsif, profesional, berorientasi pada
                                        solusi, dan menjaga kepuasan mitra.
                                    </div>
                                </div>
                            </div>
                            <div class="card wow fadeInRight" data-wow-delay="0.3s">
                                <div class="card-header">
                                    <a class="card-link collapsed" data-toggle="collapse" href="#collapseEight">
                                        Apakah pekerjaan dapat mengikuti arahan instansi?
                                    </a>
                                </div>
                                <div id="collapseEight" class="collapse" data-parent="#accordion-2">
                                    <div class="card-body">
                                        Ya. Pelaksanaan pekerjaan dapat disesuaikan dengan arahan instansi, kebutuhan
                                        operasional, dan standar yang berlaku di lingkungan kerja mitra.
                                    </div>
                                </div>
                            </div>
                            <div class="card wow fadeInRight" data-wow-delay="0.4s">
                                <div class="card-header">
                                    <a class="card-link collapsed" data-toggle="collapse" href="#collapseNine">
                                        Apakah perusahaan menangani pengadaan perkantoran?
                                    </a>
                                </div>
                                <div id="collapseNine" class="collapse" data-parent="#accordion-2">
                                    <div class="card-body">
                                        Ya. Perusahaan menyediakan layanan pengadaan kebutuhan perkantoran untuk
                                        mendukung kelancaran aktivitas operasional klien.
                                    </div>
                                </div>
                            </div>
                            <div class="card wow fadeInRight" data-wow-delay="0.5s">
                                <div class="card-header">
                                    <a class="card-link collapsed" data-toggle="collapse" href="#collapseTen">
                                        Bagaimana cara menghubungi perusahaan?
                                    </a>
                                </div>
                                <div id="collapseTen" class="collapse" data-parent="#accordion-2">
                                    <div class="card-body">
                                        Mitra dapat menghubungi perusahaan melalui kontak resmi yang tersedia untuk
                                        menyampaikan kebutuhan pekerjaan dan konsultasi layanan.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- FAQs End -->


        <!-- Testimonial Start -->
        <div class="testimonial wow fadeIn" data-wow-delay="0.1s">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="testimonial-slider-nav">
                            <div class="slider-nav"><img src="{{asset('landing/img/testimonial-1.jpg')}}"
                                    alt="Testimonial"></div>
                            <div class="slider-nav"><img src="{{asset('landing/img/testimonial-2.jpg')}}"
                                    alt="Testimonial"></div>
                            <div class="slider-nav"><img src="{{asset('landing/img/testimonial-3.jpg')}}"
                                    alt="Testimonial"></div>
                            <div class="slider-nav"><img src="{{asset('landing/img/testimonial-4.jpg')}}"
                                    alt="Testimonial"></div>
                            <div class="slider-nav"><img src="{{asset('landing/img/testimonial-1.jpg')}}"
                                    alt="Testimonial"></div>
                            <div class="slider-nav"><img src="{{asset('landing/img/testimonial-2.jpg')}}"
                                    alt="Testimonial"></div>
                            <div class="slider-nav"><img src="{{asset('landing/img/testimonial-3.jpg')}}"
                                    alt="Testimonial"></div>
                            <div class="slider-nav"><img src="{{asset('landing/img/testimonial-4.jpg')}}"
                                    alt="Testimonial"></div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="testimonial-slider">
                            <div class="slider-item">
                                <h3>Mitra Operasional</h3>
                                <h4>Layanan Jasa</h4>
                                <p>Pelayanan yang responsif membantu kebutuhan operasional terselesaikan dengan lebih
                                    tertib dan sesuai arahan pekerjaan.</p>
                            </div>
                            <div class="slider-item">
                                <h3>Mitra Konstruksi</h3>
                                <h4>Renovasi Ruangan</h4>
                                <p>Pekerjaan renovasi direncanakan dengan memperhatikan kualitas hasil, efisiensi kerja,
                                    dan ketepatan waktu pelaksanaan.</p>
                            </div>
                            <div class="slider-item">
                                <h3>Mitra Maintenance</h3>
                                <h4>Perawatan Gedung</h4>
                                <p>Penanganan pekerjaan perawatan dilakukan sesuai kondisi lapangan agar aktivitas kerja
                                    klien tetap berjalan lancar.</p>
                            </div>
                            <div class="slider-item">
                                <h3>Mitra Pengadaan</h3>
                                <h4>Kebutuhan Perkantoran</h4>
                                <p>Pengadaan kebutuhan kerja didukung proses yang menyesuaikan permintaan dan prioritas
                                    operasional mitra.</p>
                            </div>
                            <div class="slider-item">
                                <h3>Mitra Kementerian</h3>
                                <h4>Dukungan Operasional</h4>
                                <p>Pelaksanaan pekerjaan mengikuti kebutuhan dan arahan instansi sehingga hasilnya lebih
                                    relevan dengan lingkungan kerja.</p>
                            </div>
                            <div class="slider-item">
                                <h3>Mitra Fasilitas</h3>
                                <h4>Pemeliharaan Ruangan</h4>
                                <p>Tim membantu pekerjaan pemeliharaan seperti pengecatan dan perbaikan agar ruangan
                                    tetap nyaman digunakan.</p>
                            </div>
                            <div class="slider-item">
                                <h3>Mitra Proyek</h3>
                                <h4>Koordinasi Lapangan</h4>
                                <p>Koordinasi yang jelas membantu pekerjaan berjalan lebih terarah sejak kebutuhan
                                    diterima sampai penyelesaian.</p>
                            </div>
                            <div class="slider-item">
                                <h3>Mitra Layanan</h3>
                                <h4>Solusi Kerja</h4>
                                <p>Setiap permintaan ditangani dengan pendekatan solutif agar hasil pekerjaan mendukung
                                    kelancaran aktivitas klien.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Testimonial End -->


        <!-- Blog Start -->
        <div class="blog">
            <div class="container">
                <div class="section-header text-center">
                    <p>Ruang Informasi</p>
                    <h2>Fokus Layanan Perusahaan</h2>
                </div>
                <div class="row">
                    <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.2s">
                        <div class="blog-item">
                            <div class="blog-img">
                                <img src="{{asset('landing/img/blog-1.jpg')}}" alt="Image">
                            </div>
                            <div class="blog-title">
                                <h3>Jasa Operasional Terpadu</h3>
                                <a class="btn" href="">+</a>
                            </div>
                            <div class="blog-meta">
                                <p>By<a href="">Admin</a></p>
                                <p>Dalam<a href="">Jasa</a></p>
                            </div>
                            <div class="blog-text">
                                <p>
                                    Dukungan pengantaran barang dan pengadaan kebutuhan kantor membantu aktivitas
                                    operasional mitra berjalan lebih lancar.
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 wow fadeInUp">
                        <div class="blog-item">
                            <div class="blog-img">
                                <img src="{{asset('landing/img/blog-2.jpg')}}" alt="Image">
                            </div>
                            <div class="blog-title">
                                <h3>Maintenance Gedung Responsif</h3>
                                <a class="btn" href="">+</a>
                            </div>
                            <div class="blog-meta">
                                <p>By<a href="">Admin</a></p>
                                <p>Dalam<a href="">Maintenance</a></p>
                            </div>
                            <div class="blog-text">
                                <p>
                                    Perawatan gedung seperti perbaikan kebocoran dan pengecatan ruangan ditangani untuk
                                    menjaga kenyamanan area kerja.
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.2s">
                        <div class="blog-item">
                            <div class="blog-img">
                                <img src="{{asset('landing/img/blog-3.jpg')}}" alt="Image">
                            </div>
                            <div class="blog-title">
                                <h3>Renovasi Ruangan Efisien</h3>
                                <a class="btn" href="">+</a>
                            </div>
                            <div class="blog-meta">
                                <p>By<a href="">Admin</a></p>
                                <p>Dalam<a href="">Konstruksi</a></p>
                            </div>
                            <div class="blog-text">
                                <p>
                                    Pekerjaan renovasi ruangan difokuskan pada kualitas hasil, ketepatan waktu, dan
                                    efisiensi kerja sesuai kebutuhan mitra.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Blog End -->


        <!-- Footer Start -->
        <div class="footer wow fadeIn" data-wow-delay="0.3s" id="kontak">
            <div class="container">
                <div class="row">
                    <div class="col-md-6 col-lg-3">
                        <div class="footer-contact">
                            <h2>Kontak Kantor</h2>
                            <p><i class="fa fa-map-marker-alt"></i>Indonesia</p>
                            <p><i class="fa fa-phone-alt"></i>Hubungi kontak resmi perusahaan</p>
                            <p><i class="fa fa-envelope"></i>Email resmi perusahaan</p>
                            <div class="footer-social">
                                <a href=""><i class="fab fa-twitter"></i></a>
                                <a href=""><i class="fab fa-facebook-f"></i></a>
                                <a href=""><i class="fab fa-youtube"></i></a>
                                <a href=""><i class="fab fa-instagram"></i></a>
                                <a href=""><i class="fab fa-linkedin-in"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <div class="footer-link">
                            <h2>Area Layanan</h2>
                            <a href="#layanan">Pengantaran Barang</a>
                            <a href="#layanan">Pengadaan Perkantoran</a>
                            <a href="#layanan">Maintenance Gedung</a>
                            <a href="#layanan">Pengecatan Ruangan</a>
                            <a href="#layanan">Renovasi Ruangan</a>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <div class="footer-link">
                            <h2>Halaman</h2>
                            <a href="#tentang">Tentang Kami</a>
                            <a href="#layanan">Layanan</a>
                            <a href="#komitmen">Komitmen</a>
                            <a href="#kontak">Kontak</a>
                            <a href="#carousel">Beranda</a>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <div class="newsletter">
                            <h2>Konsultasi Layanan</h2>
                            <p>
                                Sampaikan kebutuhan operasional, maintenance, atau renovasi ruangan Anda melalui kontak
                                resmi perusahaan.
                            </p>
                            <div class="form">
                                <input class="form-control" placeholder="Email Anda">
                                <button class="btn">Kirim</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="container footer-menu">
                <div class="f-menu">
                    <a href="#tentang">Profil</a>
                    <a href="#layanan">Jasa</a>
                    <a href="#layanan">Konstruksi</a>
                    <a href="#komitmen">Komitmen</a>
                    <a href="#kontak">FAQ</a>
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
        <!-- Footer End -->

        <a href="#" class="back-to-top"><i class="fa fa-chevron-up"></i></a>
    </div>

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js"></script>
    <script src="{{asset('landing/lib/easing/easing.min.js')}}"></script>
    <script src="{{asset('landing/lib/wow/wow.min.js')}}"></script>
    <script src="{{asset('landing/lib/owlcarousel/owl.carousel.min.js')}}"></script>
    <script src="{{asset('landing/lib/isotope/isotope.pkgd.min.js')}}"></script>
    <script src="{{asset('landing/lib/lightbox/js/lightbox.min.js')}}"></script>
    <script src="{{asset('landing/lib/waypoints/waypoints.min.js')}}"></script>
    <script src="{{asset('landing/lib/counterup/counterup.min.js')}}"></script>
    <script src="{{asset('landing/lib/slick/slick.min.js')}}"></script>

    <!-- Template Javascript -->
    <script src="{{asset('landing/js/main.js')}}"></script>
</body>

</html>
