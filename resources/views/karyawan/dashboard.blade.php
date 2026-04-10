@extends('karyawan.layouts.app')

@section('title', 'Dashboard Absensi Karyawan')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
    :root {
        --primary-color: #3b82f6;
        --success-color: #10b981;
        --danger-color: #ef4444;
        --warning-color: #f59e0b;
        --dark-color: #1f2937;
        --card-shadow: 0 4px 6px -1px rgba(0,0,0,.1), 0 2px 4px -1px rgba(0,0,0,.06);
        --card-shadow-lg: 0 10px 15px -3px rgba(0,0,0,.1), 0 4px 6px -2px rgba(0,0,0,.05);
    }

    #map {
        height: 300px;
        border-radius: 12px;
        box-shadow: var(--card-shadow);
        margin-bottom: 1.5rem;
    }

    @media (min-width: 768px) {
        #map { height: 400px; }
    }

    .clock-display {
        font-size: clamp(2rem, 8vw, 3.5rem);
        font-weight: 700;
        color: var(--dark-color);
        letter-spacing: -0.02em;
    }

    .date-display {
        font-size: clamp(0.875rem, 3vw, 1.125rem);
        color: #6b7280;
        font-weight: 500;
    }

    .header-card {
        background: linear-gradient(135deg, #ffffff 0%, #f9fafb 100%);
        border-radius: 16px;
        box-shadow: var(--card-shadow-lg);
        border: none;
    }

    .main-card {
        background: white;
        border-radius: 20px;
        box-shadow: var(--card-shadow-lg);
        border: none;
    }

    .status-card {
        background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);
        border-radius: 12px;
        padding: 1.25rem;
        margin-bottom: 1.5rem;
        border: 1px solid #e5e7eb;
    }

    .status-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0.5rem 0;
        font-size: clamp(0.875rem, 3vw, 1rem);
    }

    @media (max-width: 576px) {
        .status-row { flex-direction: column; align-items: flex-start; gap: .25rem; }
    }

    .btn-absen {
        padding: .875rem 2rem;
        font-weight: 600;
        font-size: clamp(.875rem, 3vw, 1rem);
        border-radius: 12px;
        transition: all .3s ease;
        box-shadow: var(--card-shadow);
        border: none;
        flex: 1;
        max-width: 200px;
    }

    @media (max-width: 576px) {
        .btn-absen { width: 100%; max-width: none; padding: 1rem; }
    }

    .btn-absen:not(:disabled):hover {
        transform: translateY(-2px);
        box-shadow: var(--card-shadow-lg);
    }

    .btn-success-absen { background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: #fff; }
    .btn-danger-absen  { background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); color: #fff; }
    .btn-absen:disabled { opacity: .5; cursor: not-allowed; transform: none !important; }

    .time-info {
        background: #fef3c7;
        border-left: 4px solid var(--warning-color);
        padding: .75rem 1rem;
        border-radius: 8px;
        margin-top: 1rem;
    }

    .time-info p {
        margin: .25rem 0;
        font-size: clamp(.75rem, 2.5vw, .875rem);
        color: #92400e;
    }

    .separatorr {
        height: 1px;
        background: linear-gradient(90deg, transparent, #e5e7eb, transparent);
        margin: 1.5rem 0;
    }

    .button-container {
        display: flex;
        gap: 1rem;
        justify-content: center;
        flex-wrap: wrap;
    }

    @media (max-width: 576px) {
        .button-container { flex-direction: column; gap: .75rem; }
    }

    .table-history th { font-size: .8rem; text-transform: uppercase; color: #6b7280; font-weight: 600; }
    .table-history td { font-size: .9rem; vertical-align: middle; }
</style>
@endpush

@section('content')
<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <div class="post d-flex flex-column-fluid" id="kt_post">
        <div id="kt_content_container" class="container-xxl px-3 px-md-4">

            {{-- HEADER --}}
            <div class="card header-card shadow-sm mb-4 mt-4">
                <div class="card-body text-center py-4">
                    <img src="{{ asset('') }}assets/img/logotsi.png" alt="logo"
                        style="height: clamp(50px,12vw,70px); object-fit: contain;" class="mb-3">
                    <h1 class="fw-bolder text-dark mb-1" style="font-size: clamp(1.1rem,4vw,1.5rem);">
                        PT. Tidarjaya Solidindo
                    </h1>
                    <div class="text-muted fw-semibold" id="greetingText" style="font-size: clamp(.9rem,3vw,1.1rem);">
                        Selamat Datang
                    </div>
                </div>
            </div>

            {{-- ABSENSI + RIWAYAT --}}
            <div class="row g-4 mb-4">

                {{-- ABSENSI HARI INI --}}
                <div class="col-12 col-xl-7">
                    <div class="card main-card h-100">
                        <div class="card-header border-0 pt-4 px-4">
                            <h3 class="card-title fw-bolder text-dark mb-0" style="font-size: clamp(1rem,3.5vw,1.35rem);">
                                Absensi Hari Ini
                            </h3>
                        </div>
                        <div class="card-body pt-3 px-4">

                            {{-- MAP --}}
                            <div id="map"></div>

                            {{-- CLOCK --}}
                            <div class="text-center mb-4">
                                <div id="clock" class="clock-display mb-1"></div>
                                <div class="date-display" id="today"></div>
                            </div>

                            {{-- STATUS --}}
                            <div class="status-card">
                                <div class="status-row mb-2">
                                    <span class="fw-bold">Status Kehadiran:</span>
                                    <div>
                                        @if (is_null($cek))
                                            <span class="badge badge-light-danger">Belum Absen</span>
                                        @else
                                            @switch($cek->status)
                                                @case('hadir')
                                                    <span class="badge badge-light-success">&#10003; Hadir</span>
                                                    @break
                                                @case('izin')
                                                    <span class="badge badge-light-warning">Izin</span>
                                                    @break
                                                @case('alpha')
                                                    <span class="badge badge-light-danger">&#10007; Alpha</span>
                                                    @break
                                                @case('terlambat')
                                                    <span class="badge badge-light-danger">Terlambat</span>
                                                    @break
                                                @default
                                                    <span class="badge badge-light-danger">Belum Absen</span>
                                            @endswitch
                                        @endif
                                    </div>
                                </div>

                                <div class="status-row">
                                    <span class="fw-semibold">Waktu Datang:</span>
                                    <span class="badge badge-light-dark">{{ $cek->jam_masuk ?? '00:00:00' }}</span>
                                </div>

                                <div class="status-row">
                                    <span class="fw-semibold">Waktu Pulang:</span>
                                    <span class="badge badge-light-dark">{{ $cek->jam_keluar ?? '00:00:00' }}</span>
                                </div>
                            </div>

                            {{-- BUTTONS --}}
                            <div class="button-container mb-4">
                                <button id="btnMasuk"
                                    class="btn btn-absen btn-success-absen"
                                    {{ ($cek && $cek->jam_masuk) ? 'disabled' : '' }}>
                                    Absen Masuk
                                </button>
                                <button id="btnPulang"
                                    class="btn btn-absen btn-danger-absen"
                                    {{ (!$cek || !$cek->jam_masuk || $cek->jam_keluar) ? 'disabled' : '' }}>
                                    Absen Pulang
                                </button>
                            </div>

                            <div class="separatorr"></div>

                            <div class="time-info">
                                <p class="mb-1"><strong>Jam Kerja:</strong></p>
                                <p class="mb-1">
                                    &bull; Absen Masuk:
                                    <strong>{{ $shift_start ? \Carbon\Carbon::parse($shift_start)->format('H:i') : '-' }} WIB</strong>
                                </p>
                                <p class="mb-0">
                                    &bull; Absen Pulang:
                                    <strong>{{ $shift_end ? \Carbon\Carbon::parse($shift_end)->format('H:i') : '-' }} WIB</strong>
                                </p>
                            </div>

                        </div>
                    </div>
                </div>

                {{-- RIWAYAT ABSENSI --}}
                <div class="col-12 col-xl-5">
                    <div class="card main-card h-100">
                        <div class="card-header border-0 pt-4 px-4">
                            <h3 class="card-title fw-bolder text-dark mb-0" style="font-size: clamp(1rem,3.5vw,1.35rem);">
                                Riwayat Absensi
                            </h3>
                        </div>
                        <div class="card-body px-4 pt-3">
                            <div class="table-responsive">
                                <table class="table table-hover table-history">
                                    <thead>
                                        <tr>
                                            <th>Tanggal</th>
                                            <th>Masuk</th>
                                            <th>Pulang</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($riwayatAbsensi as $r)
                                        <tr>
                                            <td>{{ \Carbon\Carbon::parse($r->tanggal)->format('d/m/Y') }}</td>
                                            <td>{{ $r->jam_masuk ? \Carbon\Carbon::parse($r->jam_masuk)->format('H:i') : '-' }}</td>
                                            <td>{{ $r->jam_keluar ? \Carbon\Carbon::parse($r->jam_keluar)->format('H:i') : '-' }}</td>
                                            <td>
                                                @switch($r->status)
                                                    @case('hadir')
                                                        <span class="badge badge-light-success">Hadir</span>
                                                        @break
                                                    @case('terlambat')
                                                        <span class="badge badge-light-warning">Terlambat</span>
                                                        @break
                                                    @case('izin')
                                                        <span class="badge badge-light-primary">Izin</span>
                                                        @break
                                                    @case('alpha')
                                                        <span class="badge badge-light-danger">Alpha</span>
                                                        @break
                                                    @default
                                                        <span class="badge badge-light-secondary text-capitalize">{{ $r->status }}</span>
                                                @endswitch
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
                    </div>
                </div>

            </div>

        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
    // --- GREETING ---
    const hours = new Date().getHours();
    let greeting = "Selamat Datang";
    if      (hours >= 4  && hours < 11) greeting = "Selamat Pagi";
    else if (hours >= 11 && hours < 15) greeting = "Selamat Siang";
    else if (hours >= 15 && hours < 18) greeting = "Selamat Sore";
    else greeting = "Selamat Malam";
    document.getElementById('greetingText').innerHTML =
        greeting + ', <strong class="text-capitalize">{{ $namaKaryawan }}</strong>!';

    // --- MAP ---
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

    // --- CLOCK ---
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
    document.getElementById('btnMasuk').addEventListener('click', () => {
        if (document.getElementById('btnMasuk').disabled) return;
        bukaKameraPopup('Absen Masuk', '{{ route("absen.masuk") }}');
    });

    document.getElementById('btnPulang').addEventListener('click', () => {
        if (document.getElementById('btnPulang').disabled) return;
        bukaKameraPopup('Absen Pulang', '{{ route("absen.pulang") }}');
    });
</script>
@endpush
