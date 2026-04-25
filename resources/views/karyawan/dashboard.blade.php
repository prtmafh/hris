@extends('karyawan.layouts.app')

@section('title', 'Dashboard Absensi Karyawan')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
    #map {
        height: 320px;
        border-radius: 0.75rem;
        border: 1px solid rgba(0, 0, 0, 0.08);
    }

    @media (min-width: 768px) {
        #map {
            height: 380px;
        }
    }

    .clock-display {
        font-size: clamp(2rem, 7vw, 3.4rem);
        font-weight: 700;
        letter-spacing: -0.03em;
    }

    .date-display {
        font-size: 0.95rem;
        color: #69707a;
    }

    .attendance-panel {
        border: 0;
        box-shadow: 0 0.15rem 1.75rem 0 rgba(33, 40, 50, 0.1);
    }

    .status-list .list-group-item {
        padding: 0.9rem 0;
        border-color: rgba(0, 0, 0, 0.06);
    }

    .info-box {
        background: #f8f9fc;
        border: 1px solid rgba(0, 0, 0, 0.06);
        border-radius: 0.75rem;
    }

    .hero-status {
        border: 0;
        box-shadow: 0 0.15rem 1.75rem 0 rgba(33, 40, 50, 0.12);
    }

    .hero-status-label {
        font-size: 0.78rem;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        color: #69707a;
        font-weight: 700;
    }

    .hero-status-value {
        font-size: clamp(1.8rem, 5vw, 2.6rem);
        font-weight: 700;
        line-height: 1.1;
    }

    .simple-note {
        font-size: 1rem;
        color: #4b5563;
    }

    .btn-absen {
        min-width: 220px;
        padding: 1rem 1.5rem;
        font-weight: 600;
        border-radius: 0.65rem;
        border: none;
        box-shadow: 0 0.15rem 1rem 0 rgba(33, 40, 50, 0.12);
        font-size: 1.1rem;
    }

    .btn-success-absen {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    }

    .btn-danger-absen {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
    }

    .btn-absen:disabled {
        opacity: 0.55;
        cursor: not-allowed;
    }

    .btn-absen:not(:disabled):hover {
        transform: translateY(-1px);
    }

    .action-group {
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
    }

    @media (max-width: 576px) {
        .action-group {
            flex-direction: column;
        }

        .btn-absen {
            width: 100%;
        }
    }

    .company-mark {
        width: 56px;
        height: 56px;
        object-fit: contain;
    }

    .mini-label {
        font-size: 0.72rem;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: rgba(255, 255, 255, 0.75);
    }

    .history-table th {
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: #69707a;
    }

    .history-table td {
        vertical-align: middle;
    }

    .quick-guide-item {
        border-bottom: 1px solid rgba(0, 0, 0, 0.06);
        padding: 0.9rem 0;
    }

    .quick-guide-item:last-child {
        border-bottom: 0;
        padding-bottom: 0;
    }

    .helper-text {
        font-size: 0.95rem;
        color: #69707a;
    }
</style>
@endpush

@section('content')
<main>
    <header class="page-header page-header-dark bg-gradient-primary-to-secondary pb-10">
        <div class="container-xl px-4">
            <div class="page-header-content pt-4">
                <div class="row align-items-center justify-content-between">
                    <div class="col-xl-8">
                        <h1 class="page-header-title">
                            <div class="page-header-icon"><i data-feather="activity"></i></div>
                            Dashboard Absensi
                        </h1>
                        <div class="page-header-subtitle" id="greetingText">Selamat Datang</div>
                    </div>
                    <div class="col-xl-4 mt-4 mt-xl-0">
                        <div class="card border-0 shadow-sm bg-white bg-opacity-10">
                            <div class="card-body py-3">
                                <div class="d-flex align-items-center gap-3">
                                    <img src="{{ asset('assets/img/logotsi.png') }}" alt="logo" class="company-mark">
                                    <div>
                                        <div class="mini-label">Perusahaan</div>
                                        <div class="fw-bold text-white">PT. Tidarjaya Solidindo</div>
                                        <div class="small text-white-50">Dashboard absensi karyawan</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div class="container-xl px-4 mt-n10">

        <div class="row">
            <div class="col-xl-8 mb-4">
                <div class="card attendance-panel h-100">
                    {{-- <div
                        class="card-header d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2">
                        <div>
                            <div class="fw-bold">Absensi Hari Ini</div>
                            <div class="small text-muted">Tekan tombol besar di bawah untuk absen masuk atau absen
                                pulang.</div>
                        </div>
                        <span class="badge bg-light text-dark border">Mudah digunakan</span>
                    </div> --}}
                    <div class="card-body">
                        <div class="row g-4">
                            <div class="col-lg-7">
                                <div id="map" class="mb-4"></div>
                                <div class="action-group justify-content-center mb-4">
                                    @if($statusGaji === 'harian')
                                    @php
                                    $bisaPulangSesi = !!$aktiveSesi;
                                    $labelMasuk = $bisaMasukSesi && $sesiSaatIni
                                    ? "Absen Masuk Sesi {$sesiSaatIni}"
                                    : 'Absen Masuk Sesi';
                                    $labelPulang = $aktiveSesi
                                    ? "Absen Pulang Sesi {$aktiveSesi->sesi_ke}"
                                    : 'Absen Pulang Sesi';
                                    @endphp
                                    <button id="btnMasukSesi" class="btn btn-absen btn-success-absen text-white" {{
                                        $bisaMasukSesi ? '' : 'disabled' }}>
                                        <i data-feather="log-in" class="me-1"></i> {{ $labelMasuk }}
                                    </button>
                                    <button id="btnPulangSesi" class="btn btn-absen btn-danger-absen text-white" {{
                                        $bisaPulangSesi ? '' : 'disabled' }}>
                                        <i data-feather="log-out" class="me-1"></i> {{ $labelPulang }}
                                    </button>
                                    @else
                                    <button id="btnMasuk" class="btn btn-absen btn-success-absen text-white" {{ ($cek &&
                                        $cek->jam_masuk) ? 'disabled' : '' }}>
                                        <i data-feather="log-in" class="me-1"></i> Absen Masuk
                                    </button>
                                    <button id="btnPulang" class="btn btn-absen btn-danger-absen text-white" {{ (!$cek
                                        || !$cek->jam_masuk || $cek->jam_keluar) ? 'disabled' : '' }}>
                                        <i data-feather="log-out" class="me-1"></i> Absen Pulang
                                    </button>
                                    @endif
                                </div>
                                <div class="text-center">
                                    <div id="clock" class="clock-display mb-1"></div>
                                    <div class="date-display" id="today"></div>
                                </div>
                            </div>
                            <div class="col-lg-5">
                                <div class="info-box p-4 mb-4">
                                    <div class="small text-uppercase text-muted fw-bold mb-3">Keterangan Hari Ini</div>
                                    @if($statusGaji === 'harian')
                                    @if($sesiHariIni->isEmpty())
                                    <div class="text-center text-muted py-2">
                                        <span class="badge bg-danger px-3 py-2">Belum Ada Sesi</span>
                                    </div>
                                    @else
                                    <div class="table-responsive">
                                        <table class="table table-sm mb-0">
                                            <thead>
                                                <tr>
                                                    <th class="small text-muted">Sesi</th>
                                                    <th class="small text-muted">Masuk</th>
                                                    <th class="small text-muted">Pulang</th>
                                                    <th class="small text-muted">Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($sesiHariIni as $sesi)
                                                @php
                                                $sBadge = match($sesi->status) {
                                                'hadir' => 'success',
                                                'terlambat' => 'warning',
                                                'izin' => 'info',
                                                'alpha' => 'danger',
                                                default => 'secondary',
                                                };
                                                @endphp
                                                <tr>
                                                    <td class="fw-semibold">{{ $sesi->sesi_ke }}</td>
                                                    <td>{{ $sesi->jam_checkin ?
                                                        \Carbon\Carbon::parse($sesi->jam_checkin)->format('H:i') :
                                                        '--:--' }}</td>
                                                    <td>{{ $sesi->jam_checkout ?
                                                        \Carbon\Carbon::parse($sesi->jam_checkout)->format('H:i') :
                                                        '--:--' }}</td>
                                                    <td><span class="badge bg-{{ $sBadge }} text-capitalize">{{
                                                            $sesi->status }}</span></td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    @endif
                                    @else
                                    <div class="list-group list-group-flush status-list">
                                        <div
                                            class="list-group-item px-0 d-flex justify-content-between align-items-center bg-transparent">
                                            <span class="fw-semibold text-gray-700">Status</span>
                                            <span>
                                                @if (is_null($cek))
                                                <span class="badge bg-danger px-3 py-2">Belum Absen</span>
                                                @else
                                                @php
                                                $statusBadge = match($cek->status) {
                                                'hadir' => 'success',
                                                'izin' => 'info',
                                                'alpha' => 'danger',
                                                'terlambat' => 'warning',
                                                default => 'secondary',
                                                };
                                                @endphp
                                                <span class="badge bg-{{ $statusBadge }} text-capitalize px-3 py-2">{{
                                                    $cek->status }}</span>
                                                @endif
                                            </span>
                                        </div>
                                        <div
                                            class="list-group-item px-0 d-flex justify-content-between align-items-center bg-transparent">
                                            <span class="fw-semibold text-gray-700">Waktu Datang</span>
                                            <span class="badge bg-light text-dark border px-3 py-2">{{ $cek->jam_masuk
                                                ?? '--:--:--' }}</span>
                                        </div>
                                        <div
                                            class="list-group-item px-0 d-flex justify-content-between align-items-center bg-transparent">
                                            <span class="fw-semibold text-gray-700">Waktu Pulang</span>
                                            <span class="badge bg-light text-dark border px-3 py-2">{{ $cek->jam_keluar
                                                ?? '--:--:--' }}</span>
                                        </div>
                                    </div>
                                    @endif
                                </div>

                                <div class="info-box p-4">
                                    <div class="small text-uppercase text-muted fw-bold mb-3">Jam Kerja</div>
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <span class="text-muted">Jam Masuk</span>
                                        <span class="fw-bold">{{ $shift_start ?
                                            \Carbon\Carbon::parse($shift_start)->format('H:i') : '-' }} WIB</span>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <span class="text-muted">Jam Pulang</span>
                                        <span class="fw-bold">{{ $shift_end ?
                                            \Carbon\Carbon::parse($shift_end)->format('H:i') : '-' }} WIB</span>
                                    </div>
                                    <div class="helper-text mb-0">
                                        Bila ada kendala, hubungi admin atau mandor sebelum meninggalkan lokasi kerja.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-4 mb-4">
                {{-- <div class="card attendance-panel mb-4">
                    <div class="card-header">
                        <div class="fw-bold">Riwayat Absensi Terbaru</div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover history-table mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="ps-4">Tanggal</th>
                                        <th>Masuk</th>
                                        <th>Pulang</th>
                                        <th class="pe-4">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($riwayatAbsensi as $r)
                                    @php
                                    $badge = match($r->status) {
                                    'hadir' => 'success',
                                    'terlambat' => 'warning',
                                    'izin' => 'info',
                                    'alpha' => 'danger',
                                    default => 'secondary',
                                    };
                                    @endphp
                                    <tr>
                                        <td class="ps-4">
                                            <div class="fw-semibold">{{
                                                \Carbon\Carbon::parse($r->tanggal)->format('d/m/Y') }}</div>
                                        </td>
                                        <td>{{ $r->jam_masuk ? \Carbon\Carbon::parse($r->jam_masuk)->format('H:i') : '-'
                                            }}</td>
                                        <td>{{ $r->jam_keluar ? \Carbon\Carbon::parse($r->jam_keluar)->format('H:i') :
                                            '-' }}</td>
                                        <td class="pe-4">
                                            <span class="badge bg-{{ $badge }} text-capitalize">{{ $r->status }}</span>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-4">
                                            Belum ada riwayat absensi.
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div> --}}

                <div class="card attendance-panel">
                    <div class="card-header">
                        <div class="fw-bold">Informasi Singkat</div>
                    </div>
                    <div class="card-body">
                        <div class="quick-guide-item pt-0">
                            <div class="fw-semibold mb-1">Hadir bulan ini</div>
                            <div class="helper-text">{{ $totalHadirBulanIni }} kali kehadiran tercatat.</div>
                        </div>
                        <div class="quick-guide-item">
                            <div class="fw-semibold mb-1">Izin menunggu</div>
                            <div class="helper-text">{{ $totalIzinPending }} pengajuan belum diproses.</div>
                        </div>
                        <div class="quick-guide-item">
                            <div class="fw-semibold mb-1">Lembur disetujui</div>
                            <div class="helper-text">
                                {{ rtrim(rtrim(number_format($totalLemburBulanIni, 2, ',', '.'), '0'), ',') }} jam pada
                                bulan ini.
                            </div>
                        </div>
                        <div class="pt-3">
                            <div class="fw-semibold mb-1">Gaji terbaru</div>
                            @if($penggajianTerbaru)
                            <div class="h5 text-success mb-1">Rp {{ number_format($penggajianTerbaru->total_gaji, 0,
                                ',', '.') }}</div>
                            <div class="helper-text">
                                Periode {{
                                \Carbon\Carbon::create()->month($penggajianTerbaru->periode_bulan)->translatedFormat('F')
                                }}
                                {{ $penggajianTerbaru->periode_tahun }}
                            </div>
                            @else
                            <div class="helper-text">Belum ada data penggajian terbaru.</div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
    const hours = new Date().getHours();
    let greeting = "Selamat Datang";
    if      (hours >= 4  && hours < 11) greeting = "Selamat Pagi";
    else if (hours >= 11 && hours < 15) greeting = "Selamat Siang";
    else if (hours >= 15 && hours < 18) greeting = "Selamat Sore";
    else greeting = "Selamat Malam";
    document.getElementById('greetingText').innerHTML =
        greeting + ', <strong class="text-capitalize">{{ $namaKaryawan }}</strong>!';

    //map
    var map = L.map('map', { zoomControl: true }).setView([0, 0], 15);
    var marker;
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    if (navigator.geolocation) {
        navigator.geolocation.watchPosition(function(position) {
            var lat = position.coords.latitude;
            var lon = position.coords.longitude;
            var acc = position.coords.accuracy;
            map.setView([lat, lon], 17);
            if (marker) {
                marker.setLatLng([lat, lon])
                    .bindPopup('Lokasi Anda<br>Akurasi: ' + Math.round(acc) + ' m').openPopup();
            } else {
                marker = L.marker([lat, lon]).addTo(map)
                    .bindPopup('Lokasi Anda<br>Akurasi: ' + Math.round(acc) + ' m').openPopup();
            }
        });
    }

    //jam
    function updateClock() {
        const now = new Date();
        document.getElementById('clock').textContent = now.toLocaleTimeString('id-ID');
        document.getElementById('today').textContent = now.toLocaleDateString('id-ID', {
            weekday: 'long', year: 'numeric', month: 'long', day: 'numeric'
        });
    }
    setInterval(updateClock, 1000);
    updateClock();

    // --- CAMERA HELPERS ---
    let stream       = null;
    let isProcessing = false;
    let videoReady   = false;

    function bukaKameraPopup(judulAbsen, urlAbsen) {
        if (isProcessing) return;
        isProcessing = true;
        videoReady   = false;
        if (window.innerWidth <= 768) {
            bukaKameraFullScreen(judulAbsen, urlAbsen);
        } else {
            bukaKameraPopupDesktop(judulAbsen, urlAbsen);
        }
    }

    function bukaKameraFullScreen(judulAbsen, urlAbsen) {
        const overlay = document.createElement('div');
        overlay.id = 'cameraOverlay';
        overlay.style.cssText = 'position:fixed;top:0;left:0;width:100%;height:100%;background:#000;z-index:9999;display:flex;flex-direction:column;';

        overlay.innerHTML = `
            <div style="flex:0 0 auto;background:rgba(0,0,0,.8);padding:1rem;display:flex;justify-content:space-between;align-items:center;">
                <button id="btnCloseCamera" style="background:transparent;border:none;color:white;font-size:1.5rem;padding:.5rem;">&#x2715;</button>
                <h3 style="color:white;margin:0;font-size:1rem;font-weight:600;">${judulAbsen}</h3>
                <div style="width:2.5rem;"></div>
            </div>
            <div id="loadingCameraFull" style="flex:1;display:flex;flex-direction:column;align-items:center;justify-content:center;color:white;">
                <div class="spinner-border text-light" role="status" style="width:3rem;height:3rem;"><span class="visually-hidden">Loading...</span></div>
                <p style="margin-top:1rem;font-size:1rem;">Membuka kamera...</p>
            </div>
            <div id="videoContainerFull" style="flex:1;display:none;position:relative;overflow:hidden;">
                <video id="cameraFullScreen" autoplay playsinline style="width:100%;height:100%;object-fit:cover;transform:scaleX(-1);"></video>
                <div style="position:absolute;bottom:1rem;left:50%;transform:translateX(-50%);color:white;text-align:center;background:rgba(0,0,0,.5);padding:.5rem 1rem;border-radius:8px;font-size:.875rem;">
                    Pastikan wajah Anda terlihat jelas
                </div>
            </div>
            <div style="flex:0 0 auto;background:rgba(0,0,0,.9);padding:1.5rem;display:flex;gap:1rem;justify-content:center;">
                <button id="btnBatalCamera" style="flex:1;max-width:150px;padding:1rem;background:#6b7280;color:white;border:none;border-radius:12px;font-weight:600;">Batal</button>
                <button id="btnAmbilFoto" disabled style="flex:1;max-width:150px;padding:1rem;background:#10b981;color:white;border:none;border-radius:12px;font-weight:600;opacity:.5;">Ambil Foto</button>
            </div>
            <canvas id="canvasFull" style="display:none;"></canvas>
        `;

        document.body.appendChild(overlay);
        document.body.style.overflow = 'hidden';

        const videoEl        = document.getElementById('cameraFullScreen');
        const loadingDiv     = document.getElementById('loadingCameraFull');
        const videoContainer = document.getElementById('videoContainerFull');
        const btnAmbil       = document.getElementById('btnAmbilFoto');

        function tutupKamera() {
            if (stream) { stream.getTracks().forEach(t => t.stop()); stream = null; }
            document.body.removeChild(overlay);
            document.body.style.overflow = '';
            isProcessing = false;
        }

        document.getElementById('btnCloseCamera').addEventListener('click', tutupKamera);
        document.getElementById('btnBatalCamera').addEventListener('click', tutupKamera);

        btnAmbil.addEventListener('click', () => {
            if (!videoReady) return;
            const canvas = document.getElementById('canvasFull');
            const ctx    = canvas.getContext('2d');
            canvas.width  = videoEl.videoWidth;
            canvas.height = videoEl.videoHeight;
            ctx.save(); ctx.scale(-1, 1);
            ctx.drawImage(videoEl, -canvas.width, 0, canvas.width, canvas.height);
            ctx.restore();
            const fotoData = canvas.toDataURL('image/jpeg', 0.8);
            tutupKamera();
            kirimAbsen(urlAbsen, fotoData);
        });

        navigator.mediaDevices.getUserMedia({
            video: { facingMode: 'user', width: { ideal: 1280 }, height: { ideal: 720 } }
        }).then(s => {
            stream = s;
            videoEl.srcObject = s;
            videoEl.onloadedmetadata = () => videoEl.play();
            videoEl.onplaying = () => setTimeout(() => {
                videoReady = true;
                loadingDiv.style.display       = 'none';
                videoContainer.style.display   = 'block';
                btnAmbil.disabled              = false;
                btnAmbil.style.opacity         = '1';
            }, 500);
        }).catch(err => {
            tutupKamera();
            Swal.fire('Gagal', 'Kamera tidak dapat diakses: ' + err.message, 'error');
        });
    }

    function bukaKameraPopupDesktop(judulAbsen, urlAbsen) {
        Swal.fire({
            title: judulAbsen,
            html: `
                <div class="text-center">
                    <div id="loadingCamera" style="padding:20px 0;">
                        <div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div>
                        <p class="mt-2" style="font-size:.9rem;">Membuka kamera...</p>
                    </div>
                    <div style="display:none;position:relative;width:480px;height:360px;margin:0 auto;background:#000;border-radius:12px;overflow:hidden;" id="videoContainer">
                        <video id="cameraPopup" autoplay playsinline muted
                            style="width:100%!important;height:100%!important;display:block!important;transform:scaleX(-1)!important;object-fit:cover!important;position:absolute!important;top:0!important;left:0!important;">
                        </video>
                    </div>
                    <canvas id="canvasPopup" class="d-none"></canvas>
                    <p class="mt-2 mb-0" id="cameraInstruction" style="display:none;font-size:.875rem;color:#666;">
                        Pastikan wajah Anda terlihat jelas
                    </p>
                </div>`,
            showCancelButton: true,
            confirmButtonText: 'Ambil Foto & Kirim',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#10b981',
            cancelButtonColor: '#ef4444',
            allowOutsideClick: false,
            width: '600px',
            didOpen: () => {
                const swalContainer = document.querySelector('.swal2-html-container');
                if (swalContainer) { swalContainer.style.overflow = 'visible'; swalContainer.style.maxHeight = 'none'; }

                const videoEl        = document.getElementById('cameraPopup');
                const videoContainer = document.getElementById('videoContainer');
                const loadingDiv     = document.getElementById('loadingCamera');
                const instruction    = document.getElementById('cameraInstruction');
                const confirmBtn     = Swal.getConfirmButton();
                confirmBtn.style.display = 'none';
                confirmBtn.disabled = true;

                navigator.mediaDevices.getUserMedia({
                    video: { facingMode: 'user', width: { ideal: 640 }, height: { ideal: 480 } }
                }).then(s => {
                    stream = s;
                    videoEl.srcObject  = s;
                    videoEl.style.cssText = 'width:100%!important;height:100%!important;display:block!important;transform:scaleX(-1)!important;object-fit:cover!important;position:absolute!important;top:0!important;left:0!important;max-width:none!important;max-height:none!important;';
                    videoEl.onloadedmetadata = () => videoEl.play();
                    videoEl.onplaying = () => setTimeout(() => {
                        videoReady = true;
                        loadingDiv.style.display       = 'none';
                        videoContainer.style.display   = 'block';
                        instruction.style.display      = 'block';
                        confirmBtn.style.display       = 'inline-block';
                        confirmBtn.disabled            = false;
                    }, 300);
                }).catch(err => {
                    Swal.fire('Gagal', 'Kamera tidak dapat diakses: ' + err.message, 'error');
                    isProcessing = false;
                });
            },
            preConfirm: () => {
                if (!videoReady) { Swal.showValidationMessage('Tunggu hingga kamera siap...'); return false; }
                const video  = document.getElementById('cameraPopup');
                const canvas = document.getElementById('canvasPopup');
                const ctx    = canvas.getContext('2d');
                canvas.width  = video.videoWidth;
                canvas.height = video.videoHeight;
                ctx.save(); ctx.scale(-1, 1);
                ctx.drawImage(video, -canvas.width, 0, canvas.width, canvas.height);
                ctx.restore();
                return canvas.toDataURL('image/jpeg', 0.8);
            },
            willClose: () => {
                if (stream) { stream.getTracks().forEach(t => t.stop()); stream = null; }
                isProcessing = false;
            }
        }).then(result => {
            if (result.isConfirmed && result.value) {
                kirimAbsen(urlAbsen, result.value);
            }
        });
    }

    // --- KIRIM ABSEN (masuk & pulang — keduanya pakai foto + GPS) ---
    async function kirimAbsen(url, fotoData = null) {
        if (!navigator.geolocation) {
            Swal.fire('Error', 'Browser Anda tidak mendukung geolocation.', 'error');
            return;
        }

        Swal.fire({
            title: 'Mengirim absensi...',
            text: 'Mohon tunggu sebentar',
            allowOutsideClick: false,
            didOpen: () => Swal.showLoading()
        });

        try {
            const pos = await new Promise((resolve, reject) =>
                navigator.geolocation.getCurrentPosition(resolve, reject, {
                    timeout: 10000,
                    enableHighAccuracy: true
                })
            );

            const res  = await fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    latitude:  pos.coords.latitude,
                    longitude: pos.coords.longitude,
                    foto:      fotoData
                })
            });
            const data = await res.json();

            Swal.close();
            await new Promise(r => setTimeout(r, 100));

            Swal.fire({
                icon: data.status === 'success' ? 'success' : 'error',
                title: data.status === 'success' ? 'Berhasil!' : 'Gagal!',
                text: data.message,
                showConfirmButton: data.status !== 'success',
                timer: data.status === 'success' ? 2000 : undefined,
                timerProgressBar: true,
                buttonsStyling: false,
                customClass: { confirmButton: 'btn btn-danger' }
            }).then(() => { if (data.status === 'success') location.reload(); });

        } catch (error) {
            Swal.close();
            await new Promise(r => setTimeout(r, 100));
            Swal.fire('Error', 'Terjadi kesalahan: ' + error.message, 'error');
        }
    }

    // --- EVENT TOMBOL ---
    @if($statusGaji === 'harian')
    const btnMasukSesi = document.getElementById('btnMasukSesi');
    const btnPulangSesi = document.getElementById('btnPulangSesi');
    if (btnMasukSesi) {
        btnMasukSesi.addEventListener('click', () => {
            if (btnMasukSesi.disabled) return;
            bukaKameraPopup(btnMasukSesi.textContent.trim(), '{{ route("absen.sesi.masuk") }}');
        });
    }
    if (btnPulangSesi) {
        btnPulangSesi.addEventListener('click', () => {
            if (btnPulangSesi.disabled) return;
            bukaKameraPopup(btnPulangSesi.textContent.trim(), '{{ route("absen.sesi.pulang") }}');
        });
    }
    @else
    document.getElementById('btnMasuk').addEventListener('click', () => {
        if (document.getElementById('btnMasuk').disabled) return;
        bukaKameraPopup('Absen Masuk', '{{ route("absen.masuk") }}');
    });

    document.getElementById('btnPulang').addEventListener('click', () => {
        if (document.getElementById('btnPulang').disabled) return;
        bukaKameraPopup('Absen Pulang', '{{ route("absen.pulang") }}');
    });
    @endif
</script>
@endpush